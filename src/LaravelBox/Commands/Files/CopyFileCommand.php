<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class CopyFileCommand extends AbstractFileCommand
{
    private $newPath;

    public function __construct(LaravelBox $app, string $path, string $newPath)
    {
        $this->app = $app;
        $this->newPath = $newPath;
        $this->fileId = parent::getFileId($path);
        $this->folderId = parent::getFolderId(dirname($path));
    }

    public function execute()
    {
        $folderId = parent::getFolderId(dirname($this->newPath));
        $url = $this->app->getApiURI() . "/files/{$this->fileId}/copy";
        $body = [
            'parent' => [
                'id' => (string)$folderId,
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
            $req = $client->request('POST', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
