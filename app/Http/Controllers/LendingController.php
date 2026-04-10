<?php

namespace App\Http\Controllers;

use App\Models\Lending;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\LendingExport;
use Maatwebsite\Excel\Facades\Excel;

class LendingController extends Controller
{
    // Menampilkan daftar peminjaman
    public function index()
    {
        // Mengambil data dengan relasi agar nama barang dan penginput muncul
        $lendings = Lending::with(['item', 'user'])->orderBy('date_lending', 'desc')->get();
        $items = Item::where('total', '>', 0)->get();

        return view('staff.lendings.index', compact('lendings', 'items'));
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
            'item_id' => 'required|array', // Harus array karena fitur 'More'
            'qty' => 'required|array',     // Harus array
            'date_lending' => 'required|date',
        ]);

        // Looping untuk memproses setiap barang yang ditambahkan melalui tombol "More"
        foreach ($request->item_id as $index => $id) {
            $item = Item::findOrFail($id);
            $qtyPinjam = $request->qty[$index];

            // Ganti validasi agar mengecek ke 'available', bukan 'total'
            if ($item->available < $qtyPinjam) {
                return back()->with('error', "Stok tidak cukup untuk {$item->name}!");
            }
            Lending::create([
                'name' => $request->name,
                'item_id' => $id,
                'user_id' => Auth::id(),
                'qty' => $qtyPinjam,
                'date_lending' => $request->date_lending,
                'is_returned' => false,
                'notes' => $request->notes,
            ]);
        }
        return redirect()->route('lendings.index')->with('success', 'Success add new lending item!');
    }

    // PROSES PENGEMBALIAN: Menambah stok kembali
    public function update(Request $request, $id)
    {
        $lending = Lending::findOrFail($id);
        if ($lending->is_returned) {
            return back()->with('error', 'Item already returned.');
        }
        $lending->update(['is_returned' => true]);
        return redirect()->back()->with('success', 'Item is returned!');
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

    public function exportExcel()
    {
        return Excel::download(new LendingExport, 'lending-report.xlsx');
    }
}
