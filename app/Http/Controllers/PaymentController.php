<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;

class PaymentController extends Controller
{
    const DELIVERY_FEE = 100.00;

    private function _escapeString($str)
    {
        $escaped = preg_replace('/[\\"\'\"]/u', '\\\\$0', (string) $str);
        $escaped = preg_replace('/\x00/', '\\0', $escaped);
        $cleaned = str_replace('\\/', '/', $escaped);
        return $cleaned;
    }

    public function initiatePayment(Order $order)
    {
        $amountInCents = (int) ($order->total_amount * 100);

        $requestData = [
            'entityID' => config('ikhokha.app_id'),
            'externalEntityID' => config('ikhokha.app_id'), // Added for consistency with examples
            'amount' => $amountInCents,
            'currency' => 'ZAR',
            'requesterUrl' => url('/'),
            'description' => 'Payment for Order ' . $order->order_id,
            'paymentReference' => $order->order_id, // Added for consistency with examples
            'mode' => 'test', // Or 'live'
            'externalTransactionID' => $order->order_id, // We use our saved order_id
            'urls' => [
                'callbackUrl' => route('payment.callback'),
                'successPageUrl' => route('payment.success'),
                'failurePageUrl' => route('payment.failure'),
                'cancelUrl' => route('payment.cancel'),
            ]
        ];

        // --- SIGNATURE AND API CALL LOGIC (This remains the same) ---
        $apiEndpoint = config('ikhokha.api_endpoint');
        $appSecret = config('ikhokha.app_secret');
        $requestBody = json_encode($requestData, JSON_UNESCAPED_SLASHES);
        $urlPath = parse_url($apiEndpoint, PHP_URL_PATH);
        $rawPayload = $urlPath . $requestBody;
        $payloadToSign = $this->_escapeString($rawPayload);
        $signature = hash_hmac('sha256', $payloadToSign, $appSecret);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'IK-APPID' => config('ikhokha.app_id'),
            'IK-SIGN' => $signature,
        ])->post($apiEndpoint, $requestData);

        // --- HANDLE THE RESPONSE ---
        if ($response->successful() && $response->json('responseCode') === '00') {
            $paymentUrl = $response->json('paylinkUrl');

            // Store the ID in the session to show on the success page
            session()->put('latest_transaction_id', $order->order_id);

            return redirect()->away($paymentUrl);
        } else {
            Log::error('iKhokha payment initiation failed:', ['response' => $response->json(), 'order_id' => $order->id]);
            return redirect()->route('checkout.show')->with('error', 'Could not initiate payment. Please try again.');
        }
    }


    public function paymentSuccess(Request $request)
    {
        $transactionId = session('latest_transaction_id');


        if ($transactionId) {
            session()->flash('externalTransactionID', $transactionId);
        }

        return view('payment.success');
    }


    public function paymentFailure()
    {
        return view('payment.failure');
    }

    public function paymentCancel()
    {
        return view('payment.cancel');
    }


    public function paymentCallback(Request $request)
    {
        // 1. Log the entire request for debugging purposes.
        // Check your logs at `storage/logs/laravel.log`.
        Log::info('iKhokha Callback Received:', $request->all());

        // 2. Get the transaction ID from the callback data.
        // iKhokha sends this as 'externalTransactionID'.
        $order_id = $request->input('externalTransactionID');

        // It's good practice to get the status as well.
        // The exact key for status might be 'status' or 'transaction_status'.
        // We'll check for both and convert to uppercase for reliable comparison.
        $statusKey = $request->has('status') ? 'status' : 'transaction_status';
        $transactionStatus = strtoupper($request->input($statusKey, ''));

        if (!$order_id) {
            Log::error('iKhokha Callback: Missing externalTransactionID.');
            // Respond with an error to signal a problem to iKhokha's server.
            return response()->json(['status' => 'error', 'message' => 'Missing transaction ID'], 400);
        }

        // 3. Find the corresponding order in your database.
        $order = Order::where('order_id', $order_id)->first();

        if (!$order) {
            Log::error("iKhokha Callback: Order with order_id {$order_id} not found.");
            // We return a 200 OK here because retrying won't help if the order doesn't exist.
            // This prevents iKhokha from repeatedly sending a callback for a non-existent order.
            return response()->json(['status' => 'success', 'message' => 'Order not found, but acknowledged.']);
        }

        // 4. Update the order based on the transaction status.
        // The exact success string from iKhokha is 'SUCCESS'.
        if ($transactionStatus === 'SUCCESS') {

            // Only update if it's currently unpaid to prevent duplicate processing
            if ($order->payment_status == false) {
                $order->payment_status = true; // true means 'paid'
                $order->save();
                Log::info("Order {$order_id} was successful. Status updated to 'Paid'.");

                // --- TRIGGER POST-PAYMENT ACTIONS HERE ---
                // e.g., Send a confirmation email to the customer.
                // e.g., Send a notification to the admin/store owner.
                // e.g., Clear the user's cart.
                // auth()->user()->cart = null;
                // auth()->user()->save();
            } else {
                Log::warning("Received a success callback for already paid order: {$order_id}. Ignoring.");
            }

        } else {
            // Optional: Handle other statuses like 'FAILED' if needed.
            Log::warning("Received a non-success callback for order {$order_id}. Status: {$transactionStatus}.");
            // You might want to update your DB to a 'failed' state here if you add one later.
        }


        return response()->json(['status' => 'success']);
    }

    public function showCheckoutForm()
    {
        // Here you would get the cart from the session to display a summary
        return view('checkout');
    }

    public function placeOrder(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:255',
            'is_delivery' => 'required|boolean',
            'delivery_address' => 'required_if:is_delivery,true|nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // --- 2. GET CART DETAILS & CALCULATE TOTAL ---
        // This is your logic from the old initiatePayment method
        $cartItemIds = json_decode(auth()->user()->cart, true) ?? [];
        if (empty($cartItemIds)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $products = Product::find($cartItemIds);

        $product_names = $products->pluck('name');

        $orderTotalDecimal = $products->sum('price');

        if ($request->input('is_delivery')) {
            $orderTotalDecimal += self::DELIVERY_FEE; // Use the constant
        }


        $order = Order::create([
            'payment_status' => false, // unpaid
            'is_complete' => false, // not fulfilled
            'order_id' => 'ORD-' . strtoupper(Str::random(10)), // Generate the ID here
            'is_delivery' => $request->input('is_delivery'),
            'customer_name' => $request->input('customer_name'),
            'contact_phone' => $request->input('contact_phone'),
            'delivery_address' => $request->input('delivery_address'),
            'items' => $product_names,
            'total_amount' => $orderTotalDecimal,
        ]);

        // --- 4. DELEGATE TO THE PAYMENT INITIATION METHOD ---
        // We pass the newly created order to it.
        return $this->initiatePayment($order);
    }
}
