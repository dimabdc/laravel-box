<?php

namespace LaravelBox\Commands\Folders;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;

class GetFolderInformationCommand extends AbstractFolderCommand
{
    public function __construct(string $token, string $path)
    {
        $this->token = $token;
        $this->folderId = parent::getFolderId(dirname($path));
    }

    public function execute()
    {
        $url = "https://api.box.com/2.0/folders/{$this->fileId}";
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
