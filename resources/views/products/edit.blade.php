@extends('layouts.app')

@section('content')
<h2>Edit Product</h2>
<form method="POST" action="{{ route('products.update', $product) }}">
    @csrf @method('PUT')
    @include('products.form')
    <button class="btn btn-primary">Update</button>
</form>
@endsection
