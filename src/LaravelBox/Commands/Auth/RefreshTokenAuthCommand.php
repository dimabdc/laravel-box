<?php

namespace LaravelBox\Commands\Auth;


use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;

class RefreshTokenAuthCommand
{
    protected $token;
    protected $clientId;
    protected $clientSecret;

    public function __construct(string $token, string $clientId, string $clientSecret)
    {
        $this->token        = $token;
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function execute()
    {
        $url = 'https://api.box.com/oauth2/token';
        $body = [
            'parent' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->token,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
        ];
        $options = [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->token,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
        ];

        try {
            $client = new Client();
            $resp = $client->request('POST', $url, $options);

            return ApiResponseFactory::build($resp);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}