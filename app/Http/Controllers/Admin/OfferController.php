<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class OfferController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Offer');
        $param['page_description'] = trans('main.List');
        $param['offers'] = Offer::purchase()->paginate(PAGINATION_SIZE);

        return view('admin.pages.offer.index', $param);
    }

    public function create()
    {
        $param['page_title'] = trans('main.Offer');
        $param['page_description'] = trans('main.Create');
        $param['users'] = User::all();

        return view('admin.pages.offer.form', $param);
    }

    public function edit($id)
    {
        $param['page_title'] = trans('main.Offer');
        $param['page_description'] = trans('main.Edit');
        $param['users'] = User::all();
        $param['offer'] = Offer::find($id);

        return view('admin.pages.offer.form', $param);
    }

    public function store(Request $request)
    {
        $rules = [
            'name_en' => 'required',
            'user_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->has('offer_id')) {
            $id = $request->input('offer_id');
            $offer = Offer::find($id);
        } else {
            $offer = new Offer;
            $offer->photo = DEFAULT_PHOTO;
        }

        foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties){
            $nameCtrl = 'name_' . $localeCode;
            $descCtrl = 'description_' . $localeCode;
            $offer->$nameCtrl = $request->input($nameCtrl);
            $offer->$descCtrl = $request->input($descCtrl);
        }

        $offer->user_id = $request->input('user_id');
        $offer->price = $request->input('price');
        $offer->expire_at = $request->input('expire_at');
        $offer->is_review = FALSE;
        $offer->received = $request->input('received') ?: null;

        $offer->save();

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        return redirect()->route('admin.offer.index');
    }

    public function delete($id)
    {
        try {
            Offer::find($id)->delete();
            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.Error')]);
        }

        return redirect()->back();
    }
}
