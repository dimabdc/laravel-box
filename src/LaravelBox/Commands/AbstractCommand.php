<?php

namespace LaravelBox\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use LaravelBox\LaravelBox;

abstract class AbstractCommand
{
    /**
     * @var LaravelBox
     */
    protected $app;

    abstract protected function execute();

    public function getFileId(string $path)
    {
        if (basename($path) === '') {
            return -1;
        }

        $folder   = dirname($path);
        $folderId = 0; // base case of root
        if ($folder !== '/' && $folder !== '.') {
            // if not root
            $folderId = $this->getFolderId($folder);
        }
        if (($item_count = $this->getFolderItemCount($folderId)) < 0) {
            return -1;
        }
        $fileId = -1;
        $offset = 0;
        do {
            $limit = ($item_count < 1000 + $offset) ? $item_count - $offset : 1000;
            $items = $this->getFolderItems($folderId, $offset, $limit);
            foreach ($items->entries as $item) {
                if ($item->name == basename($path)) {
                    $fileId = $item->id;
                }
            }
            $offset += $limit;
        } while ($offset < $item_count && $fileId === -1);

        return $fileId;
    }

    public function getFolderId(string $path)
    {
        if (dirname($path) === '.') {
            return -1;
        }

        if ($path === '/' || $path === '') {
            return 0;
        }
        $exp     = explode('/', $path);
        $exp_cnt = count($exp);

        return $this->recursiveFolderIdFind(0, implode('/', array_slice($exp, 1)), $exp[$exp_cnt - 1]);
    }

    public function fileExists(string $fileId)
    {
        try {
            $url     = $this->app->getApiURI() . "/files/{$fileId}";
            $options = [
                'headers' => [
                    'Authorization' => "Bearer {$this->app->getToken()}",
                ],
            ];
            $client  = new Client();
            $resp    = $client->request('GET', $url, $options);

            return $resp->getStatusCode() < 400;
        } catch (ClientException $e) {
            return false;
        }
    }

    public function folderExists(string $folderId)
    {
        try {
            $url     = $this->app->getApiURI() . "/folders/{$folderId}";
            $options = [
                'headers' => [
                    'Authorization' => "Bearer {$this->app->getToken()}",
                ],
            ];
            $client  = new Client();
            $resp    = $client->request('GET', $url, $options);

            return $resp->getStatusCode() < 400;
        } catch (ClientException $e) {
            return false;
        }
    }

    public function getFolderItemCount($folderId)
    {
        if ($folderId < 0) {
            return -1;
        }

        $url     = $this->app->getApiURI() . "/folders/{$folderId}";
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
        ];
        try {
            $client = new Client();
            $req    = $client->request('GET', $url, $options);
            $json   = json_decode($req->getBody());

            return $json->item_collection->total_count;
        } catch (ClientException $e) {
            return -1;
        }
    }

    public function getFolderItems($folderId, $offset, $limit)
    {
        $limit_min = 100;
        $limit_max = 1000;
        $url       = $this->app->getApiURI() . "/folders/{$folderId}/items";
        $options   = [
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
            'query'   => [
                'fields' => 'name',
                'offset' => ($offset < 0) ? 0 : $offset,
                'limit'  => ($limit < 0) ? $limit_min : ($limit > $limit_max ? $limit_max : $limit),
            ],
        ];
        try {
            $client = new Client();
            $req    = $client->request('GET', $url, $options);

            return json_decode($req->getBody());
        } catch (\Exception $e) {
            return json_decode(json_encode([]));
        }
    }

    public function recursiveFolderIdFind($search_folder_id, $search_path, $final_folder)
    {
        $exp         = explode('/', $search_path);
        $find_folder = $exp[0];
        if (($item_count = $this->getFolderItemCount($search_folder_id)) < 0) {
            return -1;
        }
        $folderId = -1;
        $offset   = 0;
        do {
            $limit = ($item_count < 1000 + $offset) ? $item_count - $offset : 1000;
            $items = $this->getFolderItems($search_folder_id, $offset, $limit);
            foreach ($items->entries as $item) {
                if ($item->name == $find_folder) {
                    $id = $item->id;
                    if ($item->name == $final_folder) {
                        return $id;
                        break; // Just to be safe
                    }
                    $folderId = $this->recursiveFolderIdFind($id, implode('/', array_slice($exp, 1)), $final_folder);
                    break;
                }
            }
            $offset += $limit;
        } while ($offset < $item_count);

        return $folderId;
    }
}
