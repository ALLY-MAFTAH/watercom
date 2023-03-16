<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\MessagingService;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */


    // use ResetsPasswords;

    public function index()
    {
        return view('auth.passwords.email');
    }

    public function validatePasswordRequest(HttpRequest $request)
    {
        $user =User::where('mobile', '=', $request->mobile)
            ->first();
        if (!$user) {
            return redirect()->back()->withErrors(['mobile' => trans('User does not exist')]);
        }

        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'mobile' => $request->mobile,
            'token' => Str::random(60),
            'created_at' => Carbon::now()
        ]);

        $tokenData = DB::table('password_resets')
            ->where('mobile', $request->mobile)->first();

        $link = env('APP_URL') . '/password/reset/' . $tokenData->token . '?mobile=' . urlencode($user->mobile);

        $msg = "RESET PASSWORD LINK: " . $link;
        $messageingService = new MessagingService();
        $response = $messageingService->sendMessage($request->mobile, $msg);

        // dd($response);
        if ($response == 'Sent') {
            return redirect()->back()->with('status', trans('A reset link has been sent to your mobile number via sms.'));
        } else {
            return redirect()->back()->withErrors(['error' => trans('A Network Error occurred. Please try again.')]);
        }
    }
    private function sendResetEmail($email, $token)
    {
        //Retrieve the user from the database
        $user = DB::table('users')->where('email', $email)->select('name', 'email')->first();
        //Generate, the password reset link. The token generated is embedded in the link
        $link = env('APP_URL') . '/password/reset/' . $token . '?email=' . urlencode($user->email);

        // dd($link);
        try {
            Mail::send('mails.reset_password_mail', ['user' => $user, 'token' => $token, 'link' => $link], function ($m) use ($user) {
                $m->from('amelipaapp@gmail.com', setting('App Name', "Tanga Watercom"));

                $m->to($user->email, $user->name)->subject('Password Reset Link');
            });
            return true;
        } catch (\Exception $e) {
            // dd($e);
            notify()->error($e->getMessage());
            return false;
        }
    }
    public function reset($token)
    {
        return view('auth.passwords.reset', compact('token'));
    }

    public function resetPassword(HttpRequest $request)
    {
        //Validate input
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:users,mobile',
            'password' => 'required|confirmed',
            'token' => 'required'
        ]);

        //check if payload is valid before moving on
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['mobile' => 'Please complete the form']);
        }

        $password = $request->password;
        // Validate the token
        $tokenData = DB::table('password_resets')
            ->where('token', $request->token)->first();
        // Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData) return view('auth.passwords.email');

        $user =User::where('mobile', $tokenData->mobile)->first();
        // Redirect the user back if the mobile is invalid
        if (!$user) return redirect()->back()->withErrors(['mobile' => 'Mobile number not found']);
        //Hash and update the new password
        $user->password = Hash::make($password);
        $user->update(); //or $user->save();

        //login the user immediately they change password successfully
        Auth::login($user);

        //Delete the token
        DB::table('password_resets')->where('mobile', $user->mobile)
            ->delete();
        notify()->success('You have successful reset your password');

        return view('home.dashboard');
    }
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    protected $redirectTo = RouteServiceProvider::HOME;
}


