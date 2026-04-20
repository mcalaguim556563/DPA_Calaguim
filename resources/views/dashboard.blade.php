@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        <div class="stat-info">
            <label>Rice Products</label>
            <div class="stat-value">{{ $totalRiceItems }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div class="stat-info">
            <label>Total Orders</label>
            <div class="stat-value">{{ $totalOrders }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div class="stat-info">
            <label>Total Revenue</label>
            <div class="stat-value">₱{{ number_format($totalRevenue, 2) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-info">
            <label>Pending Orders</label>
            <div class="stat-value">{{ $pendingOrders }}</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1.6fr 1fr;gap:20px">
    {{-- Recent Orders --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Recent Orders</span>
            <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">+ New Order</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Order #</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
                </thead>
                <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', $order) }}"
                               style="color:var(--primary);text-decoration:none;font-weight:500">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="text-muted">{{ $order->orderItems->count() }} item(s)</td>
                        <td class="font-bold">₱{{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            @php
                                $badgeMap = ['pending'=>'badge-amber','processing'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-red'];
                            @endphp
                            <span class="badge {{ $badgeMap[$order->status] ?? 'badge-gray' }}">{{ $order->status }}</span>
                        </td>
                        <td>
                            @if($order->payment)
                                @php $pm = ['paid'=>'badge-green','unpaid'=>'badge-red','partial'=>'badge-amber']; @endphp
                                <span class="badge {{ $pm[$order->payment->status] ?? 'badge-gray' }}">{{ $order->payment->status }}</span>
                            @else
                                <span class="badge badge-gray">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted" style="padding:28px">No orders yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Low stock --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Low Stock Alert</span>
            <a href="{{ route('rice-items.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            @forelse($lowStockItems as $item)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border)">
                    <div>
                        <div style="font-size:13.5px;font-weight:500">{{ $item->name }}</div>
                        <div style="font-size:12px;color:var(--text-muted)">₱{{ number_format($item->price_per_kg,2) }}/kg</div>
                    </div>
                    <span class="badge badge-amber">{{ $item->stock_quantity }} kg</span>
                </div>
            @empty
                <div class="empty-state" style="padding:24px">
                    <p>All items are well stocked ✓</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
