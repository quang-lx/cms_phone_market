<?php

namespace  App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SmsClient implements SendSmsService
{
    const API_URL = "http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get";

    protected $headers;
    protected $client;


    public function __construct()
    {

        $this->client = new Client([
        ]);
        $this->headers = ['headers' => []];
    }

    public function post($endPoint)
    {
        return $this->client->post(self::API_URL . $endPoint, $this->headers);
    }

    public function put($endPoint)
    {
        return $this->client->put(self::API_URL . $endPoint, $this->headers);
    }

    public function get($endPoint)
    {
        return $this->client->get(self::API_URL . $endPoint, $this->headers);
    }

    public function delete($endPoint)
    {
        return $this->client->delete(self::API_URL . $endPoint, $this->headers);
    }

    public function sendToPhone($phone, $content)
    {
       // $this->requiresAuth();
        $this->usesJSON();


        try {
            return $this->client->request('GET', self::API_URL,[
                'query' => [
                    'Phone' => $phone,
                    'Content' => $content,
                    'ApiKey' => env('SMS_API_KEY'),
                    'SecretKey' => env('SMS_SECRET_KEY'),
                    'SmsType' => 2,
                    'Sandbox' => 0,
                    'brandname' => env('SMS_BANDNAME'),
                ]
            ]);
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
