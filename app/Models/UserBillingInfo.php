<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBillingInfo extends Model
{
    protected $table = 'user_billing_infos';

    protected $fillable = [
        'user_id', 'company_name', 'company_type_id', 'billing_addr', 'region',
        'invoice_country_id', 'invoice_city', 'invoice_vat_id', 'invoice_unique_code',
        'invoice_pec', 'billing_zip_code'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function country() {
        return $this->belongsTo('App\Models\Country', 'invoice_country_id', 'id');
    }
}
