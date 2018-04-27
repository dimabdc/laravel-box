<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class LockFileCommand extends AbstractFileCommand
{
    public function __construct(LaravelBox $app, string $path)
    {
        $this->app = $app;
        $this->fileId = parent::getFileId($path);
        $this->folderId = parent::getFolderId(dirname($path));
    }

    public function execute()
    {
        $fileId = $this->fileId;
        $url = $this->app->getApiURI() . "/files/{$fileId}";
        $body = [
            'lock' => [
                'type' => 'lock',
                //TODO Lock Expiration Date
                //TODO Lock Download Prevented
            ],
        ];
        $options = [
            'body' => json_encode($body),
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
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
