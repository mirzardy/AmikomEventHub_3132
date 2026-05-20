<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);

        if (Category::where('slug', $data['slug'])->exists()) {
            return back()->withErrors(['slug' => 'Slug kategori sudah digunakan.'])->withInput();
        }

        Category::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);

        if (Category::where('slug', $data['slug'])->whereKeyNot($category->id)->exists()) {
            return back()->withErrors(['slug' => 'Slug kategori sudah digunakan.'])->withInput();
        }

        $category->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->events()->exists()) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih dipakai oleh event.');
        }

        $category->delete();

        return redirect()->route('admin.events.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
