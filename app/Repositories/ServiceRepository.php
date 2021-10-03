<?php

namespace App\Repositories;

use App\Models\Country;
use App\Models\Office;
use App\Models\OfficeOpening;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceOffice;
use App\Models\ServiceSubCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServiceRepository
{
    public function __construct()
    {

    }

    public function filter(array $rules = [])
    {
        // only active service
        $query = Service::where('active', 1);

        if (array_has($rules, 'sub_category_name')) {
            $query->whereIn('sub_category_id', $this->getServiceIdsBySubCategorySlug($rules['sub_category_name']));
        } else if (array_has($rules, 'category_name')) {
            $query->whereIn('category_id', $this->getServiceIdsByCategorySlug($rules['category_name']));
        }

        if (array_has($rules, 'lat') && array_has($rules, 'lng')) {
            $query->whereIn('id', $this->getServiceIdByCityGeoLocation($rules['lat'], $rules['lng']));
        } else if (array_has($rules, 'city')) {
            $query->whereIn('id', $this->getServiceIdFromCity($rules['city']));
        }

        if (array_has($rules, 'keyword')) {
            $keyword = $rules['keyword'];

            $query->where(function ($q) use ($keyword) {
                $q->where('name_en', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('name_it', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('name_es', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('description_en', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('description_it', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('description_es', 'LIKE', '%' . $keyword . '%')
                    ->orWhereIn('category_id', $this->getServiceIdsByCategoryKeyword($keyword))
                    ->orWhereIn('sub_category_id', $this->getServiceIdsBySubCategoryKeyword($keyword));
            });
        }

        if (array_has($rules, 'availability')) {
            if ($rules['availability'] == 1) {
                $query->whereIn('id', $this->getOnOffSite('on-site'));
            } else if ($rules['availability'] == 2) {
                $query->whereIn('id', $this->getOnOffSite('off-site'));
            } else if ($rules['availability'] == 3) {
                $query->where('provide_online_type', Service::PROVIDE_ONLINE_TYPE);
            }
        }

        if (array_has($rules, 'payment_type')) {
            if ($rules['payment_type'] == 1) {
                $query->where(function ($q) {
                    $q->where('client_payment_type', Service::PAYMENT_TYPE_ONLINE)
                        ->orWhere('client_payment_type', Service::PAYMENT_TYPE_ONLINEONSITE)
                        ->orderBy('client_payment_type');
                });
            } else if ($rules['payment_type'] == 2) {
                $query->where(function ($q) {
                    $q->where('client_payment_type', Service::PAYMENT_TYPE_ONSITE)
                        ->orWhere('client_payment_type', Service::PAYMENT_TYPE_ONLINEONSITE)
                        ->orderBy('client_payment_type');
                });
            }
        }

        if (array_has($rules, 'price_range')) {
            if ($rules['price_range'] == 'low' || $rules['price_range'] == 'high') {
                $query->where('price', '>', 0)
                    ->orderBy('price', ($rules['price_range'] == 'low') ? 'ASC' : 'DESC');
            } else {
                $query->where('price', 0);
            }
        } else {
            $query->orderBy('price', 'desc');
        }

        // order by rating
        if (array_has($rules, 'service_rating')) {
            if ($rules['service_rating'] == 'low') {
                $query->orderBy('review_score', 'ASC');
            } else if ($rules['service_rating'] == 'high') {
                $query->orderBy('review_score', 'DESC');
            }
        }

        $query->orderBy('name_' . app()->getLocale(), 'ASC');

        return $query;
    }

    private function getServiceIdsByCategorySlug($slug)
    {
        return ServiceCategory::where('slug', $slug)
            ->pluck('id')
            ->all();
    }

    private function getServiceIdsBySubCategorySlug($slug)
    {
        return ServiceSubCategory::where('slug', $slug)
            ->pluck('id')
            ->all();
    }

    private function getServiceIdByCityGeoLocation($lat, $lng)
    {
        $officeIds = [];

        $nearestOffices = DB::select('SELECT offices.id, '
            . '( 6371 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') )'
            . ' + sin( radians(' . $lat . ') ) * sin( radians( lat ) ) ) ) AS distance'
            . ' FROM offices HAVING distance <= 100'
        );

        foreach ($nearestOffices as $nearestOffice) {
            $officeIds[] = $nearestOffice->id;
        }

        return ServiceOffice::select('service_id')
            ->whereIn('office_id', $officeIds)
            ->distinct()
            ->pluck('service_id')
            ->all();
    }

    private function getServiceIdFromCity($city)
    {
        $officeIds = Office::leftJoin('City as c', 'offices.city_id', '=', 'c.id')
            ->where('c.name', $city)
            ->get(['offices.id'])
            ->pluck('id')
            ->toArray();

        return ServiceOffice::select('service_id')
            ->whereIn('office_id', $officeIds)
            ->distinct()
            ->pluck('service_id')
            ->all();
    }

    private function getServiceIdsByCategoryKeyword($keyword)
    {
        $catIds = ServiceCategory::where('name_en', 'LIKE', '%' . $keyword . '%')
            ->orWhere('name_it', 'LIKE', '%' . $keyword . '%')
            ->orWhere('name_es', 'LIKE', '%' . $keyword . '%')
            ->orWhere('description_en', 'LIKE', '%' . $keyword . '%')
            ->orWhere('description_it', 'LIKE', '%' . $keyword . '%')
            ->orWhere('description_es', 'LIKE', '%' . $keyword . '%')
            ->pluck('id')
            ->toArray();

        return $catIds;
    }

    private function getServiceIdsBySubCategoryKeyword($keyword)
    {
        $subCatIds = ServiceSubCategory::where('name_en', 'LIKE', '%' . $keyword . '%')
            ->orWhere('name_it', 'LIKE', '%' . $keyword . '%')
            ->orWhere('name_es', 'LIKE', '%' . $keyword . '%')
            ->orWhere('description_en', 'LIKE', '%' . $keyword . '%')
            ->orWhere('description_it', 'LIKE', '%' . $keyword . '%')
            ->orWhere('description_es', 'LIKE', '%' . $keyword . '%')
            ->pluck('id')
            ->toArray();

        return $subCatIds;
    }

    private function getFirstAvailableServices()
    {
        $openedOffices = $this->getOpenedOffices();

        return ServiceOffice::whereIn('office_id', $openedOffices)->distinct()->get(['service_id'])->toArray();
    }

    private function getOnOffSite($type)
    {
        $serviceIds = [];
        if ($type == 'on-site') {
            $serviceIds = ServiceOffice::where('onsite_type', ServiceOffice::TYPE_ONSITE)
                ->orWhere('onsite_type', ServiceOffice::TYPE_ONOFFSITE)
                ->orderBy('onsite_type')
                ->select('service_id')
                ->groupBy('service_id', 'onsite_type')
                ->get()
                ->toArray();
        } else {
            $serviceIds = ServiceOffice::where('onsite_type', ServiceOffice::TYPE_OFFSITE)
                ->orWhere('onsite_type', ServiceOffice::TYPE_ONOFFSITE)
                ->orderBy('onsite_type')
                ->select('service_id')
                ->groupBy('service_id', 'onsite_type')
                ->get()
                ->toArray();
        }

        return $serviceIds;
    }

    private function getOpenedOffices()
    {
        $openedOffices = [];
        $today = Carbon::today();

        for ($i = 0; $i < 8; $i++) {
            $offices = $this->openedForTheDay($today->addDay($i)->format('D'));
            if (count($offices)) {
                $openedOffices = array_merge($openedOffices, $offices);
            }
        }

        return array_unique($openedOffices);
    }

    private function openedForTheDay($dayName)
    {
        return OfficeOpening::where(
            strtolower($dayName) . '_start', '!=', 'Closed'
        )->pluck('office_id')
            ->toArray();
    }

    private function getOfficesMatchedCountries($keyword)
    {
        $countries = Country::where('short_name_en', 'LIKE', '%' . $keyword . '%')
            ->orWhere('short_name_it', 'LIKE', '%' . $keyword . '%')
            ->orWhere('short_name_es', 'LIKE', '%' . $keyword . '%')
            ->get(['id']);

        return Office::whereIn('country_id', $countries->toArray())->get(['id'])->toArray();
    }

    private function getOfficesMatchedCities($keyword)
    {
        $cities = City::where('name', 'LIKE', '%' . $keyword . '%')
            ->get(['id']);

        return Office::whereIn('city_id', $cities->toArray())->get(['id'])->toArray();
    }

    private function getServiceByOfficeIds(array $officeIds)
    {
        return ServiceOffice::whereIn('office_id', $officeIds)->get(['service_id'])->toArray();
    }

    // for the free service select if owner has balance
    private function getServiceIdWithAvailableCredit($query)
    {
        return $query->where(function ($q) {
            $q->where('price', 0)->whereHas('user.balance', function ($q) {
                $q->where('user_balances.balance', '>', 0);
            });

            $q->orWhere('price', '>', 0);
        });
    }
}
