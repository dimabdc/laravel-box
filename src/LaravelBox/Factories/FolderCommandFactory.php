<?php

namespace LaravelBox\Factories;

use LaravelBox\Commands\Folders\CreateFolderCommand;
use LaravelBox\Commands\Folders\DeleteFolderCommand;
use LaravelBox\Commands\Folders\GetFolderInformationCommand;
use LaravelBox\Commands\Folders\GetFolderItemsCommand;

class FolderCommandFactory
{
    public static function build(...$args)
    {
        if (count($args) <= 0) {
            return null;
        }

        $mode = array_pop($args);
        switch ($mode) {
            case 'delete':
                return new DeleteFolderCommand(...$args);
                break;

            case 'create':
                return new CreateFolderCommand(...$args);
                break;

            case 'list':
                return new GetFolderItemsCommand(...$args);
                break;

            case 'info':
                return new GetFolderInformationCommand(...$args);
                break;

            default:
                throw new \BadMethodCallException();
        }
    }
}
