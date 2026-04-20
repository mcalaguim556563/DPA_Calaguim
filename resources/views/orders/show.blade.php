@extends('layouts.app')

@section('title', 'Order Details')
@section('page-title', 'Orders')

@section('content')
@php
    $statusBadge = ['pending'=>'badge-amber','processing'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-red'];
    $payBadge    = ['paid'=>'badge-green','unpaid'=>'badge-red','partial'=>'badge-amber'];
@endphp

<div class="page-header">
    <div>
        <h2 class="page-heading">{{ $order->order_number }}</h2>
        <p class="page-subheading">Created {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('orders.edit', $order) }}" class="btn btn-secondary">Edit Status</a>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start">
    {{-- Order items --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Order Items</span>
            <span class="badge {{ $statusBadge[$order->status] ?? 'badge-gray' }}">{{ $order->status }}</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Rice Item</th>
                    <th>Price / kg</th>
                    <th>Quantity (kg)</th>
                    <th class="text-right">Subtotal</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td style="font-weight:500">{{ $item->riceItem->name }}</td>
                        <td class="text-muted">₱{{ number_format($item->price_per_kg, 2) }}</td>
                        <td>{{ $item->quantity_kg }} kg</td>
                        <td class="text-right font-bold">₱{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right;padding:14px 16px;font-weight:600;color:var(--text-muted)">Total Amount</td>
                    <td class="text-right" style="padding:14px 16px;font-size:18px;font-weight:700;color:var(--primary)">
                        ₱{{ number_format($order->total_amount, 2) }}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        @if($order->notes)
            <div style="padding:14px 20px;border-top:1px solid var(--border)">
                <p style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:4px">Notes</p>
                <p style="font-size:13.5px">{{ $order->notes }}</p>
            </div>
        @endif
    </div>

    {{-- Payment info --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Payment</span></div>
        <div class="card-body">
            @if($order->payment)
                <div style="display:flex;flex-direction:column;gap:14px">
                    <div>
                        <label style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted)">Status</label>
                        <div style="margin-top:4px">
                            <span class="badge {{ $payBadge[$order->payment->status] ?? 'badge-gray' }}" style="font-size:13px;padding:4px 14px">
                                {{ strtoupper($order->payment->status) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted)">Amount Paid</label>
                        <p style="font-size:18px;font-weight:700;color:var(--text);margin-top:3px">₱{{ number_format($order->payment->amount_paid, 2) }}</p>
                    </div>
                    <div>
                        <label style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted)">Balance</label>
                        @php $balance = $order->total_amount - $order->payment->amount_paid; @endphp
                        <p style="font-size:15px;font-weight:600;color:{{ $balance > 0 ? 'var(--danger)' : 'var(--primary)' }};margin-top:3px">
                            {{ $balance > 0 ? '₱' . number_format($balance,2) . ' remaining' : 'Fully paid ✓' }}
                        </p>
                    </div>
                    <div>
                        <label style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted)">Method</label>
                        <p style="font-size:13.5px;font-weight:500;margin-top:3px">{{ ucfirst($order->payment->payment_method) }}</p>
                    </div>
                    @if($order->payment->paid_at)
                    <div>
                        <label style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted)">Paid On</label>
                        <p style="font-size:13px;color:var(--text-muted);margin-top:3px">{{ $order->payment->paid_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                    <a href="{{ route('payments.edit', $order->payment) }}" class="btn btn-primary" style="justify-content:center">
                        Update Payment
                    </a>
                </div>
            @else
                <div class="empty-state" style="padding:20px">
                    <p>No payment record.</p>
                </div>
                <a href="{{ route('payments.create') }}" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:12px">
                    Process Payment
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
