<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionOfCredit;
use App\Models\Book;
use App\Models\TransactionOfBooking;
use App\Models\UserBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index()
    {
        $page_title = trans('main.Statistic');
        $page_description = '';

        $total_balance_sum = UserBalance::sum('balance');
        $total_pending_fees_sum = TransactionOfBooking::getTotalPendingFee();
        $total_available_fees_sum = TransactionOfBooking::getTotalAvailableFee();
        $total_fees_refunded_by_site_sum = TransactionOfBooking::getRefundedBySite('available');
        $total_balance_topped_up_sum = TransactionOfCredit::has('user')->sum('amount');
        $total_balance_not_available_sum = Book::getBalanceNotAvailable();

        $connectedFees = TransactionOfBooking::where('receiver_id', '0')->where('refunded', '0')->get();
        $filteredFees = $connectedFees->filter(function ($value, $key) {
            return TransactionOfBooking::where('receiver_id', $value->sender_id)->where('id', $value->id - 1)->first();
        });
        $total_not_available_sum = $total_balance_not_available_sum->first()->total_not_available - $filteredFees->sum('amount');

        $total_available_balance_sum = $total_balance_sum - $total_not_available_sum;
        $total_refunded_by_users = Book::select(DB::raw('sum(number_of_booking*price) as refunded_by_users'))
            ->where('deleted_by', 'buyer')->get();
        $total_refunded_by_users_sum = $total_refunded_by_users->first()->refunded_by_users;

        $total_refunded_by_site = Book::select(DB::raw('sum(number_of_booking*price) as refunded_by_site'))
            ->where('deleted_by', 'seller')->get();
        $total_refunded_by_site_sum = $total_refunded_by_site->first()->refunded_by_site;
        $total_withdrawn = TransactionOfBooking::where(['sender_id' => '0', 'receiver_id' => '0'])->sum('amount');
        $total_site_earnings_sum = $total_available_fees_sum - $total_fees_refunded_by_site_sum;
        if ($total_site_earnings_sum < 0) {
            $total_site_earnings_sum = 0;
        }

        return view('admin.pages.statistic.index', compact(
            'page_title',
            'page_description',
            'total_balance_sum',
            'total_balance_topped_up_sum',
            'total_not_available_sum',
            'total_available_balance_sum',
            'total_refunded_by_users_sum',
            'total_refunded_by_site_sum',
            'total_pending_fees_sum',
            'total_fees_refunded_by_site_sum',
            'total_available_fees_sum',
            'total_withdrawn',
            'total_site_earnings_sum'
        ));
    }

    public function section($section, Request $request)
    {
        $page_title = trans('main.Statistic');
        $page_description = '';

        switch ($section) {
            case 2:
                $user_statistics = TransactionOfCredit::has('user')->paginate(PAGINATION_SIZE);
                $table_name = 'Total balance topped-up';
                $stat_id = $section;
                break;
            case 3:
                $user_statistics = Book::leftJoin('users', 'users.id', '=', 'books.user_id')
                    ->select(DB::raw('`users`.`name`, `users`.`email`, `books`.`created_at`, `books`.`number_of_booking`*`books`.`price` as amount'))
                    ->whereRaw('(unix_timestamp(`books`.`created_at`)+345600)>unix_timestamp()')
                    ->paginate(PAGINATION_SIZE);
                $table_name = 'Total balance not available';
                $stat_id = $section;
                break;
            case 5:
                $user_statistics = Book::leftJoin('users', 'users.id', '=', 'books.user_id')
                    ->select(DB::raw('`users`.`name`, `users`.`email`, `books`.`created_at`, `books`.`number_of_booking`*`books`.`price` as amount'))
                    ->whereRaw('`books`.`deleted_by`="buyer"')->paginate(PAGINATION_SIZE);
                $table_name = 'Total balance refunded to buyers by a cancellation from users';
                $stat_id = $section;
                break;
            case 7:
                $user_statistics = Book::leftJoin('users', 'users.id', '=', 'books.user_id')
                    ->select(DB::raw('`users`.`name`, `users`.`email`, `books`.`created_at`, `books`.`number_of_booking`*`books`.`price` as amount'))
                    ->whereRaw('`books`.`deleted_by`="seller"')->paginate(PAGINATION_SIZE);
                $table_name = 'Total balance refunded to buyers by a cancellation from site_name';
                $stat_id = $section;
                break;
            case 8:
                $user_statistics = DB::table('transactions')->join('users', 'users.id', '=', 'transactions.sender_id')
                    ->select(DB::raw('`users`.`name`, `users`.`email`, `transactions`.`created_at`, `transactions`.`amount` as amount'))
                    ->whereRaw('`transactions`.`receiver_id`=0')->paginate(PAGINATION_SIZE);
                $table_name = 'Total pending fees';
                $stat_id = $section;
                break;
            case 9:
                $user_statistics = DB::table('transactions')->join('users', 'users.id', '=', 'transactions.sender_id')->select(DB::raw('`users`.`name`, `users`.`email`, `transactions`.`created_at`, `transactions`.`amount` as amount'))
                    ->whereRaw('`transactions`.`receiver_id`=0')->whereRaw('`transactions`.`refunded`=1')->paginate(10);
                $table_name = 'Total fees refunded to sellers by site_name';
                $stat_id = $section;
                break;
            case 10:
                $user_statistics = DB::table('transactions')->join('users', 'users.id', '=', 'transactions.sender_id')->select(DB::raw('`users`.`name`, `users`.`email`, `transactions`.`created_at`, `transactions`.`amount` as amount'))
                    ->whereRaw('`transactions`.`receiver_id`=0')->whereRaw('(unix_timestamp(`transactions`.`created_at`)+345600)<unix_timestamp()')->paginate(10);
                $table_name = 'Total available fees';
                $stat_id = $section;
                break;
            case 11:
                $user_statistics = DB::table('transactions')->join('users', 'users.id', '=', 'transactions.receiver_id')->select(DB::raw('`users`.`name`, `users`.`email`, `transactions`.`created_at`, `transactions`.`amount` as amount'))
                    ->whereRaw('`transactions`.`sender_id`=0')->whereRaw('`transactions`.`receiver_id`=0')->paginate(10);
                $table_name = 'Total balance withdrawn by users';
                $stat_id = $section;
                break;
        }
        return view('admin.pages.statistic.table', compact(
            'page_title',
            'page_description',
            'user_statistics',
            'table_name',
            'stat_id'
        ));
    }
}
