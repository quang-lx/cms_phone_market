<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface SearchRepository
{
    public function listSuggestion(Request $request);
    public function search(Request $request);
}
