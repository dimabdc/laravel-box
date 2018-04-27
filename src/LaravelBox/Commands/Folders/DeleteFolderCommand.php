<?php

namespace LaravelBox\Commands\Folders;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class DeleteFolderCommand extends AbstractFolderCommand
{
    private $recursive;

    public function __construct(LaravelBox $app, string $path, $recursive)
    {
        $this->app = $app;
        $this->recursive = $recursive;
        $this->folderId  = parent::getFolderId($path);
    }

    public function execute()
    {
        $url     = $this->app->getApiURI() . "/folders/{$this->folderId}";
        $options = [
            'query'   => [
                'recursive' => ($this->recursive === true) ? 'true' : 'false',
            ],
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
        ];
        try {
            $client = new Client();
            $resp   = $client->request('DELETE', $url, $options);

            return ApiResponseFactory::build($resp);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
