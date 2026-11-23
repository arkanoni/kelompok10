<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ControllerTesting extends Controller
{

    // AUTH ya ges ya
    public function login()
    {
        return response()->view('auth.login');
    }
    public function register()
    {
        return response()->view('auth.register');
    }

    // dashboard ya ges ya
    public function dashboard()
    {
        return response()->view('dashboard');
    }

    // folder resources/views/categories
    public function formCategories()
    {
        return response()->view('categories._form');
    }
    public function createCategories()
    {

        return view('categories.create');
    }
    public function store(Request $request){
        Category::create($request->validate(['name'=>'required', 'description'=> 'nullable']));
        return redirect('/categories')->with('success', 'Kategori ditambahkan');
    }
    public function editCategories()
    {
        return view('categories.edit', compact('category'));
    }
    public function indexCategories()
    {
        $categories = Category::withCount('products')->get();
        return view('categories.index', compact('categories'));
        // return response()->view('categories.index');
    }

    // folder resources/views/orders
    public function indexOrders()
    {
        return response()->view('orders.index');
    }
    public function showOrders()
    {
        return response()->view('orders.show');
    }

    // products
    public function formProducts()
    {
        return response()->view('products._form');
    }
    public function createProducts()
    {
        return response()->view('products.create');
    }
    public function editProducts()
    {
        return response()->view('products.edit');
    }
    public function indexProducts()
    {
        return response()->view('products.index');
    }
    public function stockProducts()
    {
        return response()->view('products.stock');
    }

    // profile
    public function editProfile()
    {
        return response()->view('profile.edit');
    }

    // reports
    public function inventory()
    {
        return response()->view('reports.inventory');
    }

    // shop
    public function checkout()
    {
        return response()->view('shop.checkout');
    }
    public function indexShop()
    {
        return response()->view('shop.index');
    }
    public function ordershow()
    {
        return response()->view('shop.order-show');
    }
    public function orders()
    {
        return response()->view('shop.orders');
    }

    // users
    public function formUsers()
    {
        return response()->view('users._form');
    }
    public function createUsers()
    {
        return response()->view('users.create');
    }
    public function editUsers()
    {
        return response()->view('users.edit');
    }
    public function indexUsers()
    {
        return response()->view('users.index');
    }

}
