@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-success">Payment Successful!</h2>
                    <p>Thank you for your purchase. We have received your payment and a confirmation email is on its way.</p>
                    <p>Your Order ID is: <strong>{{ session('externalTransactionID', 'N/A') }}</strong></p>
                    <a href="/" class="btn btn-primary mt-3">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection