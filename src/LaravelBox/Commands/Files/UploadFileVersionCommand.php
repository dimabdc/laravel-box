<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class UploadFileVersionCommand extends AbstractFileCommand
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
        $fileId = $this->getFileId($this->remotePath);
        $url    = "https://upload.box.com/api/{$this->app->getApiVersion()}/files/{$fileId}/content";

        $json = json_encode([
            'name'   => basename($this->localPath),
            'parent' => [
                'id' => $this->getFolderId(dirname($this->remotePath)),
            ],
        ]);

        $body    = [
            'attributes' => $json,
            'file'       => fopen($this->localPath, 'rb'),
        ];
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
            'body'    => json_encode($body),
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
