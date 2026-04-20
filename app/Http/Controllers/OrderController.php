<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\RiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems.riceItem', 'payment'])
            ->latest()
            ->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $riceItems = RiceItem::where('stock_quantity', '>', 0)->get();
        return view('orders.create', compact('riceItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'                    => 'required|array|min:1',
            'items.*.rice_item_id'     => 'required|exists:rice_items,id',
            'items.*.quantity_kg'      => 'required|numeric|min:0.1',
            'notes'                    => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $totalAmount = 0;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id'      => auth()->id(),
                'total_amount' => 0,
                'status'       => 'pending',
                'notes'        => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $riceItem = RiceItem::findOrFail($item['rice_item_id']);
                $quantityKg = $item['quantity_kg'];
                $subtotal = $riceItem->price_per_kg * $quantityKg;
                $totalAmount += $subtotal;

                OrderItem::create([
                    'order_id'     => $order->id,
                    'rice_item_id' => $riceItem->id,
                    'quantity_kg'  => $quantityKg,
                    'price_per_kg' => $riceItem->price_per_kg,
                    'subtotal'     => $subtotal,
                ]);

                // Deduct stock
                $riceItem->decrement('stock_quantity', $quantityKg);
            }

            $order->update(['total_amount' => $totalAmount]);

            // Create a pending payment record
            Payment::create([
                'order_id'       => $order->id,
                'amount_paid'    => 0,
                'status'         => 'unpaid',
                'payment_method' => 'cash',
            ]);
        });

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load(['orderItems.riceItem', 'payment', 'user']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $riceItems = RiceItem::all();
        $order->load('orderItems.riceItem');
        return view('orders.edit', compact('order', 'riceItems'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'notes'  => 'nullable|string',
        ]);

        $order->update([
            'status' => $request->status,
            'notes'  => $request->notes,
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        // Restore stock
        foreach ($order->orderItems as $item) {
            $item->riceItem->increment('stock_quantity', $item->quantity_kg);
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
