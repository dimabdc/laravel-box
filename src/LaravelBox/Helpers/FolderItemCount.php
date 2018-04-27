<?php

namespace LaravelBox\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use LaravelBox\Commands\AbstractCommand;
use LaravelBox\LaravelBox;

class FolderItemCount extends AbstractCommand
{
    private $folderId;

    public function __construct(LaravelBox $app, string $path)
    {
        $this->app = $app;
        $this->folderId = parent::getFolderId($path);
    }

    public function execute()
    {
        $folderId = $this->folderId;
        if ($folderId < 0) {
            return -1;
        }

        $url = $this->app->getApiURI() . "/folders/{$folderId}";
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
        ];
        try {
            $client = new Client();
            $req = $client->request('GET', $url, $options);
            $json = json_decode($req->getBody());

            return $json->item_collection->total_count;
        } catch (ClientException $e) {
            return -1;
        }
    }
}
