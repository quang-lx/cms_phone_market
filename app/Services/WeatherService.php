<?php

namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    const WAPI_URL = "https://api.waqi.info/feed";
    const WEATER_API_URL = "http://api.weatherapi.com/v1/current.json";

    protected $headers;
    protected $client;

    protected $wapi_token = '';
    protected $weather_api_key = '';
    protected $request_param = [];

    public function __construct()
    {

        $this->client = new Client([
        ]);
        $this->headers = ['headers' => []];
        $this->request_param = ['headers' => []];
        $this->wapi_token = env('WAPI_TOKEN', 'e73bb2abe9f418d52823764e18befa22648f554c');
        $this->weather_api_key = env('WEATER_API_KEY', '1451a5c1c4fa43dabe9150702212501');
    }


    public function getAirQuality($city)
    {
//        $this->usesJSON();


        try {

            $this->request_param['query'] = ['token' => $this->wapi_token];
            $url = sprintf("https://api.waqi.info/feed/%s/?token=%s", $city, $this->wapi_token);
            $response =  $this->client->get($url );
            return $this->handleReturnData($response);
        } catch (ClientException $ex) {

            return $this->handleRequestException($ex);
        }

    }
    public function getWeather($city)
    {
        $this->usesJSON();


        try {

            $url = sprintf("http://api.weatherapi.com/v1/current.json?key=%s&q=%s",  $this->weather_api_key, $city);
            Log::info($url);
            $response =  $this->client->get($url);
            return $this->handleReturnData($response);
        } catch (ClientException $ex) {

            return $this->handleRequestException($ex);
        }

    }

    private function requiresAuth()
    {
        $this->headers['headers']['Authorization'] = 'Basic ' . $this->restApiKey;
    }

    private function usesJSON()
    {
        $this->headers['headers']['Content-Type'] = 'application/json';
    }

    public function handleReturnData($response)
    {
        $responseCode = $response->getStatusCode();
        $body = json_decode($response->getBody(), true);

        if ($responseCode == 200) {

            return [
                'success' => true,
                'data' => $body
            ];
        } else {

            return [
                'success' => false,
                'data' => $body
            ];
        }
    }

    /**
     * @param $exception ClientException
     * @return array
     */
    public function handleRequestException($exception)
    {

        $response = $exception->getResponse();
        $body = json_decode($response->getBody(), true);


        return [
            'success' => false,
            'data' => $body
        ];

    }
}
