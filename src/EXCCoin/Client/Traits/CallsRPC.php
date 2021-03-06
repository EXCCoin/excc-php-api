<?php
/**
 * excc-php-api (c) 2018 EXCCoin
 * Created by: widelec
 * Date: 11.06.2018
 */

namespace EXCCoin\Client\Traits;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

trait CallsRPC
{
    /**
     * @var Client
     */
    protected $guzzle;

    /**
     * @var string
     */
    protected $lastErrorMsg;

    /**
     * @var int
     */
    protected $lastErrorCode;

    /**
     * @var int
     */
    protected $id = 1;

    /**
     * Data client constructor.
     *
     * @param $url
     * @param array $options Options for Guzzle Client.
     */
    public function __construct($url, $options = [])
    {
        $this->guzzle = new Client(array_merge([
            'base_uri'          => $url,
            'verify'            => false, // TODO: this really should not be default.
            'allow_redirects'   => false,
            'curl'              => [
                CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            ],
        ], $options));
    }

    /**
     * @param Client $guzzle
     *
     * @return $this
     */
    public function setGuzzle(Client $guzzle)
    {
        $this->guzzle = $guzzle;
        return $this;
    }

    /**
     * Returns last error in form of human readable message.
     *
     * @return string
     */
    public function getLastError()
    {
        return $this->lastErrorMsg;
    }

    /**
     * Returns last error code.
     *
     * @return int
     */
    public function getLastCode()
    {
        return $this->lastErrorCode;
    }

    /**
     * Does the jsonrpc request and returns decoded response as array.
     *
     * @param              $method
     * @param array        $data
     * @return array|false
     */
    protected function request($method, $data = [])
    {
        try {
            $req = [
                'jsonrpc' => '1.0',
                'method' => $method,
                'params' => $data,
                'id' => $this->id++,
            ];

            $response = $this->guzzle->request('post', '/', [
                RequestOptions::JSON => $req,
            ]);

            $body = $response->getBody();
            $result = @json_decode($body, true);

            if ($result === null || $result === false) {
                $this->lastErrorMsg = sprintf("JSON parse error: %s", json_last_error_msg());
                return false;
            }

            if ($result['error']) {
                $this->lastErrorCode = intval($result['error']['code']);
                $this->lastErrorMsg = sprintf("JSON-RPC error %d: %s",
                    $result['error']['code'], $result['error']['message']);
                return false;
            }


            return $result['result'];
        } catch (GuzzleException $e) {
            $this->lastErrorMsg = sprintf("Connection error: %s", $e->getMessage());
            return false;
        }
    }

    /**
     * Does the HTTP(S) GET request and returns received data as array.
     *
     * @param string       $path
     * @return array|false
     */
    protected function get($path)
    {
        try {
            /** @noinspection PhpParamsInspection */
            $result = @json_decode($this->guzzle->get($path)->getBody(), true);

            if ($result === null || $result === false) {
                $this->lastErrorMsg = sprintf("JSON parse error: %s", json_last_error_msg());
                return false;
            }

            return $result;
        } catch (\Exception $e) {
            $this->lastErrorMsg = sprintf("Connection error: %s", $e->getMessage());
            return false;
        }
    }

    /**
     * Does the HTTP(S) POST request and returns received data as array.
     *
     * @param string       $path
     * @param array        $data
     * @return array|false
     */
    protected function post($path, $data = [])
    {
        try {
            /** @noinspection PhpParamsInspection */
            $response = $this->guzzle->post($path, [
                RequestOptions::JSON => $data,
            ]);

            $result = @json_decode($response->getBody(), true);

            if ($result === null || $result === false) {
                $this->lastErrorMsg = sprintf("JSON parse error: %s", json_last_error_msg());
                return false;
            }

            return $result;
        } catch (\Exception $e) {
            $this->lastErrorMsg = sprintf("Connection error: %s", $e->getMessage());
            return false;
        }
    }
}