<?php

namespace App\Console;

use App\Models\WebsiteSetting;
use App\Http\Middleware\AddConstants;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendAddReviewReminder::class,
    ];

    /**
     * Get Website Settings
     *
     */
    public function bootstrap()
    {
        parent::bootstrap();

        $staticConstants = new AddConstants();
        $staticConstants->handle(null, function ($q) {});

        //Website Settings
        $website_settings = WebsiteSetting::all();
        foreach ($website_settings as $setting) {
            switch ($setting->type) {
                case 'int':
                    if (! defined(mb_strtoupper($setting->name))) {
                        define(mb_strtoupper($setting->name), (int)$setting->value);
                    }
                    break;
                case 'integer':
                    if (! defined(mb_strtoupper($setting->name))) {
                        define(mb_strtoupper($setting->name), (integer)$setting->value);
                    }
                    break;
                case 'bool':
                    if (! defined(mb_strtoupper($setting->name))) {
                        define(mb_strtoupper($setting->name), $setting->value == 'false' ? false : (bool)$setting->value);
                    }
                    break;
                default:
                    if (! defined(mb_strtoupper($setting->name))) {
                        define(mb_strtoupper($setting->name), $setting->value);
                    }
                    break;
            }
        }
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('bookings:offline-service-booking-update')->hourlyAt(0);
        $schedule->command('bookings:balance-charge-processing')->hourly(20);
        $schedule->command('bookings:check-not-confirmed-booking')->hourlyAt(40);
        $schedule->command('bookings:request-extend-delivery-date')->dailyAt('15:00');
        $schedule->command('bookings:send-request-confirm-online-delivery')->dailyAt('22:00');
        $schedule->command('bookings:auto-accept-online-delivery')->dailyAt('16:00');
        $schedule->command('reviews:add-review-reminder')->dailyAt('18:00');        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
