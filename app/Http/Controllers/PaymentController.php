<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['order.orderItems.riceItem'])
            ->latest()
            ->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $orders = Order::whereDoesntHave('payment', function ($q) {
            $q->where('status', 'paid');
        })->orWhereHas('payment', function ($q) {
            $q->where('status', 'unpaid');
        })->with('payment')->latest()->get();

        return view('payments.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'       => 'required|exists:orders,id',
            'amount_paid'    => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:100',
            'notes'          => 'nullable|string',
        ]);

        $order = Order::findOrFail($request->order_id);

        $payment = Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'amount_paid'    => $request->amount_paid,
                'payment_method' => $request->payment_method,
                'notes'          => $request->notes,
                'status'         => $request->amount_paid >= $order->total_amount ? 'paid' : 'partial',
                'paid_at'        => now(),
            ]
        );

        if ($payment->status === 'paid') {
            $order->update(['status' => 'completed']);
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['order.orderItems.riceItem']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $payment->load('order');
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'amount_paid'    => 'required|numeric|min:0',
            'status'         => 'required|in:paid,unpaid,partial',
            'payment_method' => 'required|string|max:100',
            'notes'          => 'nullable|string',
        ]);

        $payment->update([
            'amount_paid'    => $request->amount_paid,
            'status'         => $request->status,
            'payment_method' => $request->payment_method,
            'notes'          => $request->notes,
            'paid_at'        => $request->status !== 'unpaid' ? now() : null,
        ]);

        if ($request->status === 'paid') {
            $payment->order->update(['status' => 'completed']);
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')
            ->with('success', 'Payment record deleted.');
    }
}
