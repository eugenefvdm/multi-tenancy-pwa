<?php

namespace Eugenefvdm\MultiTenancyPWA\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebPushSubscriptionController extends Controller
{
    public function handle(Request $request): \Illuminate\Http\JsonResponse
    {
        $subscription = $request->input();

        if (! isset($subscription['endpoint'])) {
            ray("Endpoint called but it's not a subscription");

            return response()->json(['error' => 'Error: not a subscription'], 400);
        }

        $method = $request->method();

        switch ($method) {
            case 'POST':
                Log::debug('Create a new subscription');
                Log::debug($request->all());

                $user = Auth::user();

                $user->updatePushSubscription(
                    $request->endpoint,
                    $request->publicKey,
                    $request->authToken,
                    $request->contentEncoding
                );

                return response()->json(['success' => 'Subscription created'], 201);
            case 'PUT':

                Log::debug('Update an existing subscription');
                Log::debug($request->all());

                $user = Auth::user();

                $user->updatePushSubscription(
                    $request->endpoint,
                    $request->publicKey,
                    $request->authToken,
                    $request->contentEncoding
                );

                return response()->json(['success' => 'Subscription updated'], 200);
            case 'DELETE':
                Log::debug('Delete an existing subscription');
                Log::debug($request->all());

                $user = Auth::user();

                $user->deletePushSubscription($request->endpoint);

                return response()->json(['success' => 'Subscription deleted'], 200);
            default:
                return response()->json(['error' => 'Error: method not handled'], 405);
        }
    }
}
