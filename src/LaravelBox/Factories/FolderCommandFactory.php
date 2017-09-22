<?php

namespace LaravelBox\Factories;

use LaravelBox\Commands\Folders\CreateFolderCommand;
use LaravelBox\Commands\Folders\DeleteFolderCommand;
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
                if (count($args) < 3) {
                    return null;
                }

                return new DeleteFolderCommand(...$args);
                break;

            case 'create':
                if (count($args) < 2) {
                    return null;
                }

                return new CreateFolderCommand(...$args);
                break;

            case 'list':
                if (count($args) < 4) {
                    return null;
                }

                return new GetFolderItemsCommand(...$args);
                break;

            default:
                return null;
                break;
        }
    }
}
