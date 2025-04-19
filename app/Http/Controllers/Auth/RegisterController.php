<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Roles;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Mail\UserNotificationEmail;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * The user has been registered.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $user
     * @return mixed
     */
    protected function registered() {
        Auth::logout(); // Logout the user after registration
        // return redirect()->route('login');
        return $this->sendResponse(true, route('login'), 'Your Credentials Successfully Registered');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'movies_count' => ['required'],
            'project_role' => ['required'],
            'country_code' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data) {

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'role_id' => Roles::Seller->value,
                'phone' => $data['phone'],
                'movies_count' => $data['movies_count'],
                'project_role' => $data['project_role'],
                'country_code' => $data['country_code']
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        try {
            Helpers::sendMail(new UserNotificationEmail($data['name']), $data['email']);
        } catch (\Exception $e) {
            Log::log('error', 'Error Sending Email:- ' . $e);
        }
        return $user;
    }

}
