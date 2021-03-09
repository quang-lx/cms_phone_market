<?php

namespace Modules\Mon\Http\Middleware;

use Modules\Mon\Repositories\UserTokenRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiToken
{
    /**
     * @var UserTokenRepository
     */
    private $userToken;

    public function __construct(UserTokenRepository $userToken)
    {
        $this->userToken = $userToken;
    }

    public function handle(Request $request, \Closure $next)
    {
        if ($request->header('Authorization') === null) {
            return new Response('Forbidden', 403);
        }

        if ($this->isValidToken($request->bearerToken()) === false) {
            return new Response('Forbidden', 403);
        }

        return $next($request);
    }

    private function isValidToken($token)
    {
        $found = $this->userToken->newQueryBuilder()->where('access_token', $token)->first();

        if ($found === null) {
            return false;
        }

        return true;
    }
}
