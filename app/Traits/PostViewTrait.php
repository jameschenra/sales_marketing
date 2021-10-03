<?php

namespace App\Traits;


use App\User;
use Illuminate\Database\Eloquent\Builder;

trait PostViewTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param Builder $query
     * @param $user
     * @param string $sessionId
     * @return Builder
     */
    public function scopeByUser(Builder $query, $user, string $sessionId)
    {
        if ($user) {
            $query->where('user_id', $user->id);
        } else {
            $query->where('session_id', $sessionId);
        }

        return $query;
    }
}