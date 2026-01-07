<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MidtransService;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request, MidtransService $midtransService)
    {
        $payload = $request->all();
        $midtransService->handleNotification($payload);

        return response()->json(['message' => 'OK']);
    }
}