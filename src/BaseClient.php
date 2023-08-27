<?php

namespace SchenkeIo\ApiClient;

use CurlHandle;

abstract class BaseClient
{
    /*
     * can be overwritten in extended classes
     *
     * @var bool
     */
    protected bool $debug = false;

    /**
     * can be overwritten in extended classes
     *
     * @var string[]
     */
    protected array $standardHeaders = [];

    /**
     * can be overwritten in extended classes
     */
    protected ApiType $type = ApiType::POST;

    /**
     * can be overwritten in extended classes
     */
    protected bool $verifySsl = false;

    public function __construct(public readonly string $url)
    {
    }

    /**
     * additional headers for authorization
     *
     * @return string[]
     */
    abstract public function getAuthHeader(): array;

    /**
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function post(
        array $data,
        string $urlPart = '',
        array $urlData = []
    ): array {
        $headers = $this->getHeaders();

        if ($this->type->isJson()) {
            $data = json_encode($data);
            $headers[] = 'Content-Length: '.strlen($data);
        }
        $ch = self::getConnection($headers, $urlPart, $urlData);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        return $this->returnResponse($ch);
    }

    /**
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function get(array $data = [], string $urlPart = ''): array
    {
        $ch = $this->getConnection($this->getHeaders(), $urlPart, $data);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        return $this->returnResponse($ch);
    }

    private function getHeaders(): array
    {
        return array_merge(
            $this->standardHeaders,
            $this->getAuthHeader(),
            $this->type->getHeaders()
        );
    }

    /**
     * @return array<string,mixed>
     */
    private function returnResponse(CurlHandle $ch): array
    {
        $response = curl_exec($ch);
        if ($response === false) {
            $return['error'] = curl_error($ch);
        } else {
            $return = (array) json_decode($response, true);
        }
        curl_close($ch);

        return $return;
    }

    private function getConnection(array $headers, string $urlPart = '', array $data = []): CurlHandle
    {
        $urlPart = ltrim($urlPart, '/');
        $url = $this->url."/$urlPart";
        if (count($data) > 0) {
            $url .= '?'.http_build_query($data);
        }
        if ($this->debug) {
            echo ">> url: $url\n";
            print_r($headers);
            print_r($data);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        if (! $this->verifySsl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        return $ch;
    }
}
