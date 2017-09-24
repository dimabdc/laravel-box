<?php

namespace LaravelBox\Commands\Streams;

use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\stream_for;
use LaravelBox\Commands\AbstractCommand;
use LaravelBox\Factories\ApiResponseFactory;

class DownloadStreamCommand extends AbstractCommand
{
    public function __construct(string $token, string $path)
    {
        $this->token = $token;
        $this->fileId = parent::getFileId($path);
        $this->folderId = parent::getFolderId(dirname($path));
    }

    public function execute()
    {
        $url = "https://api.box.com/2.0/files/{$this->fileId}/content";
        $stream = stream_for(tmpfile());
        $options = [
            'sink' => $stream,
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
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
