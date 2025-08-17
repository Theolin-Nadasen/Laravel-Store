<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {

        if (!auth()->user()->admin) {
            return redirect(route('landing'));
        }


        $incompleteOrders = Order::where('is_complete', false)->latest()->paginate(1, ['*'], 'pending_page');
        $completeOrders = Order::where('is_complete', true)->latest()->paginate(15, ['*'], 'completed_page');


        return view('admin.orders.index', [
            'incompleteOrders' => $incompleteOrders,
            'completeOrders' => $completeOrders
        ]);
    }

    public function markAsComplete(Order $order)
    {

        if (!auth()->user()->admin) {
            return redirect(route('landing'));
        }


        $order->is_complete = true;
        $order->save();


        return redirect()->route('admin.orders.index')->with('success', 'Order marked as complete!');
    }
}
