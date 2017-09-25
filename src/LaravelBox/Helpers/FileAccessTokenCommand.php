<?php

namespace LaravelBox\Helpers;


use GuzzleHttp\Client;
use LaravelBox\Commands\AbstractCommand;
use LaravelBox\Factories\ApiResponseFactory;

class FileAccessTokenCommand extends AbstractCommand
{
    private $fileId;

    public function __construct(string $token, $path)
    {
        $this->token  = $token;
        $this->fileId = is_string($path) ? parent::getFileId($path) : $path;
    }

    public function execute()
    {
        $url     = 'https://api.box.com/oauth2/token';
        $options = [
            'form_params' => [
                'grant_type'         => 'urn:ietf:params:oauth:grant-type:token-exchange',
                'scope'              => 'item_download item_preview',
                'subject_token_type' => 'urn:ietf:params:oauth:token-type:access_token',
                'subject_token'      => $this->token,
                'resource'           => "https://api.box.com/2.0/files/{$this->fileId}"
            ]
        ];
        try {
            $client = new Client();
            $req    = $client->request('POST', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
