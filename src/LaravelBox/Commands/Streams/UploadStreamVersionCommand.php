<?php

namespace LaravelBox\Commands\Streams;

use LaravelBox\Commands\AbstractCommand;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class UploadStreamVersionCommand extends AbstractCommand
{
    private $contents;
    private $remotePath;
    private $fileId;

    public function __construct(LaravelBox $app, $contents, string $remotePath)
    {
        $this->app = $app;
        $this->fileId = parent::getFileId($remotePath);
        $this->contents = $contents;
        $this->remotePath = $remotePath;
    }

    public function execute()
    {
        $cr = curl_init();
        $fw = tmpfile();
        $meta = stream_get_meta_data($fw);
        fwrite($fw, $this->contents);
        rewind($fw);
        $headers = [
            'Content-Type: multipart/form-data',
            "Authorization: Bearer {$this->app->getToken()}",
        ];
        curl_setopt($cr, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($cr, CURLOPT_URL, "https://upload.box.com/api/{$this->app->getApiVersion()}/files/{$this->fileId}/content");
        $json = json_encode([
            'name' => basename($this->remotePath),
            'parent' => [
                'id' => $this->getFolderId(dirname($this->remotePath)),
            ],
        ]);
        $fields = [
            'attributes' => $json,
            'file' => curl_file_create($meta['uri'], 'text/plain', basename($this->remotePath)),
        ];
        curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cr, CURLOPT_POSTFIELDS, $fields);
        try {
            $response = curl_exec($cr);

            return ApiResponseFactory::build($response);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        } finally {
            curl_close($cr);
            fclose($fw);
        }
    }
}
