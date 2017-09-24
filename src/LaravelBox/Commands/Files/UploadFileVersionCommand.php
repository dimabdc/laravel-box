<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;

class UploadFileVersionCommand extends AbstractFileCommand
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
        $fileId = parent::getFileId($this->remotePath);
        $url    = "https://upload.box.com/api/2.0/files/{$fileId}/content";

        $json = json_encode([
            'name'   => basename($this->localPath),
            'parent' => [
                'id' => $this->getFolderId(dirname($this->remotePath)),
            ],
        ]);

        $body    = [
            'attributes' => $json,
            'file'       => fopen($this->localPath, 'br'),
        ];
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
            'body'    => json_encode($body),
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
