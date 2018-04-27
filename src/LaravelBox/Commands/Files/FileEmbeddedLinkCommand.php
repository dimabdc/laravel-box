<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class FileEmbeddedLinkCommand extends AbstractFileCommand
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
        $options = [
            'query' => [
                'fields' => 'expiring_embed_link',
            ],
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
