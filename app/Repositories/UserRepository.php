<?php

namespace App\Repositories;

use App\User;
use App\Models\Office;
use App\Models\Service;
use App\Models\Profession;
use App\Models\UserDetail;
use App\Models\ServiceOffice;
use App\Models\ProfessionByUser;
use App\Models\ProfessionCategory;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function __construct()
    {

    }

    public function filter(array $rules = [])
    {
        $query = User::professional()->whereHas('detail', function($q) {
            $q->where('profile_wizard_completed', '>', UserDetail::CONTACT_COMPLETED);
        });

        if (array_has($rules, 'profession_name')) {
            $query->whereIn('id', $this->getUsersByProfessionSlug($rules['profession_name']));
        } else if (array_has($rules, 'category_name')) {
            $query->whereIn('id', $this->getUsersByCategorySlug($rules['category_name']));
        }

        if (array_has($rules, 'lat') && array_has($rules, 'lng')) {
            $query->whereIn('id', $this->getUsersByCityGeoLocation($rules['lat'], $rules['lng']));
        } else if (array_has($rules, 'city')) {
            $query->whereIn('id', $this->getUsersFromCity($rules['city']));
        }

        if (array_has($rules, 'online')) {
            $before8Hrs = \Carbon\Carbon::now()->subHours(8)->format("Y-m-d H:i:s");
            $query->where(function($q) use ($before8Hrs) {
                $q->whereNotNull('last_activity')
                    ->where('last_activity', '>' , $before8Hrs);
            });
        }

        if (array_has($rules, 'available_office')) {
            $query->whereIn('id', $this->getUsersOffsite());
        }

        if (array_has($rules, 'keyword')) {
            $keyword = $rules['keyword'];

            $query->where(function($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $keyword . '%')
                    ->orWhereIn('id', $this->getUsersByCategoryKeyword($keyword))
                    ->orWhereIn('id', $this->getUsersByProfessionKeyword($keyword));
            });
        }

        if (array_has($rules, 'language')) {
            $query->whereHas('detail', function ($q) use ($rules) {
                $q->where('languages', 'LIKE', '%' . $rules['language'] . '%');
            });
        }

        if (array_has($rules, 'country')) {
            $query->whereHas('offices', function ($query) use ($rules) {
                $query->where('country_id', $rules['country']);
            });
        }

        if (array_has($rules, 'price_range')) {
            $query->orderBy('hourly_rate', ($rules['price_range'] == 'low') ? 'ASC' : 'DESC');
        }

        if (array_has($rules, 'user_rating')) {
            $query->orderBy('review_score', ($rules['user_rating'] == 'low') ? 'ASC' : 'DESC');
        }

        return $query;
    }

    private function getUsersByCategorySlug($slug)
    {
        $categoryId = ProfessionCategory::where('slug', $slug)->first()->id;
        return ProfessionByUser::where('profession_category_id', $categoryId)->get(['user_id'])->pluck('user_id')->toArray();
    }

    private function getUsersByProfessionSlug($slug)
    {
        $professionId = Profession::where('slug', $slug)->first()->id;

        return ProfessionByUser::where('profession_id', $professionId)
            ->get(['user_id'])
            ->pluck('user_id')
            ->toArray();
    }

    private function getUsersByCityGeoLocation($lat, $lng)
    {
        $officeIds = [];

        $nearestOffices = DB::table('offices AS o')
            ->select(
                'o.id',
                DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                * cos(radians(o.lat)) 
                * cos(radians(o.lng) - radians(" . $lng . ")) 
                + sin(radians(" .$lat. ")) 
                * sin(radians(o.lat))) AS distance")
            )->having('distance', '<=', 100)
            ->get();

        foreach ($nearestOffices as $nearestOffice) {
            $officeIds[] = $nearestOffice->id;
        }

        return ServiceOffice::whereIn('office_id', $officeIds)
            ->distinct()
            ->get(['user_id'])
            ->pluck('user_id')
            ->toArray();
    }

    private function getUsersFromCity($city)
    {
        $officeIds = Office::select('offices.id')
            ->leftJoin('City as c', 'offices.city_id', '=', 'c.id')
            ->where('c.name', $city)
            ->pluck('id')
            ->all();

        return ServiceOffice::select('user_id')
            ->whereIn('office_id', $officeIds)
            ->distinct()
            ->pluck('user_id')
            ->all();
    }

    private function getUsersByCategoryKeyword($keyword)
    {
        $categoryIds = ProfessionCategory::select('id')
            ->where('name_en', 'LIKE', '%' . $keyword . '%')
            ->orWhere('name_it', 'LIKE', '%' . $keyword . '%')
            ->orWhere('name_es', 'LIKE', '%' . $keyword . '%')
            ->pluck('id')
            ->all();

        if (empty($categoryIds)) {
            return [];
        } else {
            return ProfessionByUser::select('user_id')
                ->where('profession_category_id', $categoryIds)
                ->pluck('user_id')
                ->all();
        }
    }

    private function getUsersByProfessionKeyword($keyword)
    {
        return ProfessionByUser::select('user_id')
            ->whereHas('profession', function ($q) use ($keyword) {
                $q->where('name_en', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('name_it', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('name_es', 'LIKE', '%' . $keyword . '%');
            })->pluck('user_id')
            ->all();
    }

    private function getUsersOffsite()
    {
        $userIds = ServiceOffice::where('onsite_type', '<>', ServiceOffice::TYPE_ONSITE)
            ->groupBy('user_id')
            ->pluck('user_id')
            ->all();

        return $userIds;
    }
}
