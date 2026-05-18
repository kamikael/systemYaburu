<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * 🔹 GET commandes d'un store
     */
    public function index($store_id)
    {
        $orders = Order::where('store_id', $store_id)
            ->with([
                'orderDetails.product'
            ])
            ->latest()
            ->get();

        return response()->json($orders);
    }

    /**
     * 🔹 CREATE commande
     */
    public function store(Request $request, $store_id)
    {
        $data = $request->validate([

            // 🔥 infos client
            'client_firstname' => 'required|string|max:255',
            'client_lastname' => 'required|string|max:255',

            'client_email' => 'nullable|email',
            'client_phone' => 'required|string|max:20',
            'client_contact' => 'nullable|string|max:255',

            'client_country' => 'nullable|string|max:255',
            'client_district' => 'nullable|string|max:255',
            'client_city' => 'nullable|string|max:255',
            'client_address' => 'nullable|string',

            // 🔥 paiement
            'payment_method' => 'nullable|string|max:255',

            // 🔥 produits
            'items' => 'required|array|min:1',

            'items.*.product_id' => 'required|exists:products,id',

            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {

            $amountTotal = 0;

            // 🔥 création commande
            $order = Order::create([

                'store_id' => $store_id,

                'order_number' => strtoupper(
                    'ORD-' . Str::random(10)
                ),

                'amount_total' => 0,

                'transaction_id' => null,
                'transaction_status' => 'pending',

                'payment_method' => $data['payment_method'] ?? null,

                'client_firstname' => $data['client_firstname'],
                'client_lastname' => $data['client_lastname'],

                'client_email' => $data['client_email'] ?? null,
                'client_phone' => $data['client_phone'] ?? null,
                'client_contact' => $data['client_contact'] ?? null,

                'client_country' => $data['client_country'] ?? null,
                'client_district' => $data['client_district'] ?? null,
                'client_city' => $data['client_city'] ?? null,
                'client_address' => $data['client_address'] ?? null,
            ]);

            // 🔥 création détails
            foreach ($data['items'] as $item) {

               $product = Product::where('store_id', $store_id)
    ->where('id', $item['product_id'])
    ->firstOrFail();

                // 🔥 vérifier stock
                if (
                    !is_null($product->quantity)
                    &&
                    $product->quantity < $item['quantity']
                ) {
                    return response()->json([
                        'message' => "Stock insuffisant pour {$product->name}"
                    ], 400);
                }

                $subtotal = $product->price * $item['quantity'];

                $amountTotal += $subtotal;

                // 🔥 créer détail commande
                OrderDetail::create([
                    'order_id' => $order->id,

                    'product_id' => $product->id,

                    'quantity' => $item['quantity'],

                    'unit_price' => $product->price,
                ]);

                // 🔥 réduire stock si physique
                if (!is_null($product->quantity)) {

                    $product->decrement(
                        'quantity',
                        $item['quantity']
                    );
                }
            }

            // 🔥 update total
            $order->update([
                'amount_total' => $amountTotal
            ]);

            DB::commit();

            return response()->json(
                $order->load([
                    'orderDetails.product'
                ]),
                201
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Erreur création commande',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔹 GET 1 commande
     */
    public function show($store_id, $id)
    {
        $order = Order::where('store_id', $store_id)
            ->where('id', $id)
            ->with([
                'orderDetails.product'
            ])
            ->firstOrFail();

        return response()->json($order);
    }

    /**
     * 🔹 UPDATE STATUS
     */
    public function updateStatus(Request $request, $store_id, $id)
    {
        $order = Order::where('store_id', $store_id)
            ->where('id', $id)
            ->firstOrFail();

        $data = $request->validate([
            'transaction_status' => 'required|in:pending,paid,canceled,failed'
        ]);

        $order->update([
            'transaction_status' => $data['transaction_status']
        ]);

        return response()->json($order);
    }

    /**
     * 🔹 DELETE commande
     */
    public function destroy($store_id, $id)
    {
        $order = Order::where('store_id', $store_id)
            ->where('id', $id)
            ->firstOrFail();

        $order->delete();

        return response()->json([
            'message' => 'Commande supprimée'
        ]);
    }
}