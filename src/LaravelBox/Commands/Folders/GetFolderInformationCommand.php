<?php

namespace LaravelBox\Commands\Folders;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;

class GetFolderInformationCommand extends AbstractFolderCommand
{
    public function __construct(string $token, $path)
    {
        $this->token = $token;
        $this->folderId = is_string($path) ? $this->getFolderId($path) : $path;
    }

    public function execute()
    {
        $url = "https://api.box.com/2.0/folders/{$this->folderId}";
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
            return ApiResponseFactory::build($e, ['GET', $url, $options]);
        }
    }
}
