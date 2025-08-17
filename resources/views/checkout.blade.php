@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Checkout</div>

                    <div class="card-body">
                        {{-- You can display a summary of the cart here if you like --}}

                        <form action="{{ route('checkout.placeOrder') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone Number</label>
                                <input type="text" class="form-control" id="contact_phone" name="contact_phone" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Delivery or Collection?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_delivery" id="delivery" value="1"
                                        checked>
                                    <label class="form-check-label" for="delivery">
                                        Delivery (R100)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_delivery" id="collection"
                                        value="0">
                                    <label class="form-check-label" for="collection">
                                        Collection
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3" id="delivery-address-group">
                                <label for="delivery_address" class="form-label">Delivery Address</label>
                                <textarea class="form-control" id="delivery_address" name="delivery_address"
                                    rows="3"></textarea>
                            </div>

                            <hr>

                            <button type="submit" class="btn btn-success w-100">Proceed to Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Optional: Simple JavaScript to hide the address field for collection --}}
    <script>
        document.querySelectorAll('input[name="is_delivery"]').forEach(radio => {
            radio.addEventListener('change', function () {
                document.getElementById('delivery-address-group').style.display = this.value === '1' ? 'block' : 'none';
            });
        });
    </script>
@endsection