@extends('layouts.app')

@section('title', 'Add Rice Item')
@section('page-title', 'Rice Menu')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Add Rice Item</h2>
        <p class="page-subheading">Fill in the details for a new rice product</p>
    </div>
    <a href="{{ route('rice-items.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card" style="max-width:600px">
    <div class="card-header"><span class="card-title">Product Details</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('rice-items.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">Rice Name <span style="color:var(--danger)">*</span></label>
                <input type="text" id="name" name="name" class="form-control"
                       value="{{ old('name') }}" placeholder="e.g. Jasmine, Brown, Dinorado" required>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price_per_kg">Price per kg (₱) <span style="color:var(--danger)">*</span></label>
                    <input type="number" id="price_per_kg" name="price_per_kg" class="form-control"
                           value="{{ old('price_per_kg') }}" step="0.01" min="0" placeholder="0.00" required>
                    @error('price_per_kg')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="stock_quantity">Stock Quantity (kg) <span style="color:var(--danger)">*</span></label>
                    <input type="number" id="stock_quantity" name="stock_quantity" class="form-control"
                           value="{{ old('stock_quantity', 0) }}" step="0.01" min="0" placeholder="0.00" required>
                    @error('stock_quantity')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control"
                          placeholder="Optional notes about this rice variety...">{{ old('description') }}</textarea>
                @error('description')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:4px">
                <a href="{{ route('rice-items.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Rice Item</button>
            </div>
        </form>
    </div>
</div>
@endsection
