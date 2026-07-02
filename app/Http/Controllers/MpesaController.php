<?php

namespace App\Http\Controllers;

use App\Models\WasteRequest;
use App\Services\MpesaService;
use Illuminate\Http\Request;

class MpesaController extends Controller
{
    public function __construct(protected MpesaService $mpesa) {}

    public function initiate(Request $request, WasteRequest $wasteRequest)
    {
        $validated = $request->validate([
            'phone' => 'required|string|regex:/^2547[0-9]{8}$/', // 2547XXXXXXXX format
        ]);

        $result = $this->mpesa->initiatePayment($wasteRequest, $validated['phone']);

        return back()->with('message', $result['CustomerMessage'] ?? 'Check your phone to complete payment.');
    }

    public function callback(Request $request)
    {
        $this->mpesa->handleCallback($request->all());

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}
