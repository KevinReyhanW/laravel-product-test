@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Products</h1>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Actions -->
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
        <form action="{{ route('products.sync') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary">Sync Products</button>
        </form>
    </div>

    <!-- Products Table -->
    <table class="table table-bordered table-striped table-hover table-sm align-middle" style="table-layout: fixed; width: 100%;">
        <thead class="table-light">
            <tr>
                <th style="width: 20%;">Name</th>
                <th style="width: 15%;">Price</th>
                <th style="width: 10%;">Stock</th>
                <th style="width: 40%;">Description</th>
                <th style="width: 15%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td style="word-wrap: break-word; white-space: normal;">{{ $product->name }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td style="word-wrap: break-word; white-space: normal;">{{ $product->description }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
