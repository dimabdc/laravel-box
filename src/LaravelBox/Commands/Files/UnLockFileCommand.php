<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class UnLockFileCommand extends AbstractFileCommand
{
    public function __construct(LaravelBox $app, string $path)
    {
        $this->app = $app;
        $this->fileId = parent::getFileId($path);
        $this->folderId = parent::getFolderId(dirname($path));
    }

    public function execute()
    {
        $url = $this->app->getApiURI() . "/files/{$this->fileId}";
        $body = [
            'lock' => [
                'type' => null,
                //TODO Lock Expiration Date
                //TODO Lock Download Prevented
            ],
        ];
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
            'query' => [
                'fields' => 'lock',
            ],
            'body' => json_encode($body),
        ];
        try {
            $client = new Client();
            $req = $client->request('PUT', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
