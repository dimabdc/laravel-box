<?php

namespace LaravelBox\Commands\Folders;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class GetFolderInformationCommand extends AbstractFolderCommand
{
    public function __construct(LaravelBox $app, $path)
    {
        $this->app = $app;
        $this->folderId = is_string($path) ? $this->getFolderId($path) : $path;
    }

    public function execute()
    {
        $url = $this->app->getApiURI() . "/folders/{$this->folderId}";
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
            return ApiResponseFactory::build($e, ['GET', $url, $options]);
        }
    }
}
