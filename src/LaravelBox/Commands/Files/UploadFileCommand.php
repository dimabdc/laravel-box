<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;

class UploadFileCommand extends AbstractFileCommand
{
    private $localPath;
    private $remotePath;

    public function __construct(string $token, $localPath, string $remotePath)
    {
        $this->token      = $token;
        $this->localPath  = $localPath;
        $this->remotePath = $remotePath;
    }

    public function execute()
    {
        $url = 'https://upload.box.com/api/2.0/files/content';

        $json = json_encode([
            'name'   => basename($this->remotePath),
            'parent' => [
                'id' => $this->getFolderId(dirname($this->remotePath)),
            ],
        ]);

        $body    = [
            'attributes' => $json,
            'file'       => $this->localPath,
        ];
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
            'body'    => $body,
        ];

        try {
            $client = new Client();
            $req    = $client->request('POST', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
