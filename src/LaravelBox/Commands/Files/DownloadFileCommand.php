<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\stream_for;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class DownloadFileCommand extends AbstractFileCommand
{
    public function __construct(LaravelBox $app, $remote)
    {
        $this->app = $app;
        $this->fileId = is_string($remote) ? $this->getFileId($remote) : $remote;
    }

    public function execute()
    {
        $fileId = $this->fileId;
        $url = $this->app->getApiURI() . "/files/{$fileId}/content";
        $stream = stream_for(tmpfile());
        $options = [
            'sink' => $stream,
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
        ];
        try {
            $client = new Client();
            $resp = $client->request('GET', $url, $options);

            return ApiResponseFactory::build($resp);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
