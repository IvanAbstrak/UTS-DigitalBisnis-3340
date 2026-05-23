<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        // Logika Pencarian (Soal 3)
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $categories = $query->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate(['name' => 'required|string|max:255']);

        $data = $request->all();
        $data['slug'] = str::slug($request->name);

        // Simpan data
        Category::create($data);

        return redirect()->route('admin.categories.index');
    }

    public function show(Category $category)
    {
        // Biasanya tidak dipakai di admin panel sederhana, biarkan kosong
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // Validasi input
        $request->validate(['name' => 'required|string|max:255']);

        $data = $request->all();
        $data['slug'] = str::slug($request->name);
        // Update data
        $category->update($data);

        return redirect()->route('admin.categories.index');
    }

    public function destroy(Category $category)
    {
        // Hapus data
        $category->delete();
        return redirect()->route('admin.categories.index');
    }
}
