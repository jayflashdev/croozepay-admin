<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Bulksms;
use App\Models\DataPin;
use App\Models\Deposit;
use App\Models\EduTrx;
use App\Models\Mdeposit;
use App\Models\NetworkTrx;
use App\Models\RechargePin;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function deposits()
    {
        $deposits = Deposit::orderByDesc('id')->paginate(200);

        return \view('admin.reports.deposits', compact('deposits'));
    }

    public function manual_deposits()
    {
        $deposits = Mdeposit::orderByDesc('id')->paginate(200);

        return \view('admin.reports.mdeposits', compact('deposits'));
    }

    public function manual_deposits_reject($id)
    {
        $mpayment = Mdeposit::findOrFail($id);
        $mpayment->status = 3;
        $mpayment->save();

        return back()->withSuccess('Payment has been rejected and deleted');

    }

    public function manual_deposits_delete($id)
    {
        $mpayment = Mdeposit::findOrFail($id);
        $mpayment->status = 3;
        $mpayment->save();

        return back()->withSuccess('Payment has been rejected and deleted');

    }

    public function manual_deposits_pay($id)
    {
        $mpayment = Mdeposit::findOrFail($id);
        $user = $mpayment->user;
        $mpayment->status = 1;
        $mpayment->save();
        $final = $mpayment['amount'] - sys_setting('bank_fee');

        // create deposit
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->type = 'credit'; // 1- credit, 2- debit, 3-others
        $transaction->code = getTrans('DEPOSIT');
        $transaction->message = $mpayment->message;
        $transaction->amount = $final;
        $transaction->status = 'successful';
        $transaction->charge = sys_setting('bank_fee');
        $transaction->service = 'deposit'; // bills
        $transaction->old_balance = $user->balance;
        $transaction->new_balance = $user->balance + $final;
        $transaction->session_id = generateSessionId();
        $transaction->image = static_asset('images/received.png');
        $transaction->title = 'New Deposit from Bank';
        $transaction->save();
        creditUser($user, $final);
        $user->save();
        // create deposit
        $deposit = new Deposit();
        $deposit->user_id = $user->id;
        $deposit->type = 'manual';
        $deposit->gateway = 'manual';
        $deposit->trx = $transaction['code'];
        $deposit->message = $transaction['message'];
        $deposit->amount = $final;
        $deposit->status = 1;
        $deposit->save();

        // send trxn email
        if (\sys_setting('trx_email') == 1 && $user->email_notify == 1) {
            \send_emails($user->email, 'DEPOSIT_EMAIL',
                [
                    'username' => $user['username'],
                    'amount' => \format_price($final),
                    'method' => 'manual',
                    'date' => $deposit->created_at,
                ]);
        }

        return back()->withSuccess('Payment Approved Successfully');

    }

    public function transactions(Request $request)
    {
        $services = Transaction::select('service')->distinct()->orderBy('service')->get();

        $query = Transaction::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by service
        if ($request->filled('service')) {
            $query->where('service', $request->service);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $transactions = $query->orderByDesc('updated_at')->paginate(50);

        return \view('admin.reports.transaction', compact('transactions', 'services'));
    }

    public function approve_transactions($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->status = 'successful';
        $transaction->save();
        // return $transaction;

        return back()->withSuccess('Transaction Approved Successfully.');
    }

    public function reverse_transactions($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->status = 'reversed';
        $transaction->new_balance = $transaction->old_balance;
        $transaction->title = 'Reversed '.$transaction->title;
        $transaction->save();

        // Add User Balance
        $user = $transaction->user;
        creditUser($user, $transaction['amount']);
        $user->save();

        // create transaction
        return back()->withSuccess('Transaction Reversed Successfully.');
    }

    public function cancel_transactions($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->status = 'failed';
        $transaction->new_balance = $transaction->old_balance;
        $transaction->save();

        // Add User Balance
        $user = $transaction->user;
        creditUser($user, $transaction['amount']);
        $user->save();

        // create transaction
        return back()->withSuccess('Transaction cancelled Successfully.');
    }

    // sales
    public function airtime_sales()
    {
        $trx = Transaction::whereService('airtime')->orderByDesc('id')->paginate(200);

        return view('admin.sales.airtime', compact('trx'));
    }

    public function data_sales()
    {
        $trx = Transaction::whereService('data')->orderByDesc('id')->paginate(200);

        return view('admin.sales.data', compact('trx'));
    }

    public function airtime_swap()
    {
        $trx = NetworkTrx::whereType(3)->orderByDesc('id')->paginate(200);

        return view('admin.sales.swap', compact('trx'));
    }

    public function airtime_swap_delete($id)
    {
        $trx = NetworkTrx::findOrFail($id);
        $trx->status = 3;
        $trx->save();

        return back()->withSuccess('Airtime swap cancelled successfully');
    }

    public function airtime_swap_approve($id)
    {
        $trx = NetworkTrx::findOrFail($id);

        if ($trx->status == 1 || $trx->status == 3) {
            return back()->withError('Something went wrong');
        }
        $trx->status = 1;
        $trx->save();
        $user = $trx->user;
        $fee = $trx->amount - $trx->charge;
        // create transaction
        $trans = new Transaction();
        $trans->user_id = $trx->user->id;
        $trans->type = 1; // 1- credit, 2- deit, 3-others
        $trans->code = getTrxcode(14);
        $trans->message = $trx->name;
        $trans->amount = $trx->amount - $trx->charge;
        $trans->status = 1;
        $trans->charge = $trx->charge;
        $trans->service = 3; // bills
        $trans->old_balance = $user->balance;
        $trans->new_balance = $user->balance + $fee;
        $trans->save();
        // add user balance
        $user->balance = $user->balance + $fee;
        $user->save();

        // send trxn email
        if (\sys_setting('trx_email') == 1 && $user->email_notify == 1) {
            \send_emails($user->email, 'TRX_EMAIL',
                [
                    'username' => $user['username'],
                    'code' => $trans->code,
                    'trx_details' => $trans->message,
                    'trx_type' => trans_type2($trans->type),
                    'amount' => format_price($trans['amount']),
                    'date' => $trans->updated_at,
                ]);
        }

        return back()->withSuccess('Airtime swap was successfully');
    }

    public function power_sales()
    {
        $trx = Transaction::whereService('electricity')->orderByDesc('id')->paginate(200);

        return view('admin.sales.power', compact('trx'));
    }

    public function cable_sales()
    {
        $trx = Transaction::whereService('cable')->orderByDesc('id')->paginate(200);

        return view('admin.sales.cable', compact('trx'));
    }

    public function education_sales()
    {
        $trx = EduTrx::orderByDesc('id')->paginate(200);

        return view('admin.sales.education', compact('trx'));
    }

    public function datacard()
    {
        $trx = DataPin::orderByDesc('id')->paginate(200);

        return view('admin.sales.datacard', compact('trx'));
    }

    public function voucher_pins()
    {
        $trx = RechargePin::orderByDesc('id')->paginate(200);

        return view('admin.sales.vouchers', compact('trx'));
    }

    public function bulksms()
    {
        $trx = Bulksms::orderByDesc('updated_at')->paginate(200);

        return view('admin.sales.bulksms', compact('trx'));
    }

    public function bet()
    {
        $trx = Transaction::whereService('betting')->orderByDesc('updated_at')->paginate(200);

        return view('admin.sales.bet', compact('trx'));
    }
}
