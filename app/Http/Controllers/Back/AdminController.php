<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Mdeposit;
use App\Models\Message;
use App\Models\NetworkTrx;
use App\Models\Page;
use App\Models\Transaction;
use App\Models\User;
use Artisan;
use Auth;
use Cache;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Str;

class AdminController extends Controller
{
    // Dashboard
    public function index()
    {
        $monthsAgo = Carbon::now()->subMonths(4);
        $today = Carbon::now()->today();
        // Use caching for users data
        $users = Cache::remember('adminUsers', 60, function () {
            return User::where('blocked', 0)->get(['balance', 'bonus', 'name']);
        });
        // Use caching for mpayment data
        $todayTrx = Cache::remember('adminTrans', 30, function () {
            return Transaction::where('status', 'successful')
                ->whereDate('updated_at', today())
                ->sum('amount');
        });
        $datas = [
            'u_count' => $users->count(),
            'balance' => $users->sum('balance'),
            'bonus' => $users->sum('bonus'),
        ];

        return view('admin.index', compact('users', 'todayTrx', 'datas'));
    }

    public function stats()
    {
        $monthsAgo = Carbon::now()->subMonths(6);

        // Use caching for users data
        $users = Cache::remember('adminUsers', 60, function () {
            return User::where('blocked', 0)->get(['balance', 'bonus', 'name']);
        });

        // Use caching for deposit data
        $deposit = Cache::remember('adminDeposit', 60, function () use ($monthsAgo) {
            return Deposit::where('status', 1)
                ->where('created_at', '>=', $monthsAgo)
                ->sum('amount');
        });

        // Use caching for mpayment data
        $mpayment = Cache::remember('adminMpayment', 60, function () use ($monthsAgo) {
            return Mdeposit::where('status', 1)
                ->where('created_at', '>=', $monthsAgo)
                ->sum('amount');
        });

        // Use caching for mpayment2 data
        $mpayment2 = Cache::remember('adminMpayment2', 60, function () use ($monthsAgo) {
            return Mdeposit::where('status', 2)
                ->where('created_at', '>=', $monthsAgo)
                ->sum('amount');
        });

        // Use caching for network transactions data
        $networkTrx = Cache::remember('adminNetworkTrx', 60, function () use ($monthsAgo) {
            return NetworkTrx::where('status', 1)
                ->where('created_at', '>=', $monthsAgo)
                ->get(['type', 'amount']);
        });

        // Use caching for transactions data
        $transactions = Cache::remember('adminTransaction', 60, function () use ($monthsAgo) {
            return Transaction::where('created_at', '>=', $monthsAgo)
                ->get(['status', 'amount', 'type', 'profit', 'created_at', 'service']);
        });

        // Data aggregation
        $datas = [
            'u_count' => $users->count(),
            'balance' => $users->sum('balance'),
            'bonus' => $users->sum('bonus'),
            'c_trx' => $transactions->where('type', 'credit')->where('status', 'successful')->sum('amount'),
            'd_trx' => $transactions->where('type', 'debit')->where('status', 'successful')->sum('amount'),
            'p_today' => $transactions->where('status', 'successful')->where('created_at', '>=', Carbon::today())->where('created_at', '<', Carbon::tomorrow())->sum('profit'),
            'p_all' => $transactions->where('status', 'successful')->sum('profit'),
        ];

        return view('admin.stats', compact('deposit', 'mpayment', 'mpayment2', 'networkTrx', 'datas', 'transactions'));
    }

    public function balance()
    {
        return view('admin.balance');
    }

    public function login()
    {
        // check if admin loggedin and show login page
        return view('admin.login');
    }

    // Profile
    public function profile()
    {
        return view('admin.profile');
    }

    public function update_profile(Request $request)
    {

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->email_verify = $request->email_verify ?? 0;

        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return back()->withSuccess(__('Profile Updated Successfully.'));
    }

    // Pages
    public function pages()
    {
        $pages = Page::all();

        return view('admin.pages.index', compact('pages'));
    }

    public function create_page()
    {
        return view('admin.pages.create');
    }

    public function store_page(Request $request)
    {
        $page = new Page();
        $page->title = $request->title;
        $page->content = $request->content;
        $page->type = 'custom';
        $page->slug = Str::slug($request->slug);
        $page->save();

        return redirect()->route('admin.pages.index')->withSuccess(__('Page Created Successfully'));
    }

    public function edit_page($id)
    {
        $page = Page::findorFail($id);

        return view('admin.pages.edit', compact('page'));
    }

    public function update_page($id, Request $request)
    {
        if (Page::where('id', '!=', $id)->where('slug', $request->slug)->first() == null) {
            $page = Page::findorFail($id);
            $page->title = $request->title;
            $page->content = $request->content;
            $page->slug = Str::slug($request->slug);
            $page->save();

            return redirect()->route('admin.pages.index')->withSuccess(__('Page updated successfully'));
        }

        return redirect()->back()->withError(__('Slug has been used. Try again'));
    }

    public function delete_page($id)
    {
        $page = Page::findOrFail($id);

        if ($page->type != 'custom') {
            return back()->withError('Something Went Wrong');
        }
        $page->delete();

        return back()->withSuccess('Page deleted successfully');
    }

    // Notification messages
    public function notifications()
    {
        $notifications = Cache::remember('adminNotifications', 160, function () {
            return Message::orderByDesc('id')->get();
        });

        return view('admin.notify', compact('notifications'));
    }

    public function storeNotification(Request $request)
    {
        $notification = new Message();
        $notification->title = $request->title;
        $notification->message = $request->message;
        $notification->save();

        return redirect()->route('admin.notifications.index')->withSuccess(__('Notification Created Successfully'));
    }

    public function deleteNotification($id)
    {
        $notification = Message::find($id);

        if ($notification) {
            $notification->delete();
            \Cache::forget('adminNotifications');
        }

        return back()->withSuccess(__('Notification Deleted Successfully'));
    }

    // Clear cache
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return back()->withSuccess('Cache Cleared Successfully.');
    }

    // // restore old isers
    // function restore(Request $request){
    //     // Get old users:
    //     $oldUsers = \DB::table('old_users')->get();

    //     // Loop through old users
    //     foreach ($oldUsers as $oldUser) {
    //         // Check if a user with the same email already exists
    //         $userExist = User::where('email', $oldUser->email)->first();

    //         if ($userExist) {
    //             // Update existing user
    //             $userExist->name            = $oldUser->name;
    //             $userExist->email           = $oldUser->email;
    //             $userExist->username        = $oldUser->username;
    //             $userExist->password        = $oldUser->password; // ensure this is hashed if needed
    //             $userExist->balance         = $oldUser->balance;
    //             $userExist->user_role       = $oldUser->user_role;
    //             $userExist->address         = $oldUser->address;
    //             $userExist->bonus           = $oldUser->bonus;
    //             $userExist->type            = $oldUser->type;
    //             // Add new fields
    //             $userExist->virtual_ref     = $oldUser->virtual_ref;
    //             $userExist->virtual_banks   = $oldUser->virtual_banks;
    //             $userExist->payvessel_ref     = $oldUser->payvessel_ref;
    //             $userExist->payvessel_banks   = $oldUser->payvessel_banks;
    //             $userExist->api_key           = $oldUser->api_key;
    //             $userExist->save();
    //         } else {
    //             // Create new user
    //             $newUser = new User();
    //             $newUser->name                = $oldUser->name;
    //             $newUser->email               = $oldUser->email;
    //             $newUser->username            = $oldUser->username;
    //             $newUser->user_role           = $oldUser->user_role;
    //             $newUser->ref_id              = $oldUser->ref_id;
    //             $newUser->ref_code            = getTrxcode(8);
    //             $newUser->trxpin              = null;
    //             $newUser->phone               = $oldUser->phone;
    //             $newUser->address             = $oldUser->address;
    //             $newUser->balance             = $oldUser->balance;
    //             $newUser->password            = $oldUser->password; // ensure this is hashed if needed
    //             $newUser->bonus               = $oldUser->bonus;
    //             $newUser->type                = $oldUser->type;
    //             $newUser->email_notify        = 1;
    //             $newUser->email_verified_at   = $oldUser->email_verified_at;
    //             // Add new fields
    //             $newUser->virtual_ref   = $oldUser->virtual_ref;
    //             $newUser->virtual_banks   = $oldUser->virtual_banks;
    //             $newUser->payvessel_ref     = $oldUser->payvessel_ref;
    //             $newUser->payvessel_banks   = $oldUser->payvessel_banks;
    //             $newUser->api_key           = $oldUser->api_key;
    //             $newUser->save();
    //         }
    //     }

    //     return back()->withSuccess('Users Restored Successfully');
    // }

}
