<?php
namespace App\Helpers;

use App\Models\Office;
use Illuminate\Support\Facades\Lang;

class TimeHelper
{
    public static function getPrettyDuration($duration) {
        $time = intval($duration);
        $hours = floor($time / 60);
        $minutes = ($time % 60);

        return Lang::choice('main.times.hour', $hours, ['h' => $hours]) . ' '
            . Lang::choice('main.times.minutes', $minutes, ['m' => $minutes]);
    }

    public static function getOfficeTimes($type = 'OPEN') {
        $endTime = $type == 'OPEN' ? 21 : 22;
        $officeTimes = [];

        for ($i = Office::OPEN_HOUR; $i <= $endTime; $i++) {
            $time = $i < 10 ? '0' . $i : $i;
            $officeTimes[] = $time . ':00';
            if ($i != ($endTime + 1)) {
                $officeTimes[] = $time . ':30';
            }
        }

        return $officeTimes;
    }
}