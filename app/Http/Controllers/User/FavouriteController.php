<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $favoriteServices = Favourite::with('service')
            ->where(['user_id' => $userId, 'type' => Favourite::TYPE_SERVICE
            ])->get();
        $favourites['services'] = $favoriteServices->filter(function ($item) {
            $service = $item->service;
            if ($service) {
                $user = $service->user;
                if ($service->price == 0 && $user->detail->unsubscribe_minimum_credit == 0) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        });

        $favourites['professionals'] = Favourite::with('user')
            ->where(['user_id' => $userId, 'type' => Favourite::TYPE_PROFESSIONAL])
            ->get();

        return view('user.pages.favourite.index')->with($favourites);
    }

    public function store(Request $request)
    {
        Favourite::updateOrCreate([
            'user_id' => auth()->id(),
            'favourite_id' => $request->input('favourite_id'),
            'type' => $request->input('type'),
        ], [
            'note' => $request->input('note'),
        ]);

        return response()->json(['response' => 'success'], 200);
    }

    public function delete($id)
    {
        $favourite = Favourite::find($id);
        if ($favourite) {
            $favourite->delete();
        }

        return response()->json(['response' => 'success'], 200);
    }
}
