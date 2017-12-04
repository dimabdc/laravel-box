<?php

namespace LaravelBox\Factories;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\PSr7\Stream;
use LaravelBox\ApiResponse;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class ApiResponseFactory
{
    public static function build()
    {
        if (func_num_args() < 1) {
            return null;
        }

        if (func_num_args() === 2 && func_get_arg(1) instanceof Stream) {
            return self::handleStreamResponse(func_get_arg(0), func_get_arg(1));
        }

        $arg = func_get_arg(0);

        if (self::isAResponse($arg)) {
            return self::getResponse($arg);
        }

        return self::getCurlResponse($arg);
    }

    private static function getResponse(ResponseInterface $arg)
    {
        if (self::isFileDownload($arg)) {
            // FILE Download
            $type     = 'FILE_DOWNLOAD';
            $code     = $arg->getStatusCode();
            $reason   = $arg->getReasonPhrase();
            $body     = $arg->getBody();
            $response = new ApiResponse($type);
            $response->setCode($code);
            $response->setReason($reason);
            $response->setBody($body);
            $response->setRequest($arg);

            return $response;
        } else {
            $type      = 'JSON';
            $code      = $arg->getStatusCode();
            $reason    = $arg->getReasonPhrase();
            $body      = $arg->getBody();
            $body_json = json_decode((string)$body);
            $response  = new ApiResponse($type);
            $response->setCode($code);
            $response->setReason($reason);
            $response->setBody($body);
            $response->setJson($body_json);
            $response->setRequest($arg);

            return $response;
        }
    }

    private static function getCurlResponse($arg)
    {
        if ($arg instanceof \Exception) {
            $exception = $arg;
            $type      = 'errors';
            $code      = $exception->getCode();
            $message   = $exception->getMessage();
            $body      = $exception->__toString();
            $body_json = json_decode(json_encode(['errors' => $body]));
            $response  = new ApiResponse($type);
            $response->setCode($code);
            $response->setMessage($message);
            $response->setBody($body);
            $response->setJson($body_json);
            $response->setException($exception);

            return $response;
        }

        $body = $arg;
        $json = json_decode($arg);
        if (property_exists($json, 'status') && $json->status >= 400) {
            // ERROR Occurred
            $type    = $json->type;
            $code    = $json->status;
            $message = $json->code;

            $response = new ApiResponse($type);
            $response->setCode($code);
            $response->setMessage($message);
            $response->setJson($json);
            $response->setBody($body);

            return $response;
        } elseif (property_exists($json, 'upload_url')) {
            // PREFLIGHT CHECK
            $type   = 'PREFLIGHT_CHECK';
            $code   = 200;
            $reason = 'Ok';

            $response = new ApiResponse($type);
            $response->setCode($code);
            $response->setReason($reason);
            $response->setBody($body);
            $response->setJson($json);

            return $response;
        } else {
            // Upload Successful
            $type     = 'FILE_UPLOAD';
            $code     = 200;
            $reason   = 'Ok';
            $fileName = $json->entries[0]->name;

            $response = new ApiResponse($type);
            $response->setCode($code);
            $response->setReason($reason);
            $response->setFileName($fileName);
            $response->setBody($body);
            $response->setJson($json);

            return $response;
        }
    }

    private static function isAResponse($arg)
    {
        return is_a($arg, Response::class);
    }

    private static function isFileDownload(ResponseInterface $arg)
    {
        return $arg->hasHeader('Content-Type') && $arg->getHeaderLine('Content-Type') !== 'application/json';
    }

    private static function handleStreamResponse($arg, $stream)
    {
        $type   = 'DOWNLOAD_STREAM';
        $code   = $arg->getStatusCode();
        $reason = $arg->getReasonPhrase();
        $body   = $arg->getBody();
        $json   = json_decode($body);

        $response = new ApiResponse($type);
        $response->setCode($code);
        $response->setReason($reason);
        $response->setBody($body); // Body is file contents if successful
        $response->setJson($json); // is null when successful
        $stream->rewind();
        $response->setStream($stream);

        return $response;
    }
}
