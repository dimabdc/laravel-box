<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\stream_for;
use LaravelBox\Factories\ApiResponseFactory;

class DownloadFileCommand extends AbstractFileCommand
{
    public function __construct(string $token, $remote)
    {
        $this->token = $token;
        $this->fileId = is_string($remote) ? $this->getFileId($remote) : $remote;
    }

    public function execute()
    {
        $fileId = $this->fileId;
        $token = $this->token;
        $url = "https://api.box.com/2.0/files/{$fileId}/content";
        $stream = stream_for(tmpfile());
        $options = [
            'sink' => $stream,
            'headers' => [
                'Authorization' => "Bearer {$token}",
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
