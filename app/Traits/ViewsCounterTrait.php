<?php

namespace App\Traits;

trait ViewsCounterTrait
{
    /**
     * @param $user
     * @param string $sessionId
     * @return $this
     */
    public function increaseViews($user, string $sessionId)
    {
        $this->views++;

        $view = $this->views()->byUser($user, $sessionId)->first();
        if ($view) {
            $view->views++;
            $view->save();
        } else {
            $this->unique_views++;
            $this->views()->create([
                'user_id' => $user->id ?? null,
                'session_id' => $sessionId,
            ]);
        }

        $this->save();

        return $this;
    }
}
