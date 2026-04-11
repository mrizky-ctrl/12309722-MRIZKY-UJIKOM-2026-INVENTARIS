<?php

namespace App\Http\Controllers;

use App\Models\Lending;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\LendingExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LendingController extends Controller
{
    // Menampilkan daftar peminjaman
    public function index(Request $request)
    {
        $query = Lending::with(['item', 'user']);
        // Filter Pencarian (Nama Peminjam atau Nama Barang)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhereHas('item', function ($itemQuery) use ($search) {
                        $itemQuery->where('name', 'like', "%$search%");
                    });
            });
        }
        // Filter Tanggal Peminjaman
        if ($request->filled('date_lending')) {
            $query->whereDate('date_lending', $request->date_lending);
        }
        // Filter Tanggal Pengembalian (Berdasarkan updated_at saat is_returned true)
        if ($request->filled('date_returned')) {
            $query->where('is_returned', true)
                ->whereDate('updated_at', $request->date_returned);
        }
        // Filter Status (Sudah Kembali = 1, Belum = 0)
        if ($request->filled('status')) {
            $query->where('is_returned', $request->status);
        }
        $lendings = $query->orderBy('date_lending', 'desc')->get();
        $items = Item::where('total', '>', 0)->get();

        $stats = [
            'total_transaksi' => Lending::count(),
            'total_barang'    => Item::sum('total'), // Sesuaikan nama kolom jika perlu
            'dipinjam'        => Lending::where('is_returned', false)->count(),
            'belum_kembali'   => Lending::where('is_returned', false)->count(), // Ini sama dengan dipinjam
        ];

        $lendings = $query->orderBy('date_lending', 'desc')->get();
        $items = Item::where('total', '>', 0)->get();

        return view('staff.lendings.index', compact('lendings', 'items', 'stats'));
    }
    public function create()
    {
        $items = Item::where('total', '>', 0)->get();
        return view('staff.lendings.create', compact('items'));
    }

    // PROSES SIMPAN: Mendukung Fitur "Add More" (Array Input)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'item_id' => 'required|array',
            'qty' => 'required|array',
            'date_lending' => 'required|date',
            'signature' => 'required', // Tetap validasi tanda tangan
        ]);

        // Looping untuk memproses setiap barang yang ditambahkan
        foreach ($request->item_id as $index => $id) {
            $item = Item::findOrFail($id);
            $qtyPinjam = $request->qty[$index];

            if ($item->available < $qtyPinjam) {
                return back()->with('error', "Stok tidak cukup untuk {$item->name}!");
            }

            // Simpan setiap item sebagai baris baru di tabel lendings
            Lending::create([
                'name'         => $request->name,
                'item_id'      => $id,
                'qty'          => $qtyPinjam,
                'user_id'      => Auth::id(),
                'date_lending' => $request->date_lending,
                'notes'        => $request->notes,
                'signature'    => $request->signature, // Simpan tanda tangan di sini
                'is_returned'  => false,
            ]);

            // Opsional: Kurangi stok di tabel items
            $item->decrement('available', $qtyPinjam);
        }

        return redirect()->route('lendings.index')->with('success', 'Peminjaman berhasil dicatat!');
    }

    // PROSES PENGEMBALIAN: Menambah stok kembali
    public function update(Request $request, $id)
    {
        $lending = Lending::findOrFail($id);

        // 1. Validasi Input
        $request->validate([
            'qty_baik'       => 'required|integer|min:0',
            'qty_rusak'      => 'required|integer|min:0',
            'qty_hilang'     => 'required|integer|min:0',
            'penalty_amount' => 'required|numeric|min:0',
        ]);

        // 2. Cek total qty apakah sesuai dengan yang dipinjam
        $totalKembali = $request->qty_baik + $request->qty_rusak + $request->qty_hilang;

        if ($totalKembali != $lending->qty) {
            return back()->with('error', "Total barang yang dikembalikan ($totalKembali) harus sama dengan jumlah dipinjam ($lending->qty)");
        }

        // 3. Update status peminjaman
        $lending->update([
            'is_returned'     => true,
            'condition'       => "Baik: {$request->qty_baik}, Rusak: {$request->qty_rusak}, Hilang: {$request->qty_hilang}",
            'qty_broken'      => $request->qty_rusak,
            'penalty_amount'  => $request->penalty_amount,
            'is_penalty_paid' => ($request->penalty_amount > 0) ? false : true,
        ]);

        // 4. PENTING: Tambahkan kembali stok barang yang kembali dalam kondisi BAIK
        // Hanya barang 'Baik' yang bisa dipinjam kembali
        $item = Item::findOrFail($lending->item_id);
        $item->increment('available', $request->qty_baik);

        return redirect()->back()->with('success', 'Pengembalian berhasil dicatat dan stok telah diperbarui.');
    }
    public function payPenalty($id)
    {
        $lending = Lending::findOrFail($id);
        $lending->update(['is_penalty_paid' => true]);
        return redirect()->back()->with('success', 'Denda telah dilunasi.');
    }

    public function lendingDetail($id)
    {
        // Mengambil item beserta semua history peminjamannya
        $item = Item::with(['lendings.user'])->findOrFail($id);

        return view('admin.lendingdetail', compact('item'));
    }

    public function destroy($id)
    {
        $lending = Lending::findOrFail($id);
        $lending->delete();
        return redirect()->back()->with('success', 'Data peminjaman dihapus.');
    }

    public function printReceipt($id)
    {
        $lending = Lending::with(['item', 'user'])->findOrFail($id);

        // Load view untuk PDF
        $pdf = Pdf::loadView('staff.lendings.receipt', compact('lending'));

        // Download atau Stream (tampil di browser)
        return $pdf->stream('Struk_Peminjaman_' . $lending->id . '.pdf');
    }
    public function exportExcel()
    {
        return Excel::download(new LendingExport, 'lending-report.xlsx');
    }
}
