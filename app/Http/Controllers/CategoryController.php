<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        // Mengambil kategori beserta jumlah item yang terkait
        $categories = Category::withCount('items')->get();
        return view('admin.categories', compact('categories'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'division_pj' => 'required'
        ]);
        Category::create($request->all());
        return back()->with('success', 'Kategori berhasil ditambahkan');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'division_pj' => 'required'
        ]);
        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->name,
            'division_pj' => $request->division_pj
        ]);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }
    public function destroy($id)
{
    $category = Category::findOrFail($id);
    $category->delete();

    return redirect()->back()->with('success', 'Category deleted successfully!');
}
}
