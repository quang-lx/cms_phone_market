<?php
namespace Modules\Mon\Repositories\Eloquent;

use \Modules\Mon\Repositories\UserTokenRepository as UserTokenInterface;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;

class UserTokenRepository extends BaseRepository implements UserTokenInterface {
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('user_id') !== null) {
            $query->where('user_id', '=', $request->get('user_id'));
        }

        if ($request->get('access_token') !== null) {
            $query->where('access_token', '=', $request->get('access_token'));
        }

        return $query->paginate($request->get('per_page', 10));
    }
}
