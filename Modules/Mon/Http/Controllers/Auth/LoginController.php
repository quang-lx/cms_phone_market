<?php

namespace Modules\Mon\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\WebController;
use Modules\Mon\Repositories\UserRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends WebController
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @param Authentication $authentication
     */
    public function __construct(Authentication $authentication)
    {
        parent::__construct($authentication);
        $this->middleware('guest')->except('logout');
    }
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }
    public function google()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        $gUser = Socialite::driver('google')->user();
        $user = $this->user->findByAttributes(['google_id' => $gUser->id]);
        if (!$user) {
            $data = [
                'facebook_id' => $gUser->getId(),
                'name' => $gUser->getName(),
                'email' => $gUser->getEmail(),
                'password' => \Hash::make(str_random(12))
            ];
            $user = $this->user->create($data);
            event(new Registered($user));
        }
        $this->guard()->login($user);
        app(UserRepository::class)->update($user, ['last_login' => date('Y-m-d H:i:s')]);
        return redirect()->intended($this->redirectPath());
    }

    public function facebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback()
    {
        $fbUser = Socialite::driver('facebook')->user();
        $user = $this->user->findByAttributes(['facebook_id' => $fbUser->id]);
        if (!$user) {
            $email = $fbUser->getEmail();
            if (!$email) {
                $email = $fbUser->getId().'@webgiasu.net';
            }
            $data = [
                'facebook_id' => $fbUser->getId(),
                'name' => $fbUser->getName(),
                'email' => $email,
                'gender' => $fbUser->getGender(),
                'password' => Hash::make(str_random(12))
            ];
            $user = $this->user->create($data);
            event(new Registered($user));
        }
        $this->guard()->login($user);
        app(UserRepository::class)->update($user, ['last_login' => date('Y-m-d H:i:s')]);
        return redirect()->intended($this->redirectPath());
    }
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
		$token = JWTAuth::fromUser($user);
		$request->session()->put('jwt_token', $token);
		app(UserRepository::class)->update($user, ['last_login' => date('Y-m-d H:i:s')]);

		return redirect()->intended($this->redirectPath());
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            return response()->json(['msg' => 'Logout successful!']);
        }
        $previousPath = url()->previous();
        return redirect($previousPath)->withSuccess(__('Logout successful!'));
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $this->seo()->setTitle(__('Login'));
        return $this->view('auth.login');
    }
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAdminLoginForm()
    {
        $this->seo()->setTitle(__('Login'));
        return view('backend::login');
    }

}
