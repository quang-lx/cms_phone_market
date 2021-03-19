<?php


namespace App\Services;


use Kreait\Firebase\Factory;

class FirebaseService
{
    /**
     * @var \Kreait\Laravel\Firebase\Facades\Firebase
     */
    public $firebase;

    public function __construct()
    {

        $this->firebase = (new Factory)->withServiceAccount(base_path('laravel-chat-moncms-70bcf62a04b8.json'))->withDatabaseUri('https://laravel-chat-moncms-default-rtdb.firebaseio.com');;

    }
}
