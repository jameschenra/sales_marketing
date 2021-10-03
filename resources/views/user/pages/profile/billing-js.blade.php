@php
    use App\Models\CompanyType;
@endphp

<script>
    $(function(){
        $('#invoice-country').on('change', onSelectCountry);
        $('#company-type-id').on('change', onSelectCompanyType);

        selectCompanyType($('#company-type-id').val());
    });

    function onSelectCountry() {
        var countryId = $(this).val();
        selectCountry(countryId);
    }

    function selectCountry(countryId) {
        if (countryId) {
            $('.detail-container').removeClass('d-none');
        } else {
            $('.detail-container').addClass('d-none');
        }

        selectCompanyType($('#company-type-id').val());
    }

    function onSelectCompanyType() {
        var companyType = $(this).val();
        selectCompanyType(companyType);
    }

    var ITALY_ID = 109;
    function selectCompanyType(companyType) {
        var countryId = $('#invoice-country').val();
        if (companyType) {
            if (companyType == "{{ CompanyType::TAX_EIN }}") {
                $('.vat-id-container label .label-text').html("{{ trans('main.EIN') }}");
                $('.vat-id-container input').attr('placeholder', "{{ trans('main.Enter EIN') }}");
            } else {
                $('.vat-id-container label .label-text').html("{{ trans('VAT ID') }}");
                $('.vat-id-container input').attr('placeholder', "{{ trans('main.Enter VAT ID') }}");
            }

            $('.vat-id-container').show();

            if (countryId == ITALY_ID && companyType == "{{ CompanyType::TAX_VAT }}") {
                $('.unique-code-container').show();
            } else {
                $('.unique-code-container').hide();
            }
        } else {
            $('.vat-id-container').hide();
            $('.unique-code-container').hide();
        }
    }
</script>