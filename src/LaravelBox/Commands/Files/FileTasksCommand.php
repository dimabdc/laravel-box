<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;

class FileTasksCommand extends AbstractFileCommand
{
    public function __construct(string $token, string $path)
    {
        $this->token = $token;
        $this->fileId = parent::getFileId($path);
        $this->folderId = parent::getFolderId(dirname($path));
    }

    public function execute()
    {
        $token = $this->token;
        $fileId = $this->fileId;

        $url = "https://api.box.com/2.0/files/{$fileId}/tasks";
        $options = [
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
