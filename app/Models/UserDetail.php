<?php

namespace App\Models;

use App\User;
use App\Models\Country;
use App\Models\Language;
use App\Models\EnrollType;
use App\Models\Association;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;

class UserDetail extends Model
{
    use ModelFieldLanguageHelper;

    protected $table = 'user_details';

    protected $fillable = [
        'user_id', 'enroll_type', 'association_id', 'country_id', 'city', 'reg_number', 'languages', 'photo',
        'description_en', 'description_it', 'description_es', 'profile_wizard_completed',
        'unsubscribe_minimum_credit'
    ];

    const NOTHING_COMPLETED = 0;
    const PROFILE_COMPLETED = 1;
    const CONTACT_COMPLETED = 2;
    const SERVICE_COMPLETED = 3;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function country() {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function enrollType() {
        return $this->belongsTo(EnrollType::class, 'enroll_type', 'id');
    }

    public function association() {
        return $this->belongsTo(Association::class, 'association_id', 'id');
    }

    public function getLang($count = 0)
    {
        $locale = app()->getLocale();
        $short_langs = explode(",", $this->languages);
        $langs = [];
        foreach ($short_langs as $key => $sl) {
            if ($count > 0) {
                if ($count >= ($key + 1)) {
                    $lang_obj = Language::where('code', $sl)->firstOrFail();
                    $langs[] = $lang_obj['name_' . $locale];
                }
            } else {
                $lang_obj = Language::where('code', $sl)->firstOrFail();
                $langs[] = $lang_obj['name' . $locale];
            }
        }
        $langs = implode(", ", $langs);
        return $langs;
    }

    public function getDescriptionAttribute()
    {
        return $this->getFieldByLanguage('description');
    }
}
