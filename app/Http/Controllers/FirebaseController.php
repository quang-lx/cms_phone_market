<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class FirebaseController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $firebase;
    protected $database;
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebase = $firebaseService->firebase;
        $this->database = $this->firebase->createDatabase();
    }
    public function index()
    {
        $userRef = null;
        $testData = [
            'name' => 'le.thi.thu',
            'email' => 'le.thi.thu@sun-asterisk.com',
        ];
        try {
            $this->database->getReference('users')->set($testData);
            $userRef = $this->database->getReference('users')
                ->orderByKey()
                ->getSnapshot();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        dd($userRef->getValue());
    }
}
