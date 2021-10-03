<?php

namespace App\Services;


use App\User;
use Exception;
use App\Models\UserTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Services\PayPalCreatePayout;

class BalanceService
{
    /**
     * @param User $user
     * @param $amount
     * @param $email
     * @param $provider
     * @return void
     * @throws Exception
     */
    public function withdraw(User $user, $email, $amount, $provider)
    {
        try {
            DB::beginTransaction();

            $transaction = $user->withdraw($amount, compact('provider', 'email'));

            $output = PayPalCreatePayout::withdraw($email, $amount);

            $transaction->addParams(['api-output' => $output]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();

            throw $exception;
        }
    }
}