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
                return new TokenAuthCommand(...$args);
                break;

            case 'refresh':
                return new RefreshTokenAuthCommand(...$args);
                break;

            default:
                throw new \BadMethodCallException();
        }
    }
}