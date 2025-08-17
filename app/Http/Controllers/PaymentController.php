<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private function _escapeString($str)
    {
        $escaped = preg_replace('/[\\"\'\"]/u', '\\\\$0', (string) $str);
        $escaped = preg_replace('/\x00/', '\\0', $escaped);
        $cleaned = str_replace('\\/', '/', $escaped);
        return $cleaned;
    }

    public function initiatePayment(Request $request)
    {

        $products = [];


        foreach (json_decode(auth()->user()->cart) as $id) {
            $products[] = Product::find($id);
        }
        ;

        // totaling up the pices
        $total = 0.00;

        foreach ($products as $item) {
            $total = $total + $item->price;
        }

        $amountInCents = (int) ($total * 100);

        $yourTransactionId = 'ORD-' . strtoupper(Str::random(10));

        $requestData = [
            'entityID' => config('ikhokha.app_id'),
            'amount' => $amountInCents,
            'currency' => 'ZAR',
            'requesterUrl' => url('/'), // The base URL of your site
            'description' => 'Payment for Order ' . $yourTransactionId,
            'mode' => 'test', // Use 'test' if they provide a test mode
            'externalTransactionID' => $yourTransactionId,
            'urls' => [
                // We will create routes/pages for these in the next steps!
                'callbackUrl' => route('payment.callback'),
                'successPageUrl' => route('payment.success'),
                'failurePageUrl' => route('payment.failure'),
                'cancelUrl' => route('payment.cancel'),
            ]
        ];


        // --- 3. CREATE THE SECURITY SIGNATURE ---
        $apiEndpoint = config('ikhokha.api_endpoint');
        $appSecret = config('ikhokha.app_secret');

        $requestBody = json_encode($requestData, JSON_UNESCAPED_SLASHES);
        $urlPath = parse_url($apiEndpoint, PHP_URL_PATH);

        $rawPayload = $urlPath . $requestBody;
        $payloadToSign = $this->_escapeString($rawPayload);

        $signature = hash_hmac('sha256', $payloadToSign, $appSecret);


        // --- 4. SEND THE REQUEST TO IKHOKHA ---
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'IK-APPID' => config('ikhokha.app_id'),
            'IK-SIGN' => $signature,
        ])->post($apiEndpoint, $requestData);


        // --- 5. HANDLE THE RESPONSE ---
        if ($response->successful()) {
            $paymentUrl = $response->json('paylinkUrl');

            $transactionIdForThisOrder = $requestData['externalTransactionID'];

            session()->put('latest_transaction_id', $transactionIdForThisOrder);

            // TODO: Before redirecting, you should save the $yourTransactionId
            // and associate it with the user's cart/order in your database,
            // marking its status as 'pending'.

            // Redirect the user to the secure payment page.
            return redirect()->away($paymentUrl);

        } else {
            // If the request fails, redirect back to the cart with an error.
            Log::error('iKhokha payment initiation failed:', $response->json());
            return redirect()->back()->with('error', 'Could not initiate payment. Please try again.');
        }

    }


    public function paymentSuccess(Request $request)
    {
        $transactionId = session('latest_transaction_id');

        // Here you can retrieve transaction details from the request if iKhokha sends them
        // and flash them to the session for display on the success page.
        // For example, if they send 'externalTransactionID' back in the query string:
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
        $externalTransactionID = $request->input('externalTransactionID');
        $transactionStatus = $request->input('status'); // Adjust 'status' if the key name is different

        if (!$externalTransactionID) {
            Log::error('iKhokha Callback: Missing externalTransactionID.');
            // Respond with an error code to signal a problem.
            return response()->json(['status' => 'error', 'message' => 'Missing transaction ID'], 400);
        }

        // 3. Find the order in your database.
        // IMPORTANT: You need an 'orders' table with 'status' and 'ikhokha_transaction_id' columns.
        // $order = Order::where('ikhokha_transaction_id', $externalTransactionID)->first();

        // --- THIS IS PSEUDO-CODE --- Replace with your actual database logic.
        Log::info("Searching for Order with ID: {$externalTransactionID}");
        // --- END PSEUDO-CODE ---

        /*
        if (!$order) {
            Log::error("iKhokha Callback: Order with ID {$externalTransactionID} not found.");
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }
        */

        // 4. Update the order based on the status.
        // The exact status strings ('SUCCESS', 'FAILURE', etc.) will depend on iKhokha's documentation.
        if (strtoupper($transactionStatus) === 'SUCCESS') {

            // --- THIS IS PSEUDO-CODE ---
            Log::info("Order {$externalTransactionID} was successful. Updating status to 'Paid'.");
            // $order->status = 'paid';
            // $order->save();

            // Trigger other actions: send confirmation email, notify admin, start shipping process, etc.
            // dispatch(new SendOrderConfirmationEmail($order));
            // --- END PSEUDO-CODE ---

        } else {
            // --- THIS IS PSEUDO-CODE ---
            Log::warning("Order {$externalTransactionID} was not successful. Status: {$transactionStatus}. Updating status to 'Failed'.");
            // $order->status = 'failed';
            // $order->save();
            // --- END PSEUDO-CODE ---
        }

        // 5. Respond with a 200 OK to acknowledge receipt.
        // This is crucial. If iKhokha doesn't get a 200 OK, it might try to send the callback again.
        return response()->json(['status' => 'success']);
    }
}
