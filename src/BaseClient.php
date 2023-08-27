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
    protected bool $verifySsl = true;

    protected string $userAgent = 'SchenkeIo-ApiClient';

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
     * @param  array<string,mixed>  $urlData
     * @return array<string,mixed>
     */
    public function post(
        string $urlPart,
        array $data,
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
    public function get(string $urlPart, array $data = []): array
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
        if ($this->debug) {
            echo "<<< return response\n";
            print_r($response);
        }
        if ($response === false) {
            $return['_error'] = curl_error($ch);
            $return['_url'] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            $return['_status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        } else {
            $return = (array) json_decode($response, true);
        }
        curl_close($ch);

        return $return;
    }

    private function getConnection(array $headers, string $urlPart = '', array $data = []): CurlHandle
    {
        $urlPart = ltrim($urlPart, '/');
        $url = rtrim($this->url, '/')."/$urlPart";
        if (count($data) > 0) {
            $url .= '?'.http_build_query($data);
        }
        if ($this->debug) {
            echo ">> getConnection url: $url\n";
            print_r($headers);
            print_r($data);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->debug);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        if (! $this->verifySsl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        return $ch;
    }
}
