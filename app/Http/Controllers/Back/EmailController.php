<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Mail\Mukamail;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Mail;

class EmailController extends Controller
{
    public function setting()
    {

        return view('admin.email.settings');
    }

    public function templates()
    {

        $templates = EmailTemplate::all();

        return view('admin.email.template', compact('templates'));
    }

    public function edit_template($id)
    {

        $template = EmailTemplate::findorFail($id);
        $template->shortcodes = json_decode($template->shortcodes);

        return view('admin.email.edit', compact('template'));
    }

    public function update_template($id, Request $request)
    {

        $request->validate([
            'subject' => 'required',
            'content' => 'required',
        ]);
        $template = EmailTemplate::findOrFail($id);
        $template->subject = $request->subject;
        $template->content = $request->content;
        $template->save();

        return \redirect()->route('admin.email.template')->withSuccess('Email template updated successfully');

    }

    public function newsletter()
    {
        return view('admin.email.newsletter');
    }

    public function send_newsletter(Request $request)
    {
        $users = User::all();

        if ($request->user_emails == 1) {
            foreach ($users as $key => $user) {
                // return $user->email;
                $data['view'] = 'email.newsletter';
                $data['subject'] = $request->subject;
                $data['email'] = env('MAIL_FROM_ADDRESS');
                $data['content'] = $request->content;

                try {
                    Mail::to($user->email)->queue(new Mukamail($data));
                } catch (\Exception $e) {
                    // dd($e);
                    return back()->with('error', __('Check SMTP Settings'));
                }
            }
        }
        $other_emails = array_filter(array_map('trim', explode(',', $request->other_emails)));
        foreach ($other_emails as $key => $email) {
            $data['view'] = 'email.newsletter';
            $data['subject'] = $request->subject;
            $data['email'] = env('MAIL_FROM_ADDRESS');
            $data['content'] = $request->content;

            try {
                Mail::to($email)->queue(new Mukamail($data));
            } catch (\Exception $e) {
                // dd($e);
                return back()->with('error', __('Check SMTP Settings'));
            }
        }

        return back()->withSuccess('Email sent and delivered');
    }

    public function test_email(Request $request)
    {
        // code...
        $data['view'] = 'email.newsletter';
        $data['subject'] = 'SMTP Test on '.\get_setting('title');
        $data['email'] = env('MAIL_FROM_ADDRESS');
        $data['content'] = "SMTP Testing </br> We may collect non-personal identification information about Users whenever they interact with our Site. Non-personal identification information may include the browser name, the type of computer and technical information about Users means of connection to our Site, such as the operating system and the Internet Service Providers utilized and other similar information. </br> Designed By Jayflash <a href='https://jadsedev.com.ng'>JadesDev </a>";

        try {
            Mail::to($request->email)->queue(new Mukamail($data));
        } catch (\Exception $e) {
            // dd($e);
            return back()->with('error', __('Check SMTP Settings'));
        }

        return back()->with('success', __('Email sent Successfully.'));
    }
}
