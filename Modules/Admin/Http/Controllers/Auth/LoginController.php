<?php

namespace Modules\Admin\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\User;
use Modules\Mon\Http\Controllers\WebController;
use Modules\Mon\Repositories\ConnectedAccountRepository;
use Modules\Mon\Repositories\UserRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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
     * @var UserRepository $userRepository
     */
    public $userRepository;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @param Authentication $authentication
     */

    public function __construct(Authentication $authentication, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

    public function facebookCallback(Request $request)
    {
        $fbUser = Socialite::driver('facebook')->user();
        $user = $this->connectedAccountRepository->getUserBySocialAccount($fbUser->getId(), 'facebook');
        if (!$user) {
            $email = $fbUser->getEmail();
            if (!$email) {
                $email = $fbUser->getId().'@moncms.com';
            }
            $data = [
                'name' => $fbUser->getName(),
                'email' => $email,
                'password' => Hash::make(Str::random(12))
            ];
            $user = $this->userRepository->createWithRoles($data, [1,2]);
            $connectedAccountData = [
                'provider' => ConnectedAccount::PROVIDER_FACEBOOK,
                'user_id' => $user->id,

                'account_id' => $fbUser->getId(),
                'email' => $fbUser->getEmail(),
                'first_name' => $fbUser->getName(),
                'last_name' => $fbUser->getNickname(),
            ];
            $this->connectedAccountRepository->create($connectedAccountData);
          //  event(new Registered($user));
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

        if (in_array('admin',explode('/', $previousPath))) {
            return redirect($previousPath)->withSuccess(__('Logout successful!'));
        }
        return redirect()->route('home')->withSuccess(__('Logout successful!'));
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
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => [
            	'required',
	            'string',
	            Rule::exists('users')->where(function ($query) {
		            return $query->where('status', User::STATUS_ACTIVE);
	            }),
	            ],
            'password' => 'required|string',

        ],[
            'username.required' => 'Vui lòng nhập đủ thông tin',
            'password.required' => 'Vui lòng nhập đủ thông tin',
            'username.exists' => 'Tài khoản đang bị khóa',
        ]);
    }
    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
