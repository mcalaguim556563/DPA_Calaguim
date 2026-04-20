@extends('layouts.app')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Order Records</h2>
        <p class="page-subheading">Track and manage customer orders</p>
    </div>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Order
    </a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Order #</th>
                <th>Date</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                @php
                    $statusBadge = ['pending'=>'badge-amber','processing'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-red'];
                    $payBadge = ['paid'=>'badge-green','unpaid'=>'badge-red','partial'=>'badge-amber'];
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('orders.show', $order) }}"
                           style="color:var(--primary);text-decoration:none;font-weight:600;font-size:13px">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td class="text-muted" style="font-size:12px">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="text-muted">{{ $order->orderItems->count() }} item(s)</td>
                    <td style="font-weight:600">₱{{ number_format($order->total_amount, 2) }}</td>
                    <td><span class="badge {{ $statusBadge[$order->status] ?? 'badge-gray' }}">{{ $order->status }}</span></td>
                    <td>
                        @if($order->payment)
                            <span class="badge {{ $payBadge[$order->payment->status] ?? 'badge-gray' }}">{{ $order->payment->status }}</span>
                        @else
                            <span class="badge badge-gray">none</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary btn-sm">View</a>
                            <form method="POST" action="{{ route('orders.destroy', $order) }}"
                                  onsubmit="return confirm('Delete this order and restore stock?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p>No orders yet. <a href="{{ route('orders.create') }}" style="color:var(--primary)">Create your first order</a></p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--border)">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
