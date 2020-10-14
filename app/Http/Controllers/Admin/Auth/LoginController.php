<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    protected $redirectTo = RouteServiceProvider::ADMIN_HOME;
    
    public function __construct(){
        $this->middleware('guest:admin');
    }

    /**
     * Show login form for guard.
     *
     * @return void
     */
    public function showLoginForm(){
        return view('login');
    }
    public function login(Request $request){
        // Validate login data
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ]);
        
        // Attempt to Login
        if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)){
            // Redirect to dashboard
            session()->flash('message', 'You are logged in');
            return redirect()->intended(route('admin.dashboard'));
        }else{
            // Redirect to login
            session()->flash('error', 'Invalid email or password');
            return redirect()->back();
        }
    }
    public function logout(Request $request){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
