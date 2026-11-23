<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class categoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('categories.index', compact('categories'));

    }
    public function create()
    {
        return view('categories.create');
    }
    public function store(Request $request)
    {
        Category::create($request)->validate(['name'=> 'required', 'description' => 'nullable']);
        return redirect('/categories')->with('success', 'Kategori ditambahkan');
    }
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }
}
