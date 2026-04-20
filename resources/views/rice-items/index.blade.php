@extends('layouts.app')

@section('title', 'Rice Menu')
@section('page-title', 'Rice Menu')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Rice Products</h2>
        <p class="page-subheading">Manage your rice inventory and pricing</p>
    </div>
    <a href="{{ route('rice-items.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Rice Item
    </a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Rice Name</th>
                <th>Price / kg</th>
                <th>Stock (kg)</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($riceItems as $item)
                <tr>
                    <td class="text-muted" style="font-size:12px">{{ $loop->iteration + ($riceItems->firstItem() - 1) }}</td>
                    <td style="font-weight:500">{{ $item->name }}</td>
                    <td>₱{{ number_format($item->price_per_kg, 2) }}</td>
                    <td>
                        @if($item->stock_quantity <= 10)
                            <span class="badge badge-amber">{{ $item->stock_quantity }} kg</span>
                        @else
                            <span style="color:var(--text)">{{ $item->stock_quantity }} kg</span>
                        @endif
                    </td>
                    <td class="text-muted" style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $item->description ?? '—' }}
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('rice-items.edit', $item) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('rice-items.destroy', $item) }}"
                                  onsubmit="return confirm('Delete {{ $item->name }}? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                            <p>No rice items added yet. <a href="{{ route('rice-items.create') }}" style="color:var(--primary)">Add your first item</a></p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($riceItems->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--border)">
            {{ $riceItems->links() }}
        </div>
    @endif
</div>
@endsection
