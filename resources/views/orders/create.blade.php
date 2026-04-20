@extends('layouts.app')

@section('title', 'Create Order')
@section('page-title', 'Orders')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Create New Order</h2>
        <p class="page-subheading">Select rice items and quantities for the order</p>
    </div>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Back</a>
</div>

@if($riceItems->isEmpty())
    <div class="alert alert-warning">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        No rice items with available stock. <a href="{{ route('rice-items.create') }}" style="color:var(--warning);text-decoration:underline">Add rice items first.</a>
    </div>
@else
<form method="POST" action="{{ route('orders.store') }}" id="orderForm">
    @csrf

    <div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start">
        {{-- Left: items --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Order Items</span>
                <button type="button" class="add-item-btn" onclick="addItemRow()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Item
                </button>
            </div>
            <div class="card-body">
                <div id="itemsContainer">
                    {{-- Initial row --}}
                    <div class="item-row" id="item-row-0">
                        <div class="form-group" style="margin:0">
                            <label>Rice Item</label>
                            <select name="items[0][rice_item_id]" class="form-control rice-select" required onchange="updatePrice(0)">
                                <option value="">Select rice...</option>
                                @foreach($riceItems as $rice)
                                    <option value="{{ $rice->id }}"
                                            data-price="{{ $rice->price_per_kg }}"
                                            data-stock="{{ $rice->stock_quantity }}">
                                        {{ $rice->name }} (₱{{ number_format($rice->price_per_kg, 2) }}/kg)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="margin:0">
                            <label>Quantity (kg)</label>
                            <input type="number" name="items[0][quantity_kg]" class="form-control qty-input"
                                   step="0.1" min="0.1" placeholder="0.0" required oninput="updateSubtotal(0)">
                        </div>
                        <div class="form-group" style="margin:0">
                            <label>Subtotal</label>
                            <div class="subtotal-display" id="subtotal-0">₱0.00</div>
                        </div>
                        <div>
                            <label style="visibility:hidden;display:block;font-size:13px">Remove</label>
                            <button type="button" onclick="removeRow(0)" class="btn btn-danger btn-sm" style="margin-top:2px">×</button>
                        </div>
                    </div>
                </div>

                @error('items')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Right: summary + notes --}}
        <div style="display:flex;flex-direction:column;gap:16px">
            <div class="card">
                <div class="card-header"><span class="card-title">Order Summary</span></div>
                <div class="card-body">
                    <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border)">
                        <span style="color:var(--text-muted);font-size:13px">Total Amount</span>
                        <span style="font-size:20px;font-weight:700;color:var(--primary)" id="grandTotal">₱0.00</span>
                    </div>
                    <div class="form-group" style="margin-top:16px;margin-bottom:0">
                        <label for="notes">Notes (optional)</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3"
                                  placeholder="Delivery instructions, customer info...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:11px">
                Place Order
            </button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary" style="width:100%;justify-content:center">Cancel</a>
        </div>
    </div>
</form>
@endif
@endsection

@push('scripts')
<script>
const riceItems = {!! $riceItems->toJson() !!};
let rowCount = 1;

function addItemRow() {
    const idx = rowCount++;
    const options = riceItems.map(r =>
        `<option value="${r.id}" data-price="${r.price_per_kg}" data-stock="${r.stock_quantity}">
            ${r.name} (₱${parseFloat(r.price_per_kg).toFixed(2)}/kg)
         </option>`
    ).join('');

    const row = `
    <div class="item-row" id="item-row-${idx}">
        <div class="form-group" style="margin:0">
            <label>Rice Item</label>
            <select name="items[${idx}][rice_item_id]" class="form-control rice-select" required onchange="updatePrice(${idx})">
                <option value="">Select rice...</option>
                ${options}
            </select>
        </div>
        <div class="form-group" style="margin:0">
            <label>Quantity (kg)</label>
            <input type="number" name="items[${idx}][quantity_kg]" class="form-control qty-input"
                   step="0.1" min="0.1" placeholder="0.0" required oninput="updateSubtotal(${idx})">
        </div>
        <div class="form-group" style="margin:0">
            <label>Subtotal</label>
            <div class="subtotal-display" id="subtotal-${idx}">₱0.00</div>
        </div>
        <div>
            <label style="visibility:hidden;display:block;font-size:13px">Remove</label>
            <button type="button" onclick="removeRow(${idx})" class="btn btn-danger btn-sm" style="margin-top:2px">×</button>
        </div>
    </div>`;
    document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', row);
}

function updatePrice(idx) { updateSubtotal(idx); }

function updateSubtotal(idx) {
    const row = document.getElementById(`item-row-${idx}`);
    if (!row) return;
    const select = row.querySelector('.rice-select');
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    const price = parseFloat(select.options[select.selectedIndex]?.dataset.price) || 0;
    const subtotal = price * qty;
    const el = document.getElementById(`subtotal-${idx}`);
    if (el) el.textContent = '₱' + subtotal.toFixed(2);
    recalcTotal();
}

function recalcTotal() {
    let total = 0;
    document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
        total += parseFloat(el.textContent.replace('₱','')) || 0;
    });
    document.getElementById('grandTotal').textContent = '₱' + total.toFixed(2);
}

function removeRow(idx) {
    const row = document.getElementById(`item-row-${idx}`);
    if (row && document.querySelectorAll('.item-row').length > 1) {
        row.remove();
        recalcTotal();
    }
}
</script>
@endpush
