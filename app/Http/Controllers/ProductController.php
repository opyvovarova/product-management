<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest()->get();

        if ($request->header('Accept') === 'application/json') {
            return response()->json($products);
        }

        return Inertia::render('Products/Index',
            ['products' => $products]
        );

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'quantity'=> 'required|integer',
            'price' => 'required|numeric'
        ]);

        Product::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        return redirect()->route('products.index')->with('message', 'Product created successfully');

    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

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
