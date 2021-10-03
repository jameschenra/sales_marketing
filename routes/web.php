<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\BlogController;
use App\Http\Controllers\User\BookController;
use App\Http\Controllers\User\CityController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\PostController;
use App\Http\Controllers\User\InviteController;
use App\Http\Controllers\User\OfficeController;
use App\Http\Controllers\User\RegionController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\BalanceController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ServiceController;
use App\Http\Controllers\User\LanguageController;
use App\Http\Controllers\User\ContactUsController;
use App\Http\Controllers\User\FavouriteController;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\ProfessionalController;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\CustomerOrderController;
use App\Http\Controllers\User\Auth\VerificationController;
use App\Http\Controllers\User\Auth\ResetPasswordController;
use App\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\SEOController as AdminSEOController;
use App\Http\Controllers\Admin\HelpController as AdminHelpController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OfferController as AdminOfferController;
use App\Http\Controllers\Admin\TermsController as AdminTermsController;
use App\Http\Controllers\Admin\OfficeController as AdminOfficeController;
use App\Http\Controllers\Admin\PolicyController as AdminPolicyController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\LoyaltyController as AdminLoyaltyController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\HelpTypeController as AdminHelpTypeController;
use App\Http\Controllers\Admin\HowItWorkController as AdminHowItWorkController;
use App\Http\Controllers\Admin\StatisticController as AdminStatisticController;
use App\Http\Controllers\Admin\Auth\RegisterController as AdminRegisterController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\PostCategoryController as AdminPostCategoryController;
use App\Http\Controllers\Admin\ProfCategoryController as AdminProfCategoryController;
use App\Http\Controllers\Admin\ProfSubCategoryController as AdminProfSubCategoryController;
use App\Http\Controllers\Admin\ServiceCategoryController as AdminServiceCategoryController;
use App\Http\Controllers\Admin\ServiceSubCategoryController as AdminServiceSubCategoryController;
use App\Http\Controllers\Admin\WorldofprofessionController as AdminWorldofprofessionController;

/*
|--------------------------------------------------------------------------
| Root URLs
|--------------------------------------------------------------------------
 */

Route::get('/', 'User\HomeController@index')->name('home');
Route::get('/test', 'TestController@test');
Route::get('/delete-book/{user_id?}', 'TestController@removeAllBooks');

Route::get('locale/{locale}', [LanguageController::class, 'setLanguage']);

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
 */

Route::group(['namespace' => 'User', 'as' => 'user.'], function () {

    // ------- Authentication ----------
    Route::group([], function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('auth.showLogin');
        Route::post('/login', [LoginController::class, 'login'])->name('auth.login');
        Route::get('/signup', [RegisterController::class, 'showRegistrationForm'])->name('auth.showSignup');
        Route::post('/signup-professional', [RegisterController::class, 'registerProfessional'])->name('auth.registerProfessional');
        Route::post('/signup', [RegisterController::class, 'register'])->name('auth.signup');

        Route::post('/logout', [LoginController::class, 'logout'])->name('auth.logout');

        Route::get('/email-verify/{token}', [VerificationController::class, 'verifyEmail'])->name('verify-email');
        Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('forgot-password.showLinkRequestForm');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password.sendResetLinkEmail');
        Route::get('/forgot-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('forgot-password.showResetForm');
        Route::post('/forgot-password-process', [ResetPasswordController::class, 'resetPassword'])->name('forgot-password.reset');
    });

    Route::get('help', [HomeController::class, 'help'])->name('help');
    Route::get('privacy-policy', [HomeController::class, 'showPrivacyPolicy'])->name('privacy');
    Route::get('contact-us', [ContactUsController::class, 'showContactUs'])->name('contact-us');
    Route::post('contact-us', [ContactUsController::class, 'sendContactRequest'])->name('contact-us.send');
    Route::get('how-it-works', [HomeController::class, 'howItWorks'])->name('howitworks');
    Route::get('terms-and-condition', [HomeController::class, 'terms'])->name('terms');
    Route::get('cookie-policy', [HomeController::class, 'cookiePolicy'])->name('cookie-policy');

    Route::group(['prefix' => 'blog'], function () {
        Route::get('/', [BlogController::class, 'search'])->name('blog.index');
        Route::get('/search', [BlogController::class, 'search'])->name('blog.search');
        Route::get('/category/{slug}', [BlogController::class, 'showByCategory'])->name('blog.category');
        Route::get('/author/{slug}', [BlogController::class, 'showByAuthor'])->name('blog.author');
        Route::get('/{slug}', [BlogController::class, 'detail'])->name('blog.detail');
    });

    Route::group(['prefix' => 'world-of-professions'], function () {
        Route::get('/', [BlogController::class, 'allWorldOfProfession'])->name('blog.worldprofession.index');
        Route::get('/{slug}', [BlogController::class, 'detailWorldOfProfession'])->name('blog.worldprofession.detail');
    });
    
    Route::group([], function () {
        Route::post('/invite', [InviteController::class, 'invite'])->name('invite');
        Route::get('/invite-signup/{sender}/{email}/{accept}', [InviteController::class, 'inviteSignup'])->name('invite.signup');
    });

    Route::get('professionals/search/{category_name?}/{profession_name?}', [
        ProfessionalController::class, 'search',
    ])->name('professionals.search');
    Route::get('professionals/detail/{slug}', [ProfileController::class, 'detailProfile'])->name('professionals.detail');
    Route::get('professionals/how-it-works', [HomeController::class, 'professionalHowItWorks'])->name('professionals.howitworks');

    Route::get('services/{category_name?}/{sub_category_name?}', [ServiceController::class, 'search'])->name('services.search');

    Route::get('booking/service/{slug}', [ServiceController::class, 'showBooking'])->name('service.show-booking');
    Route::post('booking/books-left', [BookingController::class, 'booksLeftCount'])->name('booking.books-left');
    Route::post('booking/do-purchase', [BookingController::class, 'doPurchase'])->name('booking.purchase');
    Route::any('payment/success', [BookingController::class, 'paymentSuccess'])->name('booking.payment.success');
    Route::any('payment/failed', [BookingController::class, 'paymentFailed'])->name('booking.payment.failed');

    Route::any('credit-payment/success', [CustomerOrderController::class, 'paymentSuccess'])->name('credit.payment.success');
    Route::any('credit-payment/failed', [CustomerOrderController::class, 'paymentFailed'])->name('credit.payment.failed');

    Route::get('region/all', [RegionController::class, 'getAll'])->name('region.all');
    Route::get('city/all', [CityController::class, 'getAll'])->name('city.all');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/complete-profile/{step?}', [ProfileController::class, 'profileWizard'])
            ->middleware('profile.check_step')
            ->name('profile.wizard');

        Route::group(['prefix' => 'user'], function () {
            Route::get('settings', [ProfileController::class, 'showSetting'])->name('settings');
            Route::post('settings', [ProfileController::class, 'updateSetting'])->name('settings.update');
            Route::get('settings/notification', [NotificationController::class, 'index'])->name('settings.notify');
            Route::post('settings/notification', [NotificationController::class, 'deletedNotification'])->name('settings.notify.update');

            Route::get('balance', [BalanceController::class, 'index'])->name('balance.show');
            Route::get('balance/invoice/{id}', [BalanceController::class, 'download'])->name('balance.download_invoice');
            Route::post('balance/charge', [BalanceController::class, 'doRecharge'])->name('balance.do-recharge');
            Route::any('balance/charge/success', [BalanceController::class, 'confirmRecharge']);
            Route::any('balance/charge/failed', [BalanceController::class, 'chargeFailed']);
            Route::post('balance/withdraw', [BalanceController::class, 'withdraw'])->name('balance.withdraw');
            Route::post('balance/withdraw-2fa-request', [BalanceController::class, 'twoFactorRequest'])->name('balance.withdraw_2fa_request');
            Route::post('balance/withdraw-2fa-check', [BalanceController::class, 'twoFactorCheck'])->name('balance.withdraw_2fa_check');
        });

        Route::group(['prefix' => 'profile'], function () {
            Route::get('edit', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::post('store', [ProfileController::class, 'store'])->name('profile.store');
            Route::post('upload/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
            Route::get('billing/detail', [ProfileController::class, 'billingDetail'])->name('profile.billing.detail');
            Route::post('billing/store', [ProfileController::class, 'storeBilling'])->name('profile.billing.store');
            Route::get('change-password', [ProfileController::class, 'showChangePassword'])->name('profile.password.show');
            Route::post('change-password', [ProfileController::class, 'changePassword'])->name('profile.password.change');
        });

        Route::group(['prefix' => 'office'], function () {
            Route::get('my-list', [OfficeController::class, 'list'])->middleware('profile.check_step')->name('office.mylist');
            Route::get('create', [OfficeController::class, 'create'])->middleware('profile.check_step')->name('office.create');
            Route::get('edit/{id}', [OfficeController::class, 'edit'])->middleware('profile.check_step')->name('office.edit');
            Route::post('store', [OfficeController::class, 'store'])->name('office.store');
        });

        Route::group(['prefix' => 'service'], function () {
            Route::get('my-list', [ServiceController::class, 'myList'])->middleware('profile.check_step')->name('service.mylist');
            Route::get('create', [ServiceController::class, 'create'])->middleware('profile.check_step')->name('service.create');
            Route::post('store', [ServiceController::class, 'store'])->name('service.store');
            Route::get('edit/{id}', [ServiceController::class, 'edit'])->middleware('profile.check_step')->name('service.edit');
            Route::put('update', [ServiceController::class, 'update'])->name('service.update');
            Route::post('upload/photo', [ServiceController::class, 'uploadPhoto'])->name('service.photo.upload');
            Route::post('quote/store', [ServiceController::class, 'quoteStore'])->name('service.quote.store');

            Route::get('sub-category/list/{catId?}', 'ServiceController@listSubCategoryByCatId')->name('service.subCat.listByCat');

            Route::get('available-hours', [BookingController::class, 'availableHours'])->name('service.availableHours');
        });

        Route::group(['prefix' => 'booking'], function () {
            Route::post('by-office', [BookingController::class, 'bookingByOffice'])->name('booking.by-office');
            Route::post('by-free', [BookingController::class, 'bookingByFree'])->name('booking.by-free');
            Route::post('check-auth', [BookingController::class, 'checkAuth'])->name('check-auth');
        });

        Route::group(['prefix' => 'rate-review'], function () {
            Route::get('/{token}', [ReviewController::class, 'create'])->name('review.create');
            Route::post('/store', [ReviewController::class, 'store'])->name('review.store');
            Route::get('/view/{id}', [ReviewController::class, 'view'])->name('review.view');
        });

        Route::group(['prefix' => 'customer-orders'], function () {
            Route::get('/', [CustomerOrderController::class, 'index'])->name('orders.index');
            Route::get('view/{id}', [CustomerOrderController::class, 'view'])->name('orders.view');
            Route::post('cancel', [CustomerOrderController::class, 'cancelOrder'])->name('orders.cancel');
            Route::post('accept', [CustomerOrderController::class, 'acceptOrder'])->name('orders.accept');
            Route::post('send-message', [CustomerOrderController::class, 'sendMessage'])->name('orders.send-message');
            Route::post('request-extend', [CustomerOrderController::class, 'requestExtend'])->name('orders.request-extend');
            Route::post('accept-modify', [CustomerOrderController::class, 'acceptModify'])->name('orders.accept-modify');
            Route::post('accept-modify-extend', [CustomerOrderController::class, 'acceptModifyExtend'])->name('orders.accept-modify-extend');
        });

        Route::group(['prefix' => 'my-purchases'], function () {
            Route::get('/', [BookController::class, 'list'])->name('book');
            Route::get('/detail/{id}', [BookController::class, 'detail'])->name('book.detail');
            Route::post('cancel', [BookController::class, 'cancelBook'])->name('book.cancel');
            Route::post('send-message', [BookController::class, 'sendMessage'])->name('book.send-message');
            Route::post('accept-result', [BookController::class, 'acceptResult'])->name('book.accept-result');
            Route::post('request-modify', [BookController::class, 'requestModification'])->name('book.request-modify');
            Route::post('accept-extend', [BookController::class, 'acceptExtendDeliveryDate'])->name('book.accept-extend');
            Route::post('cancel-extend', [BookController::class, 'cancelExtendDeliveryDate'])->name('book.cancel-extend');
        });

        Route::group(['prefix' => 'favorites'], function () {
            Route::get('/', [FavouriteController::class, 'index'])->name('favourite.index');
            Route::post('add', [FavouriteController::class, 'store']);
            Route::post('delete/{id}', [FavouriteController::class, 'delete']);
        });

        Route::group(['prefix' => 'post'], function () {
            Route::get('/', [PostController::class, 'myList'])->name('post.mylist');
            Route::get('create', [PostController::class, 'create'])->name('post.create');
            Route::get('edit/{id}', [PostController::class, 'edit'])->name('post.edit');
            Route::get('detail/{slug}', [PostController::class, 'detail'])->name('post.detail');
            Route::post('store', [PostController::class, 'store'])->name('post.store');
            Route::get('delete/{id}', [PostController::class, 'delete'])->name('post.delete');
        });
    });
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    // ------- Authentication ----------
    Route::group(['as' => 'auth.'], function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('showLogin');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login');
        Route::get('/signup', [AdminRegisterController::class, 'showRegistrationForm'])->name('showSignup');
        Route::post('/signup', [AdminRegisterController::class, 'register'])->name('signup');
        Route::get('/logout', [AdminLoginController::class, 'logout'])->name('logout');
    });

    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/', function () {return redirect()->route('admin.home');});
        Route::get('/dashboard', [AdminHomeController::class, 'home'])->name('home');

        Route::group(['prefix' => 'seo'], function () {
            Route::get('/', [AdminSEOController::class, 'index'])->name('seo');
            Route::get('/{name}', [AdminSEOController::class, 'view'])->name('seo.view');
            Route::post('/', [AdminSEOController::class, 'store'])->name('seo.store');
        });

        Route::group(['prefix' => 'company'], function () {
            Route::get('/', [AdminCompanyController::class, 'index'])->name('company.index');
            Route::get('create', [AdminCompanyController::class, 'create'])->name('company.create');
            Route::get('edit/{id}', [AdminCompanyController::class, 'edit'])->name('company.edit');
            Route::get('unblock/{id}', [AdminCompanyController::class, 'unblock'])->name('company.unblock');
            Route::get('block/{id}', [AdminCompanyController::class, 'block'])->name('company.block');
            Route::post('store', [AdminCompanyController::class, 'store'])->name('company.store');
            Route::post('verify', [AdminCompanyController::class, 'verify'])->name('company.verify');
            Route::get('delete/{id}', [AdminCompanyController::class, 'delete'])->name('company.delete');
            Route::get('feedback/{id}', [AdminCompanyController::class, 'feedback'])->name('company.feedback');
        });

        Route::group(['prefix' => 'user'], function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('user.index');
            Route::get('create', [AdminUserController::class, 'create'])->name('user.create');
            Route::get('edit/{id}', [AdminUserController::class, 'edit'])->name('user.edit');
            Route::get('unblock/{id}', [AdminUserController::class, 'unblock'])->name('user.unblock');
            Route::get('block/{id}', [AdminUserController::class, 'block'])->name('user.block');
            Route::post('store', [AdminUserController::class, 'store'])->name('user.store');
            Route::get('delete/{id}', [AdminUserController::class, 'delete'])->name('user.delete');
        });

        // CRUDs
        $crudClasses = [
            [
                'url' => 'prof-category',
                'alias' => 'profcat',
                'class' => AdminProfCategoryController::class,
            ],
            [
                'url' => 'prof-sub-category',
                'alias' => 'prof',
                'class' => AdminProfSubCategoryController::class,
            ],
            [
                'url' => 'service',
                'alias' => 'service',
                'class' => AdminServiceController::class,
            ],
            [
                'url' => 'service-category',
                'alias' => 'svccat',
                'class' => AdminServiceCategoryController::class,
            ],
            [
                'url' => 'service-sub-category',
                'alias' => 'svcsubcat',
                'class' => AdminServiceSubCategoryController::class,
            ],
            [
                'url' => 'post',
                'alias' => 'post',
                'class' => AdminPostController::class,
            ],
            [
                'url' => 'post-category',
                'alias' => 'postcategory',
                'class' => AdminPostCategoryController::class,
            ],
            [
                'url' => 'policy',
                'alias' => 'policy',
                'class' => AdminPolicyController::class,
            ],
            [
                'url' => 'terms',
                'alias' => 'terms',
                'class' => AdminTermsController::class,
            ],
            [
                'url' => 'help',
                'alias' => 'help',
                'class' => AdminHelpController::class,
            ],
            [
                'url' => 'help-type',
                'alias' => 'helptype',
                'class' => AdminHelpTypeController::class,
            ],
            [
                'url' => 'how-it-works',
                'alias' => 'howitworks',
                'class' => AdminHowItWorkController::class,
            ],
            [
                'url' => 'world-of-profession',
                'alias' => 'worldofprofession',
                'class' => AdminWorldofprofessionController::class,
            ],
            [
                'url' => 'offer',
                'alias' => 'offer',
                'class' => AdminOfferController::class,
            ],
            [
                'url' => 'loyalty',
                'alias' => 'loyalty',
                'class' => AdminLoyaltyController::class,
            ],
            [
                'url' => 'office',
                'alias' => 'office',
                'class' => AdminOfficeController::class,
            ],
            [
                'url' => 'plan',
                'alias' => 'plan',
                'class' => AdminPlanController::class,
            ],
            [
                'url' => 'review',
                'alias' => 'review',
                'class' => AdminReviewController::class,
            ],
        ];

        foreach ($crudClasses as $crudClass) {
            Route::group(['prefix' => $crudClass['url']], function () use ($crudClass) {
                Route::get('/', [$crudClass['class'], 'index'])->name($crudClass['alias'] . '.index');
                Route::get('create', [$crudClass['class'], 'create'])->name($crudClass['alias'] . '.create');
                Route::get('edit/{id}', [$crudClass['class'], 'edit'])->name($crudClass['alias'] . '.edit');
                Route::post('store', [$crudClass['class'], 'store'])->name($crudClass['alias'] . '.store');
                Route::get('delete/{id}', [$crudClass['class'], 'delete'])->name($crudClass['alias'] . '.delete');
            });
        }

        Route::get('service/active/{service_id}', [AdminServiceController::class, 'active'])->name('service.active');

        Route::group(['prefix' => 'transaction'], function () {
            Route::get('/', [AdminTransactionController::class, 'index'])->name('transactions.index');
            Route::post('refund', [AdminTransactionController::class, 'refund'])->name('transactions.refund');
        });

        Route::group(['prefix' => 'message'], function () {
            Route::get('/professional', [AdminMessageController::class, 'professional'])->name('message.profession.index');
            Route::get('/general', [AdminMessageController::class, 'general'])->name('message.general.index');
            Route::post('/send/{type}', [AdminMessageController::class, 'send'])->name('message.send');
        });

        Route::group(['prefix' => 'app-setting'], function () {
            Route::get('/', [AdminSettingController::class, 'edit'])->name('setting.edit');
            Route::post('/', [AdminSettingController::class, 'store'])->name('setting.store');
        });

        Route::get('statistic', [AdminStatisticController::class, 'index'])->name('statistic.index');
        Route::get('statistic/{section}', [AdminStatisticController::class, 'section'])->name('statistic.section');
    });
});
