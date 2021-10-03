<?php

namespace App\Http\Middleware;

use URL;
use Closure;
use App\Models\WebsiteSetting;
use Illuminate\Filesystem\Filesystem;

class AddConstants
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$envCheckDir = env('APP_CHECK_DIR', false);

        define('SITE_NAME',                 'Weredy');
        define('PAGINATION_SIZE',           10);
        define('DEFAULT_ICON',              'fa fa-star');
        define('DEFAULT_PHOTO',             'default.png');

        define('DEFAULT_WIDGET_COLOR',      '#e6400c');
        define('DEFAULT_WIDGET_HEADER',     '#e6400c');
        define('DEFAULT_WIDGET_BACKGROUND', '#ffffff');

        define('DEFAULT_START_TIME',        '09:00');
        define('DEFAULT_END_TIME',          '18:00');

        define('DEFAULT_LAT',               41.902784);
        define('DEFAULT_LNG',               12.496366);

		define('ADMIN_EMAIL',             	'support@weredy.com');
		define('REPLY_EMAIL',               'support@weredy.com');
        define('NOREPLY_EMAIL',             'noreply@weredy.com');
		define('REPLY_NAME', 				env('EMAIL_SENDER_NAME'));

        define('DATE_FORMAT',               'd M Y');
        define('TIME_FORMAT',               'd/m/Y H:i:s');

        define('MY_BASE_URL',               URL::to('/'));
        define('HTTP_HOST',                 MY_BASE_URL);
        define('SUB_DIR',                   '');
        define('MAINTENANCE_IMG',           '/upload/maintenance/maintenance.png');

        define('HTTP_USER_PATH',            HTTP_HOST.'/upload/user/');
		define('ABS_USER_PATH',             public_path().'/upload/user/');
		define('WEBSITE_WALLET',                 0);
		
        $filesystem = new Filesystem;
	    if(!$filesystem->isDirectory(ABS_USER_PATH) && $envCheckDir){
		    $filesystem->makeDirectory(ABS_USER_PATH, 0777, true, true);
	    }

        define('HTTP_HOWITWORKS_PATH',      HTTP_HOST.'/upload/howitworks/');
        define('ABS_HOWITWORKS_PATH',       public_path().'/upload/howitworks/');
	    if(!$filesystem->isDirectory(ABS_HOWITWORKS_PATH) && $envCheckDir){
		    $filesystem->makeDirectory(ABS_HOWITWORKS_PATH, 0777, true, true);
	    }

		define('HTTP_ONLINE_FILE_PATH',      HTTP_HOST.'/upload/online_service_files/');

        define('HTTP_SERVICE_PATH',           HTTP_HOST.'/upload/service/');
        define('ABS_SERVICE_PATH',            public_path().'/upload/service/');
	    if(!$filesystem->isDirectory(ABS_SERVICE_PATH) && $envCheckDir){
		    $filesystem->makeDirectory(ABS_SERVICE_PATH, 0777, true, true);
	    }

        define('HTTP_POST_PATH',            HTTP_HOST.'/upload/post/');
        define('ABS_POST_PATH',             public_path().'/upload/post/');
	    if(!$filesystem->isDirectory(ABS_POST_PATH) && $envCheckDir){
		    $filesystem->makeDirectory(ABS_POST_PATH, 0777, true, true);
	    }

        define('HTTP_COVER_PATH',           HTTP_HOST.'/upload/cover/');
        define('ABS_COVER_PATH',            public_path().'/upload/cover/');
	    if(!$filesystem->isDirectory(ABS_COVER_PATH) && $envCheckDir){
		    $filesystem->makeDirectory(ABS_COVER_PATH, 0777, true, true);
	    }

        define('HTTP_REVIEW_PATH',          HTTP_HOST.'/upload/review/');
        define('ABS_REVIEW_PATH',           public_path().'/upload/review/');
	    if(!$filesystem->isDirectory(ABS_REVIEW_PATH) && $envCheckDir){
		    $filesystem->makeDirectory(ABS_REVIEW_PATH, 0777, true, true);
	    }

        define('HTTP_OFFER_PATH',           HTTP_HOST.'/upload/offer/');
        define('ABS_OFFER_PATH',            public_path().'/upload/offer/');
	    if(!$filesystem->isDirectory(ABS_OFFER_PATH) && $envCheckDir){
		    $filesystem->makeDirectory(ABS_OFFER_PATH, 0777, true, true);
	    }

        define('HTTP_LOYALTY_PATH',         HTTP_HOST.'/upload/loyalty/');
        define('ABS_LOYALTY_PATH',          public_path().'/upload/loyalty/');
	    if(!$filesystem->isDirectory(ABS_LOYALTY_PATH) && $envCheckDir){
		    $filesystem->makeDirectory(ABS_LOYALTY_PATH, 0777, true, true);
	    }

        define('HTTP_LOGO_PATH',            HTTP_HOST.'/upload/logo/');
        define('ABS_LOGO_PATH',             public_path().'/upload/logo/');
	    if(!$filesystem->isDirectory(ABS_LOGO_PATH) && $envCheckDir){
		    $filesystem->makeDirectory(ABS_LOGO_PATH, 0777, true, true);
	    }

        define('HTTP_IMG_PATH',             HTTP_HOST.'/assets/img/');
        define('ABS_IMG_PATH',              public_path().'/assets/img/');
	    if(!$filesystem->isDirectory(ABS_IMG_PATH) && $envCheckDir){
		    $filesystem->makeDirectory(ABS_IMG_PATH, 0777, true, true);
	    }

	    if (env('APP_ENV') == "local")
	    {
		    $phone_prefix_default = '+855';
	    }
	    else
	    {
		    $phone_prefix_default = '+358';
	    }

	    define('PAYPAL_SERVER', env('PAYPAL_SERVER', 'www.sandbox.paypal.com'));
	    define('PAYPAL_URL', env('PAYPAL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr'));
	    define('PAYPAL_BUSINESS', env('PAYPAL_BUSINESS', 'ankorvisitor003@gmail.com'));
	    define('STRIPE_SECRET_KEY', env('STRIPE_SECRET_KEY', 'sk_test_xiBipAcqPYOnXWWDUop1Pveg'));
	    define('STRIPE_PUBLISH_KEY', env('STRIPE_PUBLISH_KEY', 'pk_test_LsObZosxYmpM5VX9eBqVh4SP'));
	    define("INFOBIP_USERNAME", env('INFOBIP_USERNAME', 'varaa6'));
	    define("INFOBIP_PASSWORD", env('INFOBIP_PASSWORD', 'varaa12'));
		define("PHONE_PREFIX", env('PHONE_PREFIX', $phone_prefix_default));
		
		//Website Settings
		$website_settings = WebsiteSetting::all();
	    foreach ($website_settings as $setting) {
		    switch ($setting->type)
		    {
				case 'int':
				    if (!defined(mb_strtoupper($setting->name))) {
						define(mb_strtoupper($setting->name), (int)$setting->value);						
				    }
					break;
			    case 'integer':
				    if (!defined(mb_strtoupper($setting->name))) {
					    define(mb_strtoupper($setting->name), (integer)$setting->value);
				    }
				    break;
			    case 'bool':
				    if (!defined(mb_strtoupper($setting->name))) {
					    define(mb_strtoupper($setting->name), $setting->value == 'false' ? false : (bool)$setting->value);
				    }
				    break;
			    default:
				    if (!defined(mb_strtoupper($setting->name))) {
					    define(mb_strtoupper($setting->name), $setting->value);
				    }
				    break;
		    }
		}

	    return $next($request);
    }
}
