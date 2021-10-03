<?php

namespace App\Repositories;

use App\Models\Country;
use App\Models\CompanyType;
use App\Models\UserBillingInfo;

class BillingRepository
{
    public static function storeBillingInfo($request)
    {
        $invoiceUniqueCode = $request->input('invoice_unique_code');
        $countryId = $request->input('invoice_country_id');
        $companyTypeId = $request->input('company_type_id');
        if ($countryId == Country::COUNTRY_ITALY && $companyTypeId == CompanyType::TAX_VAT) {
            if (!$invoiceUniqueCode) {
                $invoiceUniqueCode = '0000000';
            }
        }

        $user = auth()->user();
        $billingModel = $user->billingInfo ?? (new UserBillingInfo());

        $billingModel->user_id = auth()->id();
        $billingModel->company_name = $request->input('company_name');
        $billingModel->invoice_country_id = $countryId;
        $billingModel->company_type_id = $request->input('company_type_id');
        $billingModel->billing_addr = $request->input('billing_addr');
        $billingModel->invoice_city = $request->input('invoice_city');
        $billingModel->invoice_vat_id = $request->input('invoice_vat_id');
        $billingModel->invoice_unique_code = $invoiceUniqueCode;
        $billingModel->invoice_pec = $request->input('invoice_pec');
        $billingModel->billing_zip_code = $request->input('billing_zip_code');
        $billingModel->save();
    }
}
