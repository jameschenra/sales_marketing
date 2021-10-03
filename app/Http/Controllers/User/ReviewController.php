<?php

namespace App\Http\Controllers\User;

use App\Models\Book;
use App\Models\Review;
use App\Models\ReviewToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        if (!isset($request->token)) {
            abort(404);
        }

        $token = ReviewToken::where('token', $request->token)->first();
        if (!$token) {
            abort(404);
        }

        $book = Book::findOrFail($token->book_id);
        if ($book->user_id != auth()->id()) {
            abort(403);
        }

        return view('user.pages.review.create', [
            'book' => $book,
            'token' => $request->token,
            'reviewer_id' => auth()->user()->id,
        ]);
    }

    public function store(Request $request)
    {
        $token = ReviewToken::where('token', $request->token)->first();

        if (!$token) {
            abort(404);
        }
        
        $reviewData = $request->all();
        if (!$reviewData['rate']) {
            $reviewData['rate'] = 0;
        }

        $review = Review::create($reviewData);
        Book::where('id', $review->book_id)->update(['review_id' => $review->id]);
        ReviewToken::where('token', $request->token)->delete();
        
        $defaultLang = app()->getLocale();
        Mail::send('email.review-added.' . $defaultLang, [
            'review' => $review,
        ], function ($message) use ($review, $defaultLang) {
            $message->from($review->user->email, $review->user->name)
                ->to(ADMIN_EMAIL, REPLY_NAME)
                ->subject(trans('main.review added', [], $defaultLang));
        });

        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.Review added')]);
        return redirect()->route('user.review.view', ['id' => $review->id]);
    }

    public function view($id)
    {
        $review = Review::findOrFail($id);

        return view('user.pages.review.view', [
            'review' => $review,
        ]);
    }
}
