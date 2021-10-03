<script src="{{ userAsset('libraries/input-tel/js/intlTelInput-jquery.min.js') }}"></script>
<script src="{{ userAsset('libraries/input-tel/langs/' . app()->getLocale() . '.js') }}"></script>
<script>
$(function() {
	$('#phone').intlTelInput({
		nationalMode: false,
		hiddenInput: window.telHiddenInputName || "phone",
		autoHideDialCode: false,
		separateDialCode: true,
		utilsScript: '{{ userAsset('libraries/input-tel/js/utils.js') }}',
		preferredCountries: [
            'it',
            'es',
            'gb'
		],
		initialCountry: "it",
		localizedCountries: window.telInputContries
	});
});
</script>