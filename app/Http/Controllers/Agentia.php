<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Store;
use App\Models\UserAccount;
use App\Models\AttachmentFile;
use App\Models\AttachmentFileProduct;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Agentia extends Controller
{
    use AuthorizesRequests;

    // 🔹 GET PRODUCTS
    public function get_products($store_id)
    {
        if (
    !auth()->user()->is_admin &&
    !auth()->user()->is_agentia
) {
    return response()->json([
        'message' => 'Accès refusé'
    ], 403);
}

        $products = Product::where('store_id', $store_id)->get();

        return response()->json($products);
    }

    // 🔹 STATS DASHBOARD
    public function get_stat($store_id)
    {
                if (
            !auth()->user()->is_admin &&
            !auth()->user()->is_agentia
        ) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        // TOTAL PRODUITS
        $totalProduct = Product::where('store_id', $store_id)->count();

        // TOP 5 PRODUITS LES PLUS VENDUS
        $topFiveBestProduct = OrderDetail::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->limit(5)
            ->get();

        // TOTAL COMMANDES
        $totalSell = Order::where('store_id', $store_id)->count();

        // COMMANDES ANNULÉES
        $canceledSell = Order::where('store_id', $store_id)
            ->where('transaction_status', 'canceled')
            ->count();

        // COMMANDES EN ATTENTE
        $pendingSell = Order::where('store_id', $store_id)
            ->where('transaction_status', 'pending')
            ->count();

        // TOTAL CLIENTS
        $totalCustomers = Order::where('store_id', $store_id)
            ->distinct('client_email')
            ->count('client_email');

        // TOP 5 CLIENTS
        $topFiveBestCustomer = Order::select(
                'client_email',
                'client_firstname',
                DB::raw('SUM(amount_total) as total_spent')
            )
            ->where('store_id', $store_id)
            ->groupBy('client_email', 'client_firstname')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        // REVENU TOTAL (toutes commandes payées)
        $totalIncome = Order::where('store_id', $store_id)
            ->where('transaction_status', 'paid')
            ->sum('amount_total');

        // REVENU PAYÉ
        $incomePaid = $totalIncome;

        return response()->json([
            'totalProduct' => $totalProduct,
            'topFiveBestProduct' => $topFiveBestProduct,
            'totalSell' => $totalSell,
            'canceledSell' => $canceledSell,
            'pendingSell' => $pendingSell,
            'totalCustomers' => $totalCustomers,
            'topFiveBestCustomer' => $topFiveBestCustomer,
            'totalIncome' => $totalIncome,
            'incomePaid' => $incomePaid,
        ]);
    }

    // 🔹 GET ORDERS
    public function get_orders($store_id)
    {
            if (
            !auth()->user()->is_admin &&
            !auth()->user()->is_agentia
        ) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        $orders = Order::where('store_id', $store_id)->get();

        return response()->json($orders);
    }

   // 🔹 CHECK USER
public function check_user(Request $request)
{
    // 🔥 Vérification accès admin / agentia
    if (
        !auth()->user()->is_admin &&
        !auth()->user()->is_agentia
    ) {
        return response()->json([
            'message' => 'Accès refusé'
        ], 403);
    }

    // 🔥 Validation
    $validated = $request->validate([
        'phone' => 'required|string'
    ]);

    // 🔥 Recherche utilisateur
    $user = User::where('phone', $validated['phone'])
        ->first();

    if (!$user) {
        return response()->json([
            'message' => 'Utilisateur introuvable'
        ], 404);
    }

    // 🔥 récupérer tous les comptes liés
    $accountIds = UserAccount::where('user_id', $user->id)
        ->pluck('account_id');

    // 🔥 récupérer toutes les boutiques liées aux comptes
    $stores = Store::whereIn('account_id', $accountIds)
        ->get();

    return response()->json([
        'user' => $user,
        'stores' => $stores,
    ]);
}
    // 🔹 CREATE produit
    public function store(Request $request, $store_id)
    {
                if (
            !auth()->user()->is_admin &&
            !auth()->user()->is_agentia
        ) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }
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

        // 🔹 create product
        $product = Product::create([
            ...$data,
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
}