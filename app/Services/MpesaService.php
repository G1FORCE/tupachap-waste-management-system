<?php

namespace App\Services;

use App\Models\WasteRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

/**
 * MpesaService
 * ------------
 * Objective 4: cashless payment via M-Pesa's STK Push (the pop-up prompt
 * a user gets on their phone asking them to enter their M-Pesa PIN).
 *
 * Uses Safaricom's Daraja API sandbox - free to register, no real money
 * moves, perfect for your beta testing (Objective 5). Tigo Pesa works the
 * same way conceptually; swap the base URL + credentials when you get there.
 *
 * Setup: register at https://developer.safaricom.co.ke, create a sandbox
 * app, put the keys in your .env (see .env.example).
 */
class MpesaService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.mpesa.env') === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    protected function getAccessToken(): string
    {
        return Cache::remember('mpesa_access_token', 3500, function () {
            $response = Http::withBasicAuth(
                config('services.mpesa.consumer_key'),
                config('services.mpesa.consumer_secret')
            )->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

            return $response->json('access_token');
        });
    }

    /**
     * Sends the STK push prompt to the customer's phone for a given request.
     */
    public function initiatePayment(WasteRequest $request, string $phoneNumber): array
    {
        $token = $this->getAccessToken();
        $timestamp = now()->format('YmdHis');
        $shortcode = config('services.mpesa.shortcode');
        $passkey = config('services.mpesa.passkey');
        $password = base64_encode($shortcode . $passkey . $timestamp);

        $response = Http::withToken($token)->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", [
            'BusinessShortCode' => $shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => (int) $request->price,
            'PartyA' => $phoneNumber,
            'PartyB' => $shortcode,
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => config('services.mpesa.callback_url'),
            'AccountReference' => 'TupaChap-' . $request->id,
            'TransactionDesc' => 'Waste collection payment',
        ]);

        return $response->json();
    }

    /**
     * Called by the route that Safaricom hits (CallBackURL) once the
     * customer confirms or cancels the payment on their phone.
     */
    public function handleCallback(array $payload): void
    {
        $stkCallback = $payload['Body']['stkCallback'] ?? [];
        $resultCode = $stkCallback['ResultCode'] ?? 1;

        // AccountReference was "TupaChap-{id}" - pull the id back out
        $items = collect($stkCallback['CallbackMetadata']['Item'] ?? []);
        $receipt = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;

        if ($resultCode === 0 && $receipt) {
            // Match this to the correct request using data you stashed
            // when initiating payment (e.g. session, or store request_id
            // alongside the checkout request ID returned by initiatePayment).
            WasteRequest::where('payment_reference', null)
                ->latest()
                ->first()
                ?->update(['status' => 'paid', 'payment_reference' => $receipt]);
        }
    }
}
