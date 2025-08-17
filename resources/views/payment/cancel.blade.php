@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-info">Payment Cancelled</h2>
                    <p>Your payment process was cancelled. Your order has not been placed.</p>
                    <p>Your items are still in your cart if you'd like to try again.</p>
                    <a href="/cart" class="btn btn-secondary mt-3">Return to Cart</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection