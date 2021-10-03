<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSettingsOption extends Model
{
	protected $table = 'website_settings_options';
	protected $fillable = ['setting_id','value', 'trans_name'];
}
