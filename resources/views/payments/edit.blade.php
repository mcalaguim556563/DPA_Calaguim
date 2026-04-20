@extends('layouts.app')

@section('title', 'Edit Payment')
@section('page-title', 'Payments')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Edit Payment</h2>
        <p class="page-subheading">{{ $payment->order->order_number }}</p>
    </div>
    <a href="{{ route('payments.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div style="display:grid;grid-template-columns:1.2fr 1fr;gap:20px;align-items:start;max-width:900px">
    <div class="card">
        <div class="card-header"><span class="card-title">Update Payment</span></div>
        <div class="card-body">
            <form method="POST" action="{{ route('payments.update', $payment) }}">
                @csrf @method('PUT')

                <div style="background:var(--bg);border:1px solid var(--border);border-radius:var(--radius);padding:14px;margin-bottom:20px">
                    <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px">Order Total for {{ $payment->order->order_number }}</div>
                    <div style="font-size:20px;font-weight:700;color:var(--primary)">₱{{ number_format($payment->order->total_amount, 2) }}</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="amount_paid">Amount Paid (₱) <span style="color:var(--danger)">*</span></label>
                        <input type="number" id="amount_paid" name="amount_paid" class="form-control"
                               value="{{ old('amount_paid', $payment->amount_paid) }}" step="0.01" min="0" required>
                        @error('amount_paid')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Payment Status <span style="color:var(--danger)">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                            @foreach(['paid','unpaid','partial'] as $s)
                                <option value="{{ $s }}" {{ old('status', $payment->status) === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select id="payment_method" name="payment_method" class="form-control">
                        @foreach(['cash','gcash','paymaya','bank transfer','check'] as $method)
                            <option value="{{ $method }}" {{ old('payment_method', $payment->payment_method) === $method ? 'selected' : '' }}>
                                {{ ucwords($method) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" class="form-control" rows="2">{{ old('notes', $payment->notes) }}</textarea>
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end">
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Payment</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Order Items</span></div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr><th>Item</th><th>Qty</th><th class="text-right">Subtotal</th></tr>
                </thead>
                <tbody>
                @foreach($payment->order->orderItems as $item)
                    <tr>
                        <td style="font-weight:500">{{ $item->riceItem->name }}</td>
                        <td class="text-muted">{{ $item->quantity_kg }} kg</td>
                        <td class="text-right">₱{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2" style="text-align:right;padding:12px 16px;font-weight:600;color:var(--text-muted)">Total</td>
                    <td class="text-right" style="padding:12px 16px;font-weight:700;color:var(--primary)">₱{{ number_format($payment->order->total_amount, 2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
