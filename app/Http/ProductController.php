<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 🔹 GET produits d'une boutique
    public function index($shop_id)
    {
        return Product::where('shop_id', $shop_id)->get();
    }

    // 🔹 CREATE produit
    public function store(Request $request, $shop_id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $data['shop_id'] = $shop_id;

        return Product::create($data);
    }

    // 🔹 GET 1 produit
    public function show($shop_id, $id)
    {
        return Product::where('shop_id', $shop_id)->findOrFail($id);
    }

    // 🔹 UPDATE
    public function update(Request $request, $shop_id, $id)
    {
        $product = Product::where('shop_id', $shop_id)->findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $product->update($data);

        return $product;
    }

    // 🔹 DELETE
    public function destroy($shop_id, $id)
    {
        $product = Product::where('shop_id', $shop_id)->findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Produit supprimé']);
    }
}