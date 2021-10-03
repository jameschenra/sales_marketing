<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewToken extends Model
{
    protected $table = 'review_tokens';

    protected $fillable = [
        'token', 'book_id',
    ];
}
