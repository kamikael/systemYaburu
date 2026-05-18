<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\AttachmentFile;
use App\Models\AttachmentFileProduct;
use App\Models\ProductType;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 🔹 GET produits d'une store
    public function index($store_id)
    {
        return Product::where('store_id', $store_id)->get();
    }

    // 🔹 CREATE produit
    public function store(Request $request, $store_id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_instructions' => 'nullable|string',

            'price' => 'required|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'min_quantity' => 'nullable|integer|min:0',
            'max_quantity' => 'nullable|integer|min:0',

            'price_promo' => 'nullable|numeric|min:0',
            'start_promo' => 'nullable|date',
            'end_promo' => 'nullable|date',

            'product_type_id' => 'required|exists:product_types,id',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $data['store_id'] = $store_id;
        // 🔹 create product
       $product = Product::create([
    ...$data
]);

        // 🔹 upload images (nouveau système attachments)
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $index => $image) {

                $path = $image->store('products', 'public');

                $file = AttachmentFile::create([
                    'uuid' => \Str::uuid(),
                    'type' => 'image',
                    'file_name' => basename($path),
                    'file_name_original' => $image->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $image->getSize(),
                    'file_type' => $image->getClientMimeType(),
                    'client_session_id' => null,
                ]);

                AttachmentFileProduct::create([
                    'attachment_file_id' => $file->id,
                    'product_id' => $product->id,
                    'position' => $index,
                ]);
            }
        }

        return response()->json($product->load('productType'), 201);
    }

    // 🔹 GET 1 produit
    public function show($store_id, $id)
    {
        return Product::where('id', $id)
            ->where('store_id', $store_id)
            ->firstOrFail();
    }

    // 🔹 UPDATE produit
    public function update(Request $request, $store_id, $id)
    {
        $product = Product::where('id', $id)
            ->where('store_id', $store_id)
            ->firstOrFail();

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'purchase_instructions' => 'nullable|string',

            'price' => 'sometimes|numeric|min:0',
            'quantity' => 'sometimes|integer|min:0',

            'price_promo' => 'nullable|numeric|min:0',
            'start_promo' => 'nullable|date',
            'end_promo' => 'nullable|date',
        ]);

        $product->update($data);

        return response()->json($product);
    }

    // 🔹 DELETE produit
    public function destroy($store_id, $id)
    {
        $product = Product::where('id', $id)
            ->where('store_id', $store_id)
            ->firstOrFail();

        $product->delete();

        return response()->json([
            'message' => 'Produit supprimé'
        ]);
    }
}