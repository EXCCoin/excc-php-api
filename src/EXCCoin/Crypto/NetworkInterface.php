<?php namespace EXCCoin\Crypto;

use EXCCoin\Client\Chain;
use EXCCoin\Client\Data as DataClient;
use EXCCoin\Client\Chain as ChainClient;
use EXCCoin\Client\Notifier as NotifierClient;

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
     * @return string
     */
    public function WIFPrivKeyId();

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
     * @return null|NotifierClient
     */
    public function getNotifierClient();

    /**
     * Get base58 encoded extended key address.
     *
     * @param string $key
     *
     * @return string
     */
    public function base58EncodeAddress($key);

    /**
     * Get decoded address
     *
     * @param $address
     * @return string
     * @throws \Exception
     */
    public function base58DecodeAddress($address);

    /**
     * Get base58 encoded private key.
     *
     * @param string $key
     * @return string
     */
    public function base58EncodePrivateKey($key);

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
