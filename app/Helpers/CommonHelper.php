<?php

use App\Models\UserDetail;

function escapeHtmlDescription($description)
{
    $descString = strip_tags($description);
    $descString = str_replace("\"", "", $descString);
    $descString = str_replace("\\", "", $descString);
    // $descString = str_replace("'", "\'", $descString);
    $descString = trim(html_entity_decode($descString), " \t\n\r\0\x0B\xC2\xA0");

    return $descString;
}

function hasNotify()
{
    return checkNotifyLowBalance() || checkNotifyHasNoPost();
}

function checkNotifyLowBalance()
{
    $user = auth()->user();

    if (session('showNotifyLowBalance') == 1) {
        if ($user && $user->wallet_balance < MIN_CREDIT_TO_STOP_VIEW_FREE_SERVICE
            && $user->services()->count() > 0 && $user->detail->unsubscribe_minimum_credit == 1) {
            return true;
        }
    }

    return false;
}

function checkNotifyHasNoPost()
{
    if (session('showNotifyHasNoPost') == 1) {
        $user = auth()->user();
        $numberOfServices = $user->services->count();
        if (!$numberOfServices) {
            return true;
        }
    }

    return false;
}

function isProfileCompleted()
{
    $user = Auth::user();

    if ($user->detail->profile_wizard_completed >= UserDetail::CONTACT_COMPLETED) {
        return true;
    } else {
        return false;
    }
}