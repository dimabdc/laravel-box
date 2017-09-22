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
                if (count($args) < 3) {
                    return null;
                }

                return new UploadStreamCommand(...$args);
                break;

            case 'upload-version':
                if (count($args) < 3) {
                    return null;
                }

                return new UploadStreamVersionCommand(...$args);
                break;

            case 'upload-stream':
                if (count($args) < 3) {
                    return null;
                }

                return new UploadStreamContentsCommand(...$args);
                break;

            case 'upload-stream-version':
                if (count($args) < 3) {
                    return null;
                }

                return new UploadStreamContentsVersionCommand(...$args);
                break;

            case 'download':
                if (count($args) < 2) {
                    return null;
                }

                return new DownloadStreamCommand(...$args);
                break;

            case 'thumbnail':
                if (count($args) < 3) {
                    return null;
                }

                return new DownloadThumbnailStreamCommand(...$args);
                break;

            default:
                return null;
        }
    }
}
