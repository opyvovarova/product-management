<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return Inertia::render('Products/Index',
            ['products' => $products]
        );
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'quantity'=> 'required|integer',
            'price' => 'required|numeric'
        ]);

        Product::create($validatedData);

        return redirect()->route('products.index');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOfFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric'
        ]);

        $product->update($validatedData);

        return redirect()->route('products.index');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->route('products.index');
    }
}
