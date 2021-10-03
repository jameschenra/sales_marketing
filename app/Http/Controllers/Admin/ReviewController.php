<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Mail\ReviewPublishedMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ReviewController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.review');
        $param['page_description'] = trans('main.Management');
        $param['reviews'] = Review::with('user', 'reviewer')->orderByDesc('id')->paginate(PAGINATION_SIZE);

        return view('admin.pages.review.index', $param);
    }

    public function edit($id)
    {
        $param['page_title'] = trans('main.Review');
        $param['page_description'] = trans('main.Edit');
        $param['review'] = Review::find($id);

        return view('admin.pages.review.form', $param);
    }

    public function store(Request $request)
    {
        if ($request->has('review_id')) {
            $id = $request->input('review_id');
            $review = Review::find($id);
        } else {
            $review = new Review;
        }

        if($review->is_email_sent == 0 && $request->is_published == 1) {
            Mail::to($review->reviewer->email, $review->reviewer->name)->queue(new ReviewPublishedMail($review));
            $review->is_email_sent = 1;
        }

        $review->review = $request->input('review');
        $review->is_published = $request->input('is_published');
        $review->save();

        $user = $review->user;
        $user->review_score = $user->getReviewScore();
        $user->save();

        $book = $review->book;
        $service = $book->service;
        $service->review_score = $service->getReviewScore();
        $service->save();

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        return redirect()->route('admin.review.index');
    }

    public function delete($id)
    {
        try {
            Review::find($id)->delete();
            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.Error')]);
        }

        return redirect()->back();
    }
}
