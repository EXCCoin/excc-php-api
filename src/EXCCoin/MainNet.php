<?php namespace EXCCoin;

use EXCCoin\Crypto\ExchangecoinNetwork;
use EXCCoin\Client\Data as DataClient;
use EXCCoin\Client\Chain as ChainClient;
use EXCCoin\Client\Notifier as NotifierClient;

class MainNet extends ExchangecoinNetwork
{
    const DATA_URL = "http://explorer2.excc.co";
    const CHAIN_URL = "https://localhost:9109";
    const NOTIFIER_URL = "http://localhost:9119";

    const HD_PRIVATE_KEY_ID     = "\x04\x88\xad\xe4"; // xprv
    const HD_PUBLIC_KEY_ID      = "\x04\x88\xb2\x1e"; // xpub

    const PUB_KEY_HASH_ADDR_ID  = "\x21\xb9"; // 22
    const WIF_PRIVATE_KEY_ID    = "\x80"; // 5 (uncompressed) or K (compressed)

    /**
     * @return MainNet
     */
    public static function instance()
    {
        return new MainNet();
    }

    /**
     * @param bool $isPrivate
     *
     * @return string
     */
    public function HDVersion($isPrivate)
    {
        return $isPrivate ? self::HD_PRIVATE_KEY_ID : self::HD_PUBLIC_KEY_ID;
    }

    /**
     * @return string
     */
    public function HDPubKeyHashAddrId()
    {
        return self::PUB_KEY_HASH_ADDR_ID;
    }

    /**
     * @return string
     */
    public function WIFPrivKeyId()
    {
        return self::WIF_PRIVATE_KEY_ID;
    }

    /**
     * @param string                $url
     * @return \EXCCoin\Client\Data
     */
    public function getDataClient($url = self::DATA_URL)
    {
        return new DataClient($url);
    }

    /**
     * @param string            $username
     * @param string            $password
     * @param string            $url
     * @return null|ChainClient
     */
    public function getChainClient($username, $password, $url = self::CHAIN_URL)
    {
        return new ChainClient($url, ['auth' => [$username, $password]]);
    }

    /**
     * @param string               $url
     * @return null|NotifierClient
     */
    public function getNotifierClient($url = self::NOTIFIER_URL)
    {
        return new NotifierClient($url);
    }
}
