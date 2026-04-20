@extends('layouts.app')

@section('title', 'Edit Order')
@section('page-title', 'Orders')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Edit Order Status</h2>
        <p class="page-subheading">{{ $order->order_number }}</p>
    </div>
    <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card" style="max-width:500px">
    <div class="card-header"><span class="card-title">Update Order</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('orders.update', $order) }}">
            @csrf @method('PUT')

            <div style="background:var(--bg);border:1px solid var(--border);border-radius:var(--radius);padding:14px;margin-bottom:20px">
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px">Order Total</div>
                <div style="font-size:20px;font-weight:700;color:var(--primary)">₱{{ number_format($order->total_amount,2) }}</div>
            </div>

            <div class="form-group">
                <label for="status">Order Status</label>
                <select id="status" name="status" class="form-control" required>
                    @foreach(['pending','processing','completed','cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', $order->status) === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
                @error('status')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" class="form-control">{{ old('notes', $order->notes) }}</textarea>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end">
                <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Order</button>
            </div>
        </form>
    </div>
</div>
@endsection
