<?php
namespace Modules\Mon\Repositories\Eloquent;



class ConnectedAccountRepository extends BaseRepository implements \Modules\Mon\Repositories\ConnectedAccountRepository {
    public function getUserBySocialAccount($socialAccountId, $provider) {
        $account = $this->findByAttributes([
            'provider' => $provider,
            'account_id' => $socialAccountId
        ]);
        if ($account && $account->user) {
            return $account->user;
        }
        return null;
    }
}
