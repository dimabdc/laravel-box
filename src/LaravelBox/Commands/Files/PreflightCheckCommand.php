<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;

class PreflightCheckCommand extends AbstractFileCommand
{
    private $localPath;
    private $remotePath;

    public function __construct(string $token, string $localPath, string $remotePath)
    {
        $this->token      = $token;
        $this->localPath  = $localPath;
        $this->remotePath = $remotePath;
    }

    public function execute()
    {
        $url     = 'https://api.box.com/2.0/files/content';
        $body    = [
            'name'   => basename($this->localPath),
            'parent' => [
                'id' => $this->getFolderId(dirname($this->remotePath)),
            ],
            'size'   => filesize($this->localPath),
        ];
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
            'body'    => json_encode($body),
        ];

        try {
            $client = new Client();
            $req    = $client->request('OPTIONS', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
