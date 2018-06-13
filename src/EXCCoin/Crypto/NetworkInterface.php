<?php namespace EXCCoin\Crypto;

use EXCCoin\Client\Chain;
use EXCCoin\Client\Data as DataClient;
use EXCCoin\Client\Chain as ChainClient;

interface NetworkInterface
{
    /**
     * Create network instance.
     *
     * @return NetworkInterface
     */
    public static function instance();

    /**
     * Apply network defined hash on key.
     *
     * @param string $key
     *
     * @return string
     */
    public function hashKey256($key);

    /**
     * @param bool $isPrivate
     *
     * @return string
     */
    public function HDVersion($isPrivate);

    /**
     * @return string
     */
    public function HDPubKeyHashAddrId();

    /**
     * @return DataClient
     */
    public function getDataClient();

    /**
     * @param string                      $username
     * @param string                      $password
     * @return null|ChainClient
     */
    public function getChainClient($username, $password);

    /**
     * Get base58 encoded extended key address.
     *
     * @param string $key
     *
     * @return string
     */
    public function base58EncodeAddress($key);

    /**
     * Get base58 encoded payload + 4 bytes checksum of payload.
     *
     * @param string $payload
     *
     * @return string 
     */
    public function base58EncodeChecksum($payload);

    /**
     * Get base58 4 bytes checksum of payload.
     *
     * @param string $payload
     *
     * @return string
     */
    public function base58Checksum($payload);
}
