<?php
namespace Modules\Mon\Repositories;

use Illuminate\Http\Request;
use \Illuminate\Database\Eloquent\Builder;

interface BaseRepository {
    public function all();
    public function find($id);
    public function findByAttributes(array $attributes);
    public function create($data);
    public function update($model, $data);
    public function destroy($model);
    public function newQueryBuilder():Builder;
    public function serverPagingFor(Request $request, $relations = null);
}
