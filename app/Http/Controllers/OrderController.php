<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 🔹 GET commandes d'une boutique
    public function index($shop_id)
    {
        return Order::where('shop_id', $shop_id)->with('items')->get();
    }

    // 🔹 CREATE commande
    public function store(Request $request, $shop_id)
    {
        $data = $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $total = 0;

        $order = Order::create([
            'shop_id' => $shop_id,
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'total' => 0,
            'status' => 'pending'
        ]);

        foreach ($data['items'] as $item) {

            $product = Product::where('shop_id', $shop_id)
                        ->findOrFail($item['product_id']);

            // 🔥 vérifier stock
            if ($product->stock < $item['quantity']) {
                abort(400, "Stock insuffisant pour {$product->name}");
            }

            $subtotal = $product->price * $item['quantity'];
            $total += $subtotal;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price
            ]);

            // 🔥 réduire stock
            $product->decrement('stock', $item['quantity']);
        }

        $order->update(['total' => $total]);

        return $order->load('items');
    }

    // 🔹 GET 1 commande
    public function show($shop_id, $id)
    {
        return Order::where('shop_id', $shop_id)
                    ->with('items')
                    ->findOrFail($id);
    }

    // 🔹 UPDATE STATUS
    public function updateStatus(Request $request, $shop_id, $id)
    {
        $order = Order::where('shop_id', $shop_id)->findOrFail($id);

        $data = $request->validate([
            'status' => 'required|in:pending,completed,canceled'
        ]);

        $order->update($data);

        return $order;
    }

    // 🔹 DELETE
    public function destroy($shop_id, $id)
    {
        $order = Order::where('shop_id', $shop_id)->findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Commande supprimée']);
    }
}