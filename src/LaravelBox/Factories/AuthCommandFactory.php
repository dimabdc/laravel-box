<?php

namespace LaravelBox\Factories;


use LaravelBox\Commands\Auth\RefreshTokenAuthCommand;
use LaravelBox\Commands\Auth\TokenAuthCommand;

class AuthCommandFactory
{
    public static function build(...$args)
    {
        if (count($args) <= 0) {
            return null;
        }

        $mode = array_pop($args);
        switch ($mode) {
            case 'token':
                if (count($args) < 3) {
                    return null;
                }

                return new TokenAuthCommand(...$args);
                break;

            case 'refresh':
                if (count($args) < 3) {
                    return null;
                }

                return new RefreshTokenAuthCommand(...$args);
                break;

            default:
                return null;
                break;
        }
    }
}