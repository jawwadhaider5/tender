<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Mockery\Matcher\Subset;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function subscribe(Request $request)
    {    

        try { 
            Subscribe::create([
                "email" => $request->get("email")
            ]);  

            $data = [
                "success" => true,
                "'message'" => "Subscribed Successfully"
            ];
        } catch (Exception $e) { 
            $data = [
                "success" => false,
                "'message'" => "Something went worng!"
            ];
        } 

        return json_encode($data); 
        // return redirect()->back()->with($data); 
    }
}
