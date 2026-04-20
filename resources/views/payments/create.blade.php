@extends('layouts.app')

@section('title', 'Record Payment')
@section('page-title', 'Payments')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Record Payment</h2>
        <p class="page-subheading">Process payment for an existing order</p>
    </div>
    <a href="{{ route('payments.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div style="display:grid;grid-template-columns:1.2fr 1fr;gap:20px;align-items:start;max-width:900px">
    <div class="card">
        <div class="card-header"><span class="card-title">Payment Details</span></div>
        <div class="card-body">
            <form method="POST" action="{{ route('payments.store') }}" id="paymentForm">
                @csrf

                <div class="form-group">
                    <label for="order_id">Select Order <span style="color:var(--danger)">*</span></label>
                    <select id="order_id" name="order_id" class="form-control" required onchange="updateOrderInfo(this)">
                        <option value="">Choose an order...</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}"
                                    data-total="{{ $order->total_amount }}"
                                    data-paid="{{ $order->payment?->amount_paid ?? 0 }}"
                                    data-number="{{ $order->order_number }}"
                                    {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                {{ $order->order_number }} — ₱{{ number_format($order->total_amount, 2) }}
                                @if($order->payment && $order->payment->status === 'partial')
                                    (partial)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="amount_paid">Amount Paid (₱) <span style="color:var(--danger)">*</span></label>
                        <input type="number" id="amount_paid" name="amount_paid" class="form-control"
                               value="{{ old('amount_paid') }}" step="0.01" min="0.01" placeholder="0.00" required
                               oninput="updateChange()">
                        @error('amount_paid')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Payment Method <span style="color:var(--danger)">*</span></label>
                        <select id="payment_method" name="payment_method" class="form-control" required>
                            @foreach(['cash','gcash','paymaya','bank transfer','check'] as $method)
                                <option value="{{ $method }}" {{ old('payment_method') === $method ? 'selected' : '' }}>
                                    {{ ucwords($method) }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Notes (optional)</label>
                    <textarea id="notes" name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end">
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Process Payment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Order summary panel --}}
    <div class="card" id="orderSummaryCard" style="display:none">
        <div class="card-header"><span class="card-title">Order Summary</span></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
            <div>
                <label style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted)">Order Number</label>
                <p id="s-number" style="font-size:14px;font-weight:600;margin-top:3px"></p>
            </div>
            <div>
                <label style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted)">Order Total</label>
                <p id="s-total" style="font-size:20px;font-weight:700;color:var(--primary);margin-top:3px"></p>
            </div>
            <div>
                <label style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted)">Previously Paid</label>
                <p id="s-paid" style="font-size:14px;font-weight:500;margin-top:3px"></p>
            </div>
            <div style="border-top:1px solid var(--border);padding-top:14px">
                <label style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted)">Balance / Change</label>
                <p id="s-balance" style="font-size:16px;font-weight:700;margin-top:3px"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateOrderInfo(sel) {
    const opt = sel.options[sel.selectedIndex];
    const card = document.getElementById('orderSummaryCard');
    if (!opt.value) { card.style.display = 'none'; return; }
    card.style.display = 'block';
    document.getElementById('s-number').textContent = opt.dataset.number;
    document.getElementById('s-total').textContent = '₱' + parseFloat(opt.dataset.total).toFixed(2);
    document.getElementById('s-paid').textContent = '₱' + parseFloat(opt.dataset.paid).toFixed(2);
    updateChange();
}

function updateChange() {
    const sel = document.getElementById('order_id');
    const opt = sel.options[sel.selectedIndex];
    if (!opt.value) return;
    const total = parseFloat(opt.dataset.total) || 0;
    const prev = parseFloat(opt.dataset.paid) || 0;
    const input = parseFloat(document.getElementById('amount_paid').value) || 0;
    const remaining = total - prev - input;
    const el = document.getElementById('s-balance');
    if (remaining > 0) {
        el.textContent = '₱' + remaining.toFixed(2) + ' still owed';
        el.style.color = 'var(--danger)';
    } else if (remaining < 0) {
        el.textContent = '₱' + Math.abs(remaining).toFixed(2) + ' change';
        el.style.color = 'var(--primary)';
    } else {
        el.textContent = 'Exactly paid ✓';
        el.style.color = 'var(--primary)';
    }
}

// Trigger on load if there's an old value
window.addEventListener('load', () => {
    const sel = document.getElementById('order_id');
    if (sel.value) updateOrderInfo(sel);
});
</script>
@endpush
