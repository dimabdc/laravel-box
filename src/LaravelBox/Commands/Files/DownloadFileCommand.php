<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\stream_for;
use LaravelBox\Factories\ApiResponseFactory;

class DownloadFileCommand extends AbstractFileCommand
{
    private $downloadPath;

    public function __construct(string $token, string $local, string $remote)
    {
        $this->downloadPath = $local;
        $this->token = $token;
        $this->fileId = parent::getFileId($remote);
        $this->folderId = parent::getFolderId(dirname($remote));
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
