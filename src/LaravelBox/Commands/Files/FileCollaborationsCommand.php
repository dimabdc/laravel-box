<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use \LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class FileCollaborationsCommand extends AbstractFileCommand
{
    public function __construct(LaravelBox $app, string $path)
    {
        $this->app = $app;
        $this->fileId = $this->getFileId(basename($path));
    }

    public function execute()
    {
        $url = $this->app->getApiURI() . "/files/{$this->fileId}/collaborations";
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
        ];

        try {
            $client = new Client();
            $req = $client->request('GET', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
