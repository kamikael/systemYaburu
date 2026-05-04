<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 🔹 GET
    public function index($shop_id)
    {
        return Category::all();
    }

    // 🔹 CREATE
    public function store(Request $request, $shop_id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string'
        ]);

        return Category::create($data);
    }

    // 🔹 UPDATE
    public function update(Request $request, $shop_id, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $category->update($data);

        return $category;
    }

    // 🔹 DELETE
    public function destroy($shop_id, $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Catégorie supprimée']);
    }
}