<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;

class DeleteFileCommand extends AbstractFileCommand
{
    public function __construct(string $token, $path)
    {
        $this->token = $token;
        $this->fileId = is_string($path) ? parent::getFileId($path) : $path;
    }

    public function execute()
    {
        $url = "https://api.box.com/2.0/files/{$this->fileId}";
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
        ];
        try {
            $client = new Client();
            $req = $client->request('DELETE', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
