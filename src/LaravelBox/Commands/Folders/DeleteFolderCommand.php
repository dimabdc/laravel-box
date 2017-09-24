<?php

namespace LaravelBox\Commands\Folders;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;

class DeleteFolderCommand extends AbstractFolderCommand
{
    private $recursive;

    public function __construct(string $token, string $path, $recursive)
    {
        $this->token     = $token;
        $this->recursive = $recursive;
        $this->folderId  = parent::getFolderId($path);
    }

    public function execute()
    {
        $url     = "https://api.box.com/2.0/folders/{$this->folderId}";
        $options = [
            'query'   => [
                'recursive' => ($this->recursive === true) ? 'true' : 'false',
            ],
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
        ];
        try {
            $client = new Client();
            $resp   = $client->request('DELETE', $url, $options);

            return ApiResponseFactory::build($resp);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
