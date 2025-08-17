@extends('layouts.main')

@section('content')
    <div class="container">
        <h1 class="mb-4">Manage Orders</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Section for Incomplete/Pending Orders --}}
        <div class="card mb-5">
            <div class="card-header">
                <h2>Pending Orders</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Contact</th>
                                <th>Delivery Info</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Placed At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($incompleteOrders as $order)
                                <tr>
                                    <td>{{ $order->order_id }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->contact_phone }}</td>
                                    <td>
                                        @if($order->delivery_address)
                                            {{ $order->delivery_address }}
                                        @else
                                            <strong>Collection</strong>
                                        @endif
                                    </td>
                                    <td>{{ implode(', ', $order->items) }}</td>
                                    <td>R{{ number_format($order->total_amount, 2) }}</td>
                                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.orders.complete', $order) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to mark this order as complete?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success">Mark as Complete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No pending orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $incompleteOrders->links() }}
                </div>
            </div>
        </div>


        {{-- Section for Complete Orders --}}
        <div class="card">
            <div class="card-header">
                <h2>Completed Orders</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Delivery Info</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Completed At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($completeOrders as $order)
                                <tr>
                                    <td>{{ $order->order_id }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>
                                        @if($order->delivery_address)
                                            {{ $order->delivery_address }}
                                        @else
                                            <strong>Collection</strong>
                                        @endif
                                    </td>
                                    <td>{{ implode(', ', $order->items) }}</td>
                                    <td>R{{ number_format($order->total_amount, 2) }}</td>
                                    <td>{{ $order->updated_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No completed orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $completeOrders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection