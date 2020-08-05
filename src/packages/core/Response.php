<?php
namespace Status\Core;

use Status\System\Headers;

/**
 * Class Response
 * @package Status\Core
 */
final class Response
{
    /**
     * @var
     */
    private static $response;

    /**
     * @param array $array
     * @param bool $contentType
     * @return Response
     */
    public static function json(array $array, bool $contentType = true): Response
    {
        self::$response = json_encode($array);

        if($contentType){
            header('Content-Type: application/json');
            Headers::setContentType();
        }

        return new self();
    }

    /**
     * @param string $text
     * @param bool $contentType
     * @return Response
     */
    public static function text(string $text, bool $contentType = true): Response
    {
        self::$response = $text;

        if($contentType){
            header('Content-Type: application/json');
            Headers::setContentType();
        }

        return new self();
    }

    /**
     * @param int $statusCode
     * @param string $statusText
     * @return Response
     */
    public function setHeader(int $statusCode = 200, string $statusText = ''): Response
    {
        header("HTTP/2.0 $statusCode");
        header("StatusText: $statusText");

        return new self();
    }

    /**
     * @param int $statusCode
     * @return Response
     */
    public function setStatusCode(int $statusCode = 200): Response
    {
        http_response_code($statusCode);
        return new self();
    }

    /**
     * echo string
     */
    public function echo(): void
    {
        echo self::$response;
    }

    /**
     * @return string
     */
    public function return(): string
    {
        return self::$response;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return self::$response;
    }
}