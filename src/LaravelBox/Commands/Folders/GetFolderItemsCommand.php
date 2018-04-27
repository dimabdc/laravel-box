<?php

namespace LaravelBox\Commands\Folders;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class GetFolderItemsCommand extends AbstractFolderCommand
{
    private $offset;
    private $limit;
    private $fields;

    public function __construct(LaravelBox $app, $path, int $offset, int $limit, string $fields = '')
    {
        $this->app = $app;
        $this->folderId = is_string($path) ? $this->getFolderId($path) : $path;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->fields = $fields;
    }

    public function execute()
    {
        $offset = $this->offset;
        $limit = $this->limit;
        $url = $this->app->getApiURI() . "/folders/{$this->folderId}/items";
        $options = [
            'query' => [
                'offset' => ($offset >= 0) ? $offset : 0,
                'limit' => max(1, min(1000, $limit)),
                'fields' => $this->fields
            ],
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
        ];
        try {
            $client = new Client();
            $resp = $client->request('GET', $url, $options);

            return ApiResponseFactory::build($resp);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
