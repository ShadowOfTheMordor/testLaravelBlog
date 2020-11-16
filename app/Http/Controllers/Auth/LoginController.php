<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


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
//    protected $redirectTo = RouteServiceProvider::HOME;
//    protected $redirectTo = RouteServiceProvider::POST;
    protected $redirectTo = "/post";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (Session::has("backUrl"))
            Session::reflash();
        return view("auth.login");
    }
    
    protected function authenticated(Request $request, $user)
    {
//        $request->session()->flash("success",__("messages.auth.LoginSuccess"));
        \Illuminate\Support\Facades\Session::flash("success", __("messages.auth.LoginSuccess"));
//        return redirect()->intended($this->redirectPath());
//        return redirect()->back();
//        $url=url()->previous();
//        return redirect($url);
        if (Session::has("backUrl"))
            return redirect(Session::get ("backUrl"));
        return redirect()->back();
    }
    
    public function loggedOut(Request $request)
    {
        return redirect(route("post.index"))
                ->with("success",__("messages.auth.LogoutSuccess") );
    }

}
