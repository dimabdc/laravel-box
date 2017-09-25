<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;

class MoveFileCommand extends AbstractFileCommand
{
    private $newPath;
    private $oldPath;

    public function __construct(string $token, $path, $newPath)
    {
        $this->oldPath  = $path;
        $this->newPath  = $newPath;
        $this->token    = $token;
        $this->fileId   = is_string($path) ? parent::getFileId($path) : $path;
        $this->folderId = is_string($newPath) ? parent::getFolderId(dirname($path)) : $newPath;
    }

    public function execute()
    {
        $url     = "https://api.box.com/2.0/files/{$this->fileId}";
        $body    = [
            'name'   => basename($this->newPath),
            'parent' => [
                'id' => $this->getFolderId(dirname($this->newPath)),
            ],
        ];
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
            'body'    => json_encode($body),
        ];

        try {
            $client = new Client();
            $req    = $client->request('PUT', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
