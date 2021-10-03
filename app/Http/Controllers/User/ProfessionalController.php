<?php

namespace App\Http\Controllers\User;

use App\Models\Country;
use App\Models\Language;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\ProfessionCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Browser;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfessionalController extends Controller
{
    public function search(Request $request, $category_name = null, $profession_name = null)
    {
        $locale = app()->getLocale();
        $search_params = $request->all();

        if ($category_name) {
            $category = ProfessionCategory::where('slug', $category_name)->first();
            $search_params += ['category_name' => $category_name];

            if ($profession_name) {
                $search_params += ['profession_name' => $profession_name];
            }

            $param['professions'] = Profession::where('category_id', $category->id)
                ->orderBy('name_' . app()->getLocale(), 'ASC')
                ->get();
        }

        $userRepository = new UserRepository;
        $param['users'] = $userRepository->filter($search_params)
            ->with('profsByUser', 'profsByUser.category', 'offices')
            ->paginate(PAGINATION_SIZE);

        $param['selected_category_slug'] = $category->slug ?? '';
        $param['selected_profession_slug'] = $profession_name ?? '';
        $param['profCategories'] = ProfessionCategory::getOrderedList();
        $param['countries'] = Country::getOrderByShortName();
        $param['languages'] = Language::whereNotNull('code')->orderBy('name_' . $locale, 'ASC')->get();
        $param['is_mobile'] = Browser::isMobile();

        return view('user.pages.professional.search')->with($param);
    }
}
