<?php
namespace App\Models;

use App\User;
use App\Models\Book;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = ['user_id', 'reviewer_id', 'rate', 'review', 'book_id', 'is_published', 'is_email_sent'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'last_name', 'email');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id')->select('id', 'name', 'last_name', 'email');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
