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
                return new MoveFileCommand(...$args);
                break;

            case 'info':
                return new GetFileInformationCommand(...$args);
                break;

            case 'download':
                return new DownloadFileCommand(...$args);
                break;

            case 'upload':
                return new UploadFileCommand(...$args);
                break;

            case 'upload-version':
                return new UploadFileVersionCommand(...$args);
                break;

            case 'flight-check':
                return new PreflightCheckCommand(...$args);
                break;

            case 'delete':
                return new DeleteFileCommand(...$args);
                break;

            case 'copy':
                return new CopyFileCommand(...$args);
                break;

            case 'file-lock':
                return new LockFileCommand(...$args);
                break;

            case 'file-unlock':
                return new UnLockFileCommand(...$args);
                break;

            case 'thumbnail':
                return new FileThumbnailCommand(...$args);
                break;

            case 'embed-link':
                return new FileEmbeddedLinkCommand(...$args);
                break;

            case 'collaborations':
                return new FileCollaborationsCommand(...$args);
                break;

            case 'comments':
                return new FileCommentsCommand(...$args);
                break;

            case 'tasks':
                return new FileTasksCommand(...$args);
                break;

            default:
                throw new \BadMethodCallException();
        }
    }
}
