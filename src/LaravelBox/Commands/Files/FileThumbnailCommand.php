<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;

class FileThumbnailCommand extends AbstractFileCommand
{
    private $extension;
    private $outPath;

    public function __construct(string $token, string $path, string $outPath, string $extension)
    {
        $this->extension = $extension;
        $this->outPath = $outPath;
        $this->token = $token;
        $this->fileId = parent::getFileId($path);
        $this->folderId = parent::getFolderId(dirname($path));
    }

    public function execute()
    {
        $token = $this->token;
        $fileId = $this->fileId;
        $extension = $this->extension;
        $url = "https://api.box.com/2.0/files/{$fileId}/thumbnail.{$extension}";
        $options = [
            'sink' => fopen($this->outPath, 'wb'),
            'headers' => [
                'Authorization' => "Bearer {$token}",
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
