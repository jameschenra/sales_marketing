<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\WebsiteSettingsOption;

class WebsiteSetting extends Model {
	protected $table = 'website_settings';
	protected $fillable = ['name','name_trans', 'value', 'description', 'type', 'form_type', 'input_type'];

	/**
	 * Get select options
	 */
	public function selectOptions()
	{
		return $this->hasMany(WebsiteSettingsOption::class, 'setting_id');
	}
}
