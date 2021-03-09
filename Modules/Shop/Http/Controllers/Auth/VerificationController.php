<?php

namespace Modules\Shop\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\VerifiesEmails;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\WebController;

class VerificationController extends WebController
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @param Authentication $auth
     */
    public function __construct(Authentication $auth)
    {
        parent::__construct($auth);
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
