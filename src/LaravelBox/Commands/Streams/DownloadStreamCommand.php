<?php

namespace LaravelBox\Commands\Streams;

use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\stream_for;
use LaravelBox\Commands\AbstractCommand;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class DownloadStreamCommand extends AbstractCommand
{
    private $fileId;

    public function __construct(LaravelBox $app, string $path)
    {
        $this->app = $app;
        $this->fileId = parent::getFileId($path);
    }

    public function execute()
    {
        $url = $this->app->getApiURI() . "/files/{$this->fileId}/content";
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

            return ApiResponseFactory::build($resp, $stream);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}

