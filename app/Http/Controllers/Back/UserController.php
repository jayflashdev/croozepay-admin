<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Users
    public function users()
    {
        $users = User::whereBlocked(0)->orderByDesc('id')->get();
        return view('admin.user.index', compact('users'));
    }
    public function user_settings()
    {
       return view('admin.user.settings');
    }
    public function ban_user($id,$status)
    {
        $user = User::findorFail($id);
        $user->suspend = $status;
        $user->save();
        return redirect()->back()->withSuccess(__('User updated Successfully.'));
    }
    public function verify_user($id)
    {
        $user = User::findorFail($id);
        $user->email_verified_at = now();
        $user->email_verify = 1;
        $user->save();
        return redirect()->back()->withSuccess(__('User Email Verified Successfully.'));
    }
    public function delete_user($id)
    {
        $user = User::findorFail($id);
        $user->blocked = 1;
        $user->save();
        return redirect()->back()->withSuccess(__('User deleted Successfully.'));
    }
    function view_user ($id){
        $user = User::findorFail($id);
        return view('admin.user.view', compact('user'));
    }
    function update_user_balance ($id, Request $request){
        $this->validate($request, [
            'amount' => 'required|numeric|min:1',
            'type' => 'required',
            'message' => 'required'
        ]);
        $amount = $request->amount;
        $user = User::findorFail($id);
        if($request->type == 1){
            // Create a new transaction
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->type = "credit"; // 1- credit, 2- debit, 3-others
            $transaction->code = getTrans('DEPOSIT');
            $transaction->message = $request->message;
            $transaction->amount = $request->amount;
            $transaction->status = 'successful';
            $transaction->charge = 0;
            $transaction->service = "deposit"; // bills
            $transaction->old_balance = $user->balance ;
            $transaction->new_balance = $user->balance + $amount;
            $transaction->session_id = generateSessionId();
            $transaction->image = static_asset('images/received.png');
            $transaction->title = "Credit Transaction";
            $transaction->save();
            creditUser($user, $amount);
            $user->save();
            // create deposit
            $deposit = new Deposit();
            $deposit->user_id = $user->id;
            $deposit->type = 'manual';
            $deposit->gateway = "manual";
            $deposit->trx = $transaction['code'];
            $deposit->message = $transaction['message'];
            $deposit->amount = $amount;
            $deposit->status = 1;
            $deposit->save();
        } else{
            // Create a new transaction
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->type = "debit"; // 1- credit, 2- debit, 3-others
            $transaction->code = getTrans('DEBIT');
            $transaction->message = $request->message;
            $transaction->amount = $request->amount;
            $transaction->status = 'successful';
            $transaction->charge = 0;
            $transaction->service = "system"; // bills
            $transaction->old_balance = $user->balance ;
            $transaction->new_balance = $user->balance - $amount;
            $transaction->session_id = generateSessionId();
            $transaction->image = static_asset('images/spent.png');
            $transaction->title = "Debit Transaction";
            $transaction->save();
            debitUser($user, $amount);
            $user->save();
        }
        // Optionally send transaction email
        if (\sys_setting('trx_email') == 1 && $user->email_notify == 1) {
            \send_emails($user->email, 'TRX_EMAIL', [
                'username' => $user->username,
                'code' => $transaction->code,
                'trx_details' => $transaction->message,
                'trx_type' => trans_type2($transaction->type),
                'amount' => format_price($transaction->amount),
                'date' => $transaction->created_at
            ]);
        }
        return redirect()->back()->withSuccess(__('User updated Successfully.'));
    }
    function update_user ($id, Request $request){
        $user = User::findorFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->type = $request->type;
        $user->email_verify = $request->email_verify ?? 0;
        if($request->password != null){
            $user->password = Hash::make($request->password);
        }if($request->trxpin != null){
            $user->trxpin = Hash::make($request->trxpin);
        }
        $user->save();
        return redirect()->back()->withSuccess(__('User updated Successfully.'));
    }
    function user_referral ($id){
        $user = User::findorFail($id);
        $referrals = User::orderByDesc('id')->whereRefId($user->id)->get();
        return view('admin.user.referral', compact('referrals','user'));
    }
    function user_deposit ($id){
        $user = User::findorFail($id);
        $deposits = Deposit::orderByDesc('id')->whereUserId($user->id)->paginate(100);
        return view('admin.user.deposits', compact('deposits','user'));
    }
    function user_trx ($id){
        $user = User::findorFail($id);
        $trx = Transaction::orderByDesc('id')->whereUserId($user->id)->paginate(100);
        return view('admin.user.trx', compact('trx','user'));
    }

    public function userLogin($id)
    {
        $user = User::findOrFail(($id));

        auth('web')->login($user, false);

        return redirect()->route('user.dashboard');
    }
}
