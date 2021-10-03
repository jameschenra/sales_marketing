<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TFA extends Model
{
    public const WITHDRAW = 'WITHDRAW',
        PAY_TO_THE_OFFICE = 'PAY_TO_THE_OFFICE',
        BOOK_FREE_SERVICE = 'BOOK_FREE_SERVICE';

    protected const DEFAULT_DURATION = 15 * 60;// 15 minutes

    protected $table = 'two_factor_authorization_codes';

    protected $fillable = [
        'user_id',
        'type',
        'duration',
        'data'
    ];

    protected $casts = ['data' => 'json'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            do {
                $code = str_random(6);
            } while (static::type($model->type)->code($code)->exists());

            $model->code = $code;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param Builder $query
     * @param string $code
     * @return Builder
     */
    public function scopeCode(Builder $query, string $code): Builder
    {
        return $query->where('code', $code);
    }

    /**
     * @param Builder $query
     * @param string  $code
     * @param User $user
     *
     * @return Builder
     */
    public function scopeCodeForUser(Builder $query, string $code, User $user): Builder
    {
        return $query->where('code', $code)->where('user_id', $user->id);
    }

    /**
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithdraw(Builder $query): Builder
    {
        return $query->type(static::WITHDRAW);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->created_at->addSeconds($this->duration ?? static::DEFAULT_DURATION) >= Carbon::now();
    }
}
