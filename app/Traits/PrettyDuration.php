<?php

namespace App\Traits;

trait PrettyDuration
{
    public function getPrettyDurationAttribute()
    {
        $time = intval($this->duration);
        $hours = floor($time / 60);
        $minutes = ($time % 60);

        return \Lang::choice('main.times.hour', $hours, ['h' => $hours]) . ' '
            . \Lang::choice('main.times.minutes', $minutes, ['m' => $minutes]);
    }

};
