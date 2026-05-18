<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    /**
     * Créer un store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'domain_alias' => 'nullable|string|max:255',
        ]);
        $account = auth()->user()->accounts()->first();
        $store = Store::create([
            'account_id' => $account->id,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . uniqid(),
            'domain' => $validated['domain'] ?? null,
            'domain_alias' => $validated['domain_alias'] ?? null,
        ]);

        return response()->json([
            'message' => 'Store créé avec succès',
            'store' => $store
        ], 201);
    }

    /**
     * Récupérer les stores de l'utilisateur
     */
    public function userStores($id)
    {
        if (auth()->user()->id !== (int) $id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $stores = Store::where('account_id', $id)->get();

        return response()->json($stores);
    }

    /**
     * Afficher un store
     */
    public function show($id)
    {
        $store = Store::findOrFail($id);

        if (auth()->user()->id !== $store->account_id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        return response()->json($store);
    }

    /**
     * Mettre à jour un store
     */
    public function update(Request $request, Store $store)
    {
        if (auth()->user()->id !== $store->account_id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'domain' => 'nullable|string|max:255',
            'domain_alias' => 'nullable|string|max:255',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();
        }

        $store->update($validated);

        return response()->json([
            'message' => 'Store mis à jour',
            'store' => $store
        ]);
    }

    /**
     * Supprimer un store
     */
    public function destroy(Store $store)
    {
        if (auth()->user()->id !== $store->account_id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $store->delete();

        return response()->json([
            'message' => 'Store supprimé'
        ]);
    }
}