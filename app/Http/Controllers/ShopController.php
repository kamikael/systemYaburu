<?php

namespace App\Http\Controllers;

use App\Models\shops;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 

class ShopController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
            $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
        ]);

        $shop = Shops::create([
            'user_id' => auth()->user()->id,
            'shop_name' => $validated['shop_name'],
            'shop_slug' => Str::slug($validated['shop_name']) . '-' . uniqid(),
            'description' => $validated['description'] ?? null,
            'logo_url' => $validated['logo_url'] ?? null,
        ]);

        return response()->json(['message' => 'Boutique créée', 'shop' => $shop], 201);
    }


    // Récupérer les boutiques de l'utilisateur
    public function userShops($id)
    {
        if (auth()->user()->id !== (int)$id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $shops = Shops::where("user_id", $id)->get();
        return response()->json($shops);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $shop = Shops::findOrFail("id", $id);
          if (auth()->user()->id !== $shop->user_id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        return response()->json($shop);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, shops $shops)
    {
        $shop = Shops::findOrFail($id);

        if (auth()->user()->id !== $shop->user_id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'shop_name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
        ]);

        $shop->update($validated);

        return response()->json(['message' => 'Boutique mise à jour', 'shop' => $shop]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(shops $shops)
    {
        $shop = Shops::findOrFail($id);

        if (auth()->user()->id !== $shop->user_id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $shop->delete();

        return response()->json(['message' => 'Boutique supprimée']);
    }
}
