<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    // 🔹 GET all product types
    public function index()
    {
        return ProductType::all();
    }

    // 🔹 CREATE product type
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $productType = ProductType::create($data);

        return response()->json($productType, 201);
    }

    // 🔹 UPDATE product type
    public function update(Request $request, $id)
    {
        $productType = ProductType::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $productType->update($data);

        return response()->json($productType);
    }

    // 🔹 DELETE product type
    public function destroy($id)
    {
        $productType = ProductType::findOrFail($id);
        $productType->delete();

        return response()->json([
            'message' => 'Type de produit supprimé'
        ]);
    }
}