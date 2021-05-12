<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;
use Modules\Mon\Entities\User;

interface AddressRepository
{
	public function create($data);
	public function update($model, $data);
	public function findById(Request $request, $id);
	public function serverPagingFor(Request $request, User $user);
}
