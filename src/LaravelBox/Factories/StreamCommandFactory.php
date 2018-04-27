<?php

namespace LaravelBox\Factories;

use LaravelBox\Commands\Streams\DownloadStreamCommand;
use LaravelBox\Commands\Streams\DownloadThumbnailStreamCommand;
use LaravelBox\Commands\Streams\UploadStreamCommand;
use LaravelBox\Commands\Streams\UploadStreamContentsCommand;
use LaravelBox\Commands\Streams\UploadStreamContentsVersionCommand;
use LaravelBox\Commands\Streams\UploadStreamVersionCommand;

class StreamCommandFactory
{
    public static function build(...$args)
    {
        if (count($args) <= 0) {
            return null;
        }

        $command = array_pop($args);
        switch ($command) {
            case 'upload':
                return new UploadStreamCommand(...$args);
                break;

            case 'upload-version':
                return new UploadStreamVersionCommand(...$args);
                break;

            case 'upload-stream':
                return new UploadStreamContentsCommand(...$args);
                break;

            case 'upload-stream-version':
                return new UploadStreamContentsVersionCommand(...$args);
                break;

            case 'download':
                return new DownloadStreamCommand(...$args);
                break;

            case 'thumbnail':
                return new DownloadThumbnailStreamCommand(...$args);
                break;

            default:
                throw new \BadMethodCallException();
        }
    }
}
