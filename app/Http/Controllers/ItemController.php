<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemsExport;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        // Mengambil items dengan hitungan lending dan detail lending beserta user yang menginputnya
        $items = Item::with(['category', 'lendings.user'])->withCount('lendings')->get();
        $categories = Category::all();
        if (Auth::user()->role == 'admin') {
            return view('admin.items', compact('items', 'categories'));
        } else {
            return view('staff.items.index', compact('items'));
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'total' => 'required|numeric'
        ], [
            'name.required' => 'The name field is required.',
            'category_id.required' => 'The category id field is required.',
            'total.required' => 'The total field is required.',
        ]);

        Item::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'total' => $request->total,
            'repair' => 0
        ]);

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'total' => 'required|numeric',
            'new_broke_item' => 'nullable|numeric|min:0'
        ]);

        // Logika Akumulasi Repair: Repair Lama + Input Baru
        $totalRepair = $item->repair + ($request->new_broke_item ?? 0);

        $item->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'total' => $request->total,
            'repair' => $totalRepair
        ]);

        return redirect()->back()->with('success', 'Barang berhasil diperbarui');
    }
    public function lendingDetail($id)
    {
        // Mengambil data item beserta relasi lendings dan usernya
        $item = Item::with(['lendings.user'])->findOrFail($id);

        // Mengarah ke resources/views/admin/lending_detail.blade.php
        return view('admin.lendingdetail', compact('item'));
    }
    public function destroy($id)
    {
        Item::destroy($id);
        return redirect()->back()->with('success', 'Barang berhasil dihapus');
    }
    public function exportExcel()
    {
        return Excel::download(new ItemsExport, 'items.xlsx');
    }
}
