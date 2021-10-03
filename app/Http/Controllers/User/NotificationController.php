<?php

namespace App\Http\Controllers\User;

use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications
     *
     */
    public function index(Request $request)
    {
        $showNotifyLowBalance = checkNotifyLowBalance();
        $showNotifyHasNoPost = checkNotifyHasNoPost();

        return view('user.pages.profile.settings-notify')->with(
            compact('showNotifyLowBalance', 'showNotifyHasNoPost'));
    }
    
    /***
     * 
     * Deleted notification
     * 
     * */
    public function deletedNotification(Request $request) {
        $notifyKey = $request->input('notify_key');
        if (Auth::check() && $notifyKey) {
            if (session()->has($notifyKey)) {
                session()->forget($notifyKey);
                $response['message'] = "ok";
                return ['message' => "ok"];
            }
        }
    }
}
