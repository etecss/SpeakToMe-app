<?php
namespace App\Http\Controllers\Api\v1;

use GuzzleHttp\Client;

class Travel extends ApiController
{
    public function run() {
        $client = new Client();

        $params = [
            'query' => [
                'latitude' => '45.770297',
                'longitude' => '4.863703',
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken(),
            ]
        ];

        $response = $client->request('GET', 'https://api.yelp.com/v3/businesses/search', $params);

        $body = $response->getBody();
        $objResponse = json_decode($body);
        return $objResponse->businesses;
    }

    public function generateToken() {
        $client = new Client();

        $params = [
            'form_params' => [
                'client_id' => config('external_api.params.travel.client_id'),
                'client_secret' => config('external_api.params.travel.client_secret')
            ]
        ];

        $response = $client->request('POST', 'https://api.yelp.com/oauth2/token', $params);
        $body = $response->getBody();
        $objResponse = json_decode($body);
        $token = $objResponse->access_token;
        $this->saveToken($token);

        return $token;
    }
}