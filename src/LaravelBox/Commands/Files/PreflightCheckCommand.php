<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class PreflightCheckCommand extends AbstractFileCommand
{
    private $localPath;
    private $remotePath;

    public function __construct(LaravelBox $app, string $localPath, string $remotePath)
    {
        $this->app = $app;
        $this->localPath  = $localPath;
        $this->remotePath = $remotePath;
    }

    public function execute()
    {
        $url     = $this->app->getApiURI() . '/files/content';
        $body    = [
            'name'   => basename($this->localPath),
            'parent' => [
                'id' => $this->getFolderId(dirname($this->remotePath)),
            ],
            'size'   => filesize($this->localPath),
        ];
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
            'body'    => json_encode($body),
        ];

        try {
            $client = new Client();
            $req    = $client->request('OPTIONS', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
