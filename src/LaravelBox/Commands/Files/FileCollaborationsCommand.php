<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use \LaravelBox\Factories\ApiResponseFactory;

class FileCollaborationsCommand extends AbstractFileCommand
{
    public function __construct(string $token, string $path)
    {
        $this->fileId = $this->getFileId(basename($path));
    }

    public function execute()
    {
        $url = "https://api.box.com/2.0/files/{$this->fileId}/collaborations";
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
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
