<?php

namespace LaravelBox\Commands\Folders;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;

class GetFolderItemsCommand extends AbstractFolderCommand
{
    private $offset;
    private $limit;
    private $fields;

    public function __construct(string $token, $path, int $offset, int $limit, string $fields = '')
    {
        $this->token = $token;
        $this->folderId = is_string($path) ? $this->getFolderId($path) : $path;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->fields = $fields;
    }

    public function execute()
    {
        $offset = $this->offset;
        $limit = $this->limit;
        $url = "https://api.box.com/2.0/folders/{$this->folderId}/items";
        $options = [
            'query' => [
                'offset' => ($offset >= 0) ? $offset : 0,
                'limit' => ($limit >= 1) ? ($limit <= 1000 ? $limit : 1000) : 1,
                'fields' => $this->fields
            ],
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
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
