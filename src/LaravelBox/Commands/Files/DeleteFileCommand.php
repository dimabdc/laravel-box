<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class DeleteFileCommand extends AbstractFileCommand
{
    public function __construct(LaravelBox $app, $path)
    {
        $this->app = $app;
        $this->fileId = is_string($path) ? parent::getFileId($path) : $path;
    }

    public function execute()
    {
        $url = $this->app->getApiURI() . "/files/{$this->fileId}";
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
        ];
        try {
            $client = new Client();
            $req = $client->request('DELETE', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
