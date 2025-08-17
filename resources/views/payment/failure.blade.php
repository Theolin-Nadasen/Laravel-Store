@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-danger">Payment Failed</h2>
                    <p>Unfortunately, your payment could not be processed. No funds have been debited from your account.</p>
                    <p>Please check your details and try again, or use a different payment method.</p>
                    <a href="/cart" class="btn btn-warning mt-3">Return to Cart</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection