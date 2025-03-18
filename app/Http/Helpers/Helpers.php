<?php

use App\Mail\MainEmail;
use App\Models\ApiSetting;
use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Models\SystemSetting;
use App\Models\User;

if (!function_exists('get_setting')) {
    function get_setting($key)
    {
        $settings = Cache::remember('Settings', 3600, function () {
            return Setting::first();
        });
        return $settings->$key ?? null;
    }
}

if (!function_exists('sys_setting')) {
    function sys_setting($key, $default = null)
    {
        $settings = Cache::remember('SystemSettings', 3600, function () {
            return SystemSetting::all();
        });
        $setting = $settings->where('name', $key)->first();

        return $setting == null ? $default : $setting->value;
    }
}
if (!function_exists('api_setting')) {
    function api_setting($key, $default = null)
    {
        $settings = ApiSetting::all();
        $setting = $settings->where('name', $key)->first();

        return $setting == null ? $default : $setting->value;
    }
}
if (! function_exists('static_asset')) {
    function static_asset($path, $secure = null)
    {
        if (php_sapi_name() == 'cli-server') {
            return app('url')->asset('assets/' . $path, $secure);
        }

        return app('url')->asset('public/assets/' . $path, $secure);
    }
}

// Return file uploaded via uploader
if (! function_exists('my_asset')) {
    function my_asset($path, $secure = null)
    {
        if (php_sapi_name() == 'cli-server') {
            return app('url')->asset('uploads/' . $path, $secure);
        }

        return app('url')->asset('public/uploads/' . $path, $secure);
    }
}

// Get a setting value by key from the settings table
if (! function_exists('get_setting')) {
    function get_setting($key = null)
    {
        $settings = Cache::get('Setting');

        if (! $settings) {
            $settings = Setting::first();
            Cache::put('Setting', $settings, 30000);
        }

        if ($key) {
            return @$settings->$key;
        }

        return $settings;
    }
}

if (! function_exists('sys_setting')) {
    function sys_setting($key, $default = null)
    {
        $settings = Cache::get('SystemSettings');

        if (! $settings) {
            $settings = SystemSetting::all();
            Cache::put('SystemSettings', $settings, 30000);
        }
        $setting = $settings->where('name', $key)->first();

        return $setting == null ? $default : $setting->value;
    }
}

function text_trim($string, $length = null)
{
    if (empty($length)) $length = 100;
    return Str::limit($string, $length, "...");
}
function show_datetime($date, $format = 'd M, Y h:ia')
{
    return \Carbon\Carbon::parse($date)->format($format);
}
function show_datetime1($date, $format = 'd M, Y h:i:sa')
{
    return \Carbon\Carbon::parse($date)->format($format);
}
function show_datetime2($date, $format = 'M jS, H:i:s')
{
    return \Carbon\Carbon::parse($date)->format($format);
}

// Transactions
function getTrans($id)
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ1234567890acdefghijklmopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 15; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $id.'_'.$randomString;
}
// random string
function getTrxcode($length)
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function getTrx($length)
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ1234567890acdefghijklmopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
// trxImage
function trxImage($image = null){
    if($image == null){
        return static_asset('images/default.png');
    }
    // If the image URL already exists, return it
    if (filter_var($image, FILTER_VALIDATE_URL)) {
        return $image;
    }
    $imagePath = public_path($image);
    if (file_exists($imagePath)) {
        return asset($image);
    }

    // If the image doesn't exist, return the default image
    return static_asset('images/default.png');

    return $image;
}
function generateSessionId() {
    $timestamp = date('ymdHis');
    $random_number = getNumber(9);
    return '100004'.$timestamp . $random_number;
}

function getNumber($length = 6)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
// short code replacer
function shortCodeReplacer($shortCode, $replace_with, $template_string)
{
    return str_replace($shortCode, $replace_with, $template_string);
}

function show_date($date, $format = 'd M, Y')
{
    return \Carbon\Carbon::parse($date)->format($format);
}

function show_time($date, $format = 'h:ia')
{
    return \Carbon\Carbon::parse($date)->format($format);
}
// Transaction type
function short_trx_type($id){
    if($id == 1){
        return "airtime";
    }elseif($id == 2){
        return "data";
    }elseif($id == 3){
        return "airtime swap";
    }elseif($id == 4){
        return "voucher pin";
    }elseif($id == 5){
        return "cable tv";
    }elseif($id == 6){
        return "education";
    }elseif($id == 7){
        return "electricity";
    }elseif($id == 8){
        return "bulk sms";
    }elseif($id == 9){
        return "wallet";
    }elseif($id == 10){
        return "referral";
    }elseif($id == 11){
        return "datacard";
    }elseif($id == 12){
        return "betting";
    } else{
        return $id;
    }
}
function trans_status($id){
    if($id == 1){
        return 'successful';
    }elseif($id == 2){
        return 'processing';
    } elseif($id == 3){
        return 'failed';
    } elseif($id == 4){
        return 'reversed';
    } else{
        return $id;
    }
}
if (! function_exists('transactionStatus')) {
    function transactionStatus($status) {
        $statusClasses = [
            'successful' => 'bg-success',
            'pending' => 'bg-warning',
            'failed' => 'bg-danger',
            'processing' => 'bg-warning',
            'reversed' => 'bg-primary',
        ];

        $badgeClass = $statusClasses[$status] ?? 'bg-secondary';
        $displayText = ucfirst($status);

        return '<span class="badge ' . $badgeClass . '">' . $displayText . '</span>';
    }
}
function trans_type($id){
    if($id == 1){
        return '<span class="badge bg-success">credit</span>';
    }elseif($id == 2){
        return '<span class="badge bg-danger">debit</span>';
    } else{
        return $id;
    }
}
function trans_type2($id){
    if($id == 1){
        return 'credit';
    }elseif($id == 2){
        return 'debit';
    } else{
        return $id;
    }
}
//formats currency
if (!function_exists('format_price')) {
    function format_price($price)
    {
        $fomated_price = number_format($price, 2);
        $currency = get_setting('currency');
        return $currency .$fomated_price;
    }
}
function sym_price($price)
{
    $fomated_price = number_format($price, 2);
    $currency = get_setting('currency_code');
    return $currency . $fomated_price;
}
function format_number($price)
{
    $fomated_price = number_format($price, 2);
    return $fomated_price;
}
function price_number($number){
    // 2 decimal place
    return sprintf("%.2f", $number);
}
// Send general emails
function send_emails($email, $type, $shortCodes = [])
{
    $email_template = EmailTemplate::whereType($type)->first();
    if($email_template == null){
        return;
    }
    $message = $email_template->content;
    foreach ($shortCodes as $code => $value) {
        $message = shortCodeReplacer('{{'.$code.'}}', $value, $message);
    }
    // subject
    $subject = $email_template->subject;
    foreach ($shortCodes as $code => $value) {
        $subject = shortCodeReplacer('{{'.$code.'}}', $value, $subject);
    }

    $data['subject'] = $subject;
    $data['message'] = $message;

    try {
        Mail::to($email)->queue(new MainEmail($data));
    } catch (\Exception $e) {
        // dd($e);
    }

}

function sendNotification(string $type, User $user, array $shortcodes, $custom = [])
{
    return [
        'type' => $type,
        'user_id' => $user->id,
        'data' => json_encode($shortcodes + $custom),
        'created_at' => now(),
        'updated_at' => now(),
    ];

}

// send email
function general_email($email, $mes, $sub)
{
    $data['subject'] = $sub;
    $data['message'] = $mes;
    try {
        Mail::to($email)->queue(new MainEmail($data));
    } catch (\Exception $e) {
        // dd($e);
    }
}

function sendTransactionEmail($user, $transaction)
{
    try {
        // Check if email notifications are enabled globally and for the user
        if (\sys_setting('trx_email') == 1 && $user->email_notify == 1) {
            // Prepare email data
            $emailData = [
                'username' => $user->username,
                'code' => $transaction->code,
                'title' => $transaction->title,
                'trx_details' => $transaction->message,
                'trx_type' => $transaction->type, //
                'amount' => format_price($transaction->amount),
                'date' => $transaction->created_at,
            ];

            // Use a notification or email function
            \send_emails($user->email, 'TRX_EMAIL', $emailData);
        }
    } catch (\Exception $e) {
        // Log any email sending error for debugging
        \Log::error('Failed to send transaction email: ' . $e->getMessage());
    }
}

// give affiliate bonus
function give_affiliate_bonus($id, $amount){
    $user = User::find($id);
    $refer = User::find($user->ref_id);
    $commission = sys_setting('referral_commission') * $amount /100;
    $trxcode = getTrans('BONUS');

    if($refer){
        $refer->bonus = $commission + $refer->bonus;
        $refer->save();
        $refer->transactions()->create([
            'amount' => $commission,
            'user_id' => $refer->id,
            'charge' => 0,
            'old_balance' => $refer->bonus - $commission,
            'new_balance' => $refer->bonus,
            'type' => 'credit',
            'status'=> 'successful',
            'service' => 'referral',
            'session_id' => generateSessionId(),
            'title' => 'Referral Bonus',
            'message' => 'Referral Bonus from '. $user->username,
            'code' => $trxcode,
            'reference' => $trxcode,
            'image' => static_asset('images/referral.png'),
        ]);
        // send email
        if(\sys_setting('trx_email') == 1 && $refer->email_notify == 1){
            \send_emails($refer->email, 'TRX_EMAIL',
            [
                'username' => $refer['username'],
                'code' => $trxcode,
                'trx_details' => 'Referral Bonus from '. $user->username,
                'trx_type' => trans_type2(1),
                'amount' => format_price($commission),
                'date' => date('Y-m-d H:m:s')
            ]);
        }
    }
    return;
}

function text_shortener($string, $length = null)
{
    if (empty($length)) $length = 100;
    return Str::limit($string, $length, "...");
}

function slug($string)
{
    return Illuminate\Support\Str::slug($string);
}


function generate_apikey(){
    return bin2hex(openssl_random_pseudo_bytes(33));
}

function api_response($code, $data, $data2=null)
{
    $response = $data;
    $response['data'] = $data2;

    return response()->json($response,$code);
}

function get_api_user(){
    $headers = getallheaders();
    $apikey = $headers['Authorization'];
    $apikey = str_replace('Token ', '', $apikey);
    $user = User::where('api_key', $apikey)->whereBlocked(0)->first();
    return $user;
}


function formatAndValidateUsername($username)
{
    $username = trim($username);

    $username = preg_replace('/\s+/', ' ', $username);
    $username = preg_replace('/[^a-zA-Z0-9_-]/', '', $username);

    $username = str_replace(' ', '_', $username);

    if (strlen($username) < 3 || strlen($username) > 20) {
        return false;
    }
    $pattern = '/^[a-zA-Z][a-zA-Z0-9_-]*$/';
    if (!preg_match($pattern, $username)) {
        return false;
    }

    return $username;
}

function debitUser($user, $amount){
    DB::transaction(function () use ($user, $amount) {
        User::where('id', $user->id)->lockForUpdate()->decrement('balance', $amount);
    });
    return true;
}
function creditUser($user, $amount){
    DB::transaction(function () use ($user, $amount) {
        User::where('id', $user->id)->lockForUpdate()->increment('balance', $amount);
    });
    return true;
}

function queryBuild($key, $value)
{
    $queries = request()->query();
    if(count($queries) > 0){
        $delimeter = '&';
    } else {
        $delimeter = '?';
    }
    if(request()->has($key)){
      $url = request()->getRequestUri();
      $pattern = "\?$key";
      $match = preg_match("/$pattern/",$url);
      if($match != 0){
        return  preg_replace('~(\?|&)'.$key.'[^&]*~', "\?$key=$value", $url);
      }
       $filteredURL = preg_replace('~(\?|&)'.$key.'[^&]*~', '', $url);
       return  $filteredURL.$delimeter."$key=$value";
    }
    return  request()->getRequestUri().$delimeter."$key=$value";

}
function validatePhone($phone) {
    $phone = preg_replace('/\D/', '', $phone);

    if (strlen($phone) !== 11) {
        return false;
    }
    $pattern = '/^(070|080|081|090|091)\d{8}$/';

    if (preg_match($pattern, $phone)) {
        return $phone;
    }
    return false;
}
