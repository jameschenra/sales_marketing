<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\Theme\Admin\Init;
use App\Classes\Theme\User\Init as UserInit;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\ViewComposers\SEODataComposer;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(Request $request)
    {
        // load metronic config by subdomain
        $path = $request->path();
        $paths = explode('/', $path);

        if (empty($paths) || $paths[0] != 'admin') {
            UserInit::run();
        } else {
            Init::run();
        }

        $this->shareDataWithViews();
    }

    public function redirectErrorBack($msg = null) {
        if ($msg == null) {
            $msg = trans("Error occured while processing.");
        }

        session()->flash(['message', 
            [
                'type' => 'danger',
                'msg' => $msg,
            ]
        ]);

        return redirect()->back()->withInput();
    }

    private function shareDataWithViews()
    {
        view()->composer('user.layout.default', SEODataComposer::class);
    }
}
