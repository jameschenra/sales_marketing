<?php

namespace App\Helpers;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    public static function environment()
    {
        if (env('PAYPAL_MODE') == 'production') {
            $clientId = env('PAYPAL_LIVE_PAYOUT_CLIENT_ID');
            $clientSecret = env('PAYPAL_LIVE_PAYOUT_SECRET');
            return new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $clientId = env('PAYPAL_SANDBOX_PAYOUT_CLIENT_ID');
            $clientSecret = env('PAYPAL_SANDBOX_PAYOUT_SECRET');
            return new SandboxEnvironment($clientId, $clientSecret);
        }
        
    }
}