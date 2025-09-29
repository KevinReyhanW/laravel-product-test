@extends('layouts.app')

@section('content')
<h2>Add Product</h2>
<form method="POST" action="{{ route('products.store') }}">
    @csrf
    @include('products.form')
    <button class="btn btn-primary">Save</button>
</form>
@endsection
