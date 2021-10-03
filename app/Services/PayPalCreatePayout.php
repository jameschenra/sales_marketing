<?php

namespace App\Services;

use App\Helpers\PayPalClient;
use Illuminate\Support\Facades\Log;
use PaypalPayoutsSDK\Payouts\PayoutsPostRequest;

class PayPalCreatePayout
{
    public static function buildRequestBody($email, $amount, $currency)
    {
        return [
            "sender_batch_header" => [
                "email_subject" => "Weredy withdraw money",
            ],
            "items" => [
                [
                    "recipient_type" => "EMAIL",
                    "receiver" => $email,
                    "note" => "Your payout for " . $currency . ' ' . $amount,
                    "sender_item_id" => "withdraw for " . $currency . ' ' . $amount,
                    "amount" => [
                        "currency" => $currency,
                        "value" => $amount,
                    ],
                ],
            ],
        ];
    }

    public static function withdraw($email, $amount, $currency = "EUR")
    {
        try {
            $request = new PayoutsPostRequest();
            $request->body = self::buildRequestBody($email, $amount, $currency);
            $client = PayPalClient::client();
            $response = $client->execute($request);
            // if ($debug) {
            //     print "Status Code: {$response->statusCode}\n";
            //     print "Status: {$response->result->batch_header->batch_status}\n";
            //     print "Batch ID: {$response->result->batch_header->payout_batch_id}\n";
            //     print "Links:\n";
            //     foreach ($response->result->links as $link) {
            //         print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
            //     }
            //     // To toggle printing the whole response body comment/uncomment below line
            //     echo json_encode($response->result, JSON_PRETTY_PRINT), "\n";
            // }
            return $response;
        } catch (HttpException $e) {
            Log::error($e->getMessage(), ['payoutRequest' => $request]);
            throw $e;
        }
    }
}
