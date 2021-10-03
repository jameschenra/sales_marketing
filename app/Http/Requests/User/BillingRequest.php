<?php

namespace App\Http\Requests\User;

use App\Models\Country;
use App\Models\CompanyType;
use Illuminate\Foundation\Http\FormRequest;

class BillingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $countryId = request()->input('invoice_country_id');
        if ($countryId) {
            $country = Country::find($countryId);
            $zipCodeValid = '|postal_code_for:' . $country->iso2;
        } else {
            $zipCodeValid = '';
        }

        $rules = [
            'company_name' => 'required',
            'invoice_country_id' => 'required',
            'company_type_id' => 'required',
            'invoice_vat_id' => 'required',
            'invoice_pec' => 'email|nullable',
            'invoice_city' => 'required',
            'billing_addr' => 'required',
            'billing_zip_code' => 'required' . $zipCodeValid,
        ];

        $user = auth()->user();
        $billingInfoId = $user->billingInfo ? $user->billingInfo->id : null;
        $rules['invoice_pec'] .= '|unique:user_billing_infos,invoice_pec,' . $billingInfoId;

        $request = request();
        $companyTypeId = $request->input('company_type_id');
        if ($countryId == Country::COUNTRY_ITALY && $companyTypeId == CompanyType::TAX_VAT) {
            if ($request->input('invoice_unique_code')) {
                $rules['invoice_unique_code'] = 'required_without:invoice_pec|min:7|max:7';
            } else {
                $rules['invoice_unique_code'] = 'required_without:invoice_pec';
            }

            if ($request->input('invoice_pec')) {
                $rules['invoice_pec'] = 'required_without:invoice_unique_code|email'
                    . '|unique:user_billing_infos,invoice_pec,' . $billingInfoId;
            } else {
                $rules['invoice_pec'] = 'required_without:invoice_unique_code';
            }
        }

        return $rules;
    }
}
