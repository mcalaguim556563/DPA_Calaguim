@extends('layouts.app')

@section('title', 'Payments')
@section('page-title', 'Payments')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Payment History</h2>
        <p class="page-subheading">View and manage all payment transactions</p>
    </div>
    <a href="{{ route('payments.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Record Payment
    </a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Order #</th>
                <th>Order Total</th>
                <th>Amount Paid</th>
                <th>Method</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($payments as $payment)
                @php $payBadge = ['paid'=>'badge-green','unpaid'=>'badge-red','partial'=>'badge-amber']; @endphp
                <tr>
                    <td>
                        <a href="{{ route('orders.show', $payment->order) }}"
                           style="color:var(--primary);text-decoration:none;font-weight:600;font-size:13px">
                            {{ $payment->order->order_number }}
                        </a>
                    </td>
                    <td>₱{{ number_format($payment->order->total_amount, 2) }}</td>
                    <td style="font-weight:600">₱{{ number_format($payment->amount_paid, 2) }}</td>
                    <td class="text-muted" style="text-transform:capitalize">{{ $payment->payment_method }}</td>
                    <td><span class="badge {{ $payBadge[$payment->status] ?? 'badge-gray' }}">{{ $payment->status }}</span></td>
                    <td class="text-muted" style="font-size:12px">
                        {{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : '—' }}
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('payments.destroy', $payment) }}"
                                  onsubmit="return confirm('Delete this payment record?')">
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            <p>No payment records yet.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--border)">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection
