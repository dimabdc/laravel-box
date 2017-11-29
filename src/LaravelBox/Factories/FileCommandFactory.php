<?php

namespace LaravelBox\Factories;

use LaravelBox\Commands\Files\CopyFileCommand;
use LaravelBox\Commands\Files\DeleteFileCommand;
use LaravelBox\Commands\Files\DownloadFileCommand;
use LaravelBox\Commands\Files\FileCollaborationsCommand;
use LaravelBox\Commands\Files\FileCommentsCommand;
use LaravelBox\Commands\Files\FileEmbeddedLinkCommand;
use LaravelBox\Commands\Files\FileTasksCommand;
use LaravelBox\Commands\Files\FileThumbnailCommand;
use LaravelBox\Commands\Files\GetFileInformationCommand;
use LaravelBox\Commands\Files\LockFileCommand;
use LaravelBox\Commands\Files\MoveFileCommand;
use LaravelBox\Commands\Files\PreflightCheckCommand;
use LaravelBox\Commands\Files\UnLockFileCommand;
use LaravelBox\Commands\Files\UploadFileCommand;
use LaravelBox\Commands\Files\UploadFileVersionCommand;

class FileCommandFactory
{
    public static function build(...$args)
    {
        if (count($args) <= 0) {
            return null;
        }

        $mode = array_pop($args);
        switch ($mode) {
            case 'move':
                if (count($args) < 3) {
                    return null;
                }

                return new MoveFileCommand(...$args);
                break;

            case 'info':
                if (count($args) < 2) {
                    return null;
                }

                return new GetFileInformationCommand(...$args);
                break;

            case 'download':
                if (count($args) < 2) {
                    return null;
                }

                return new DownloadFileCommand(...$args);
                break;

            case 'upload':
                if (count($args) < 3) {
                    return null;
                }

                return new UploadFileCommand(...$args);
                break;

            case 'upload-version':
                if (count($args) < 3) {
                    return null;
                }

                return new UploadFileVersionCommand(...$args);
                break;

            case 'flight-check':
                if (count($args) < 3) {
                    return null;
                }

                return new PreflightCheckCommand(...$args);
                break;

            case 'delete':
                if (count($args) < 2) {
                    return null;
                }

                return new DeleteFileCommand(...$args);
                break;

            case 'copy':
                if (count($args) < 3) {
                    return null;
                }

                return new CopyFileCommand(...$args);
                break;

            case 'file-lock':
                if (count($args) < 2) {
                    return null;
                }

                return new LockFileCommand(...$args);
                break;

            case 'file-unlock':
                if (count($args) < 2) {
                    return null;
                }

                return new UnLockFileCommand(...$args);
                break;

            case 'thumbnail':
                if (count($args) < 4) {
                    return null;
                }

                return new FileThumbnailCommand(...$args);
                break;

            case 'embed-link':
                if (count($args) < 2) {
                    return null;
                }

                return new FileEmbeddedLinkCommand(...$args);
                break;

            case 'collaborations':
                if (count($args) < 2) {
                    return null;
                }

                return new FileCollaborationsCommand(...$args);
                break;

            case 'comments':
                if (count($args) < 2) {
                    return null;
                }

                return new FileCommentsCommand(...$args);
                break;

            case 'tasks':
                if (count($args) < 2) {
                    return null;
                }

                return new FileTasksCommand(...$args);
                break;
            default:
                return null;
                break;
        }
    }
}
