<?php

namespace LaravelBox\Commands\Auth;


use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class TokenAuthCommand
{
    protected $app;
    protected $code;
    protected $clientId;
    protected $clientSecret;

    public function __construct(LaravelBox $app, string $code, string $clientId, string $clientSecret)
    {
        $this->app = $app;
        $this->code = $code;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function execute()
    {
        $url = $this->app->getApiRootURL() . '/oauth2/token';
        $options = [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $this->code,
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
