<?php

namespace LaravelBox\Commands\Streams;

use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\stream_for;
use LaravelBox\Commands\AbstractCommand;
use LaravelBox\Factories\ApiResponseFactory;

class DownloadThumbnailStreamCommand extends AbstractCommand
{
    private $extension;

    public function __construct(string $token, string $path, string $extension)
    {
        $this->token = $token;
        $this->fileId = parent::getFileId($path);
        $this->folderId = parent::getFolderId(dirname($path));
        $this->extension = $extension;
    }

    public function execute()
    {
        $url = "https://api.box.com/2.0/files/{$this->fileId}/thumbnail.{$this->extension}";
        $tmpFile = tmpfile();
        $stream = stream_for($tmpFile);
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
