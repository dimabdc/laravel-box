<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class FileThumbnailCommand extends AbstractFileCommand
{
    private $extension;
    private $outPath;

    public function __construct(LaravelBox $app, string $path, string $outPath, string $extension)
    {
        $this->app = $app;
        $this->extension = $extension;
        $this->outPath = $outPath;
        $this->fileId = parent::getFileId($path);
        $this->folderId = parent::getFolderId(dirname($path));
    }

    public function execute()
    {
        $fileId = $this->fileId;
        $extension = $this->extension;
        $url = $this->app->getApiURI() . "/files/{$fileId}/thumbnail.{$extension}";
        $options = [
            'sink' => fopen($this->outPath, 'wb'),
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
