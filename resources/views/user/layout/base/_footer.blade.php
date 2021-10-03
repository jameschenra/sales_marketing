<!-- Footer Start -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
                <a href="/" class="logo-footer">
                    <img src="{{ imageAsset('app_logos/blue-white.svg') }}" height="32" alt="">
                </a>
                <h4 class="mt-4">@lang('main.Footer_left_description')</h4>
                <ul class="list-unstyled social-icon social mb-0 mt-4">
                    <li class="list-inline-item"><a href="https://www.facebook.com/weredyofficial" target="_blank" class="rounded"><i data-feather="facebook" class="fea icon-sm fea-social"></i></a></li>
                    <li class="list-inline-item"><a href="https://www.instagram.com/weredyofficial/" target="_blank" class="rounded"><i data-feather="instagram" class="fea icon-sm fea-social"></i></a></li>
                    <li class="list-inline-item"><a href="https://twitter.com/weredyofficial" target="_blank" class="rounded"><i data-feather="twitter" class="fea icon-sm fea-social"></i></a></li>
                </ul>
                <!--end icon-->
            </div>
            <!--end col-->

            <div class="col-lg-2 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h4 class="text-light footer-head">@lang('main.about.section')</h4>
                <ul class="list-unstyled footer-list mt-4">
                    <li><a href="{{ route('user.contact-us') }}" class="text-foot"> @lang('main.Contact and Support')</a></li>
                    <li><a href="{{ route('user.help') }}" class="text-foot"> @lang('main.Help')</a></li>
                    <li><a href="{{ route('user.blog.search') }}" class="text-foot"> @lang('main.Blog')</a></li>
                    <li><a href="{{route('user.terms')}}" class="text-foot"> @lang('main.Terms and Condition')</a></li>
                    <li><a href="{{ route('user.privacy') }}" class="text-foot"> @lang('main.Privacy Policy')</a></li>
                    <li><a href="{{ route('user.cookie-policy') }}" class="text-foot"> @lang('main.footer.cookies_policy')</a></li>
                </ul>
            </div>
            <!--end col-->

            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h4 class="text-light footer-head">@lang('main.users.section')</h4>
                <ul class="list-unstyled footer-list mt-4">
                    <li><a href="{{ route('user.professionals.search') }}" class="text-foot"> @lang('main.footer.find_a_professional')</a></li>
                    <li><a href="{{ route('user.services.search') }}" class="text-foot"> @lang('main.footer.find_a_service')</a></li>
                    <li><a href="{{ route('user.howitworks') }}" class="text-foot"> @lang('main.How it works')</a></li>
                </ul>
            </div>
            <!--end col-->

            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h4 class="text-light footer-head">@lang('main.professionals.section')</h4>
                <ul class="list-unstyled footer-list mt-4">
                    <li><a href="{{ route('user.professionals.howitworks') }}" class="text-foot"> @lang('main.How it works professional')</a></li>
                    <li><a href="{{ route('user.auth.showSignup') }}" class="text-foot"> @lang('main.Signup.footer')</a></li>
                </ul>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</footer>
<!--end footer-->
<footer class="footer footer-bar">
    <div class="container text-center">
        <div class="text-sm-center">
            <p class="mb-0">@lang('Copyright') © 2014-{{date("Y")}} Weredy</p>
        </div>
    </div>
    <!--end container-->
</footer>
<!--end footer-->
<!-- Footer End -->