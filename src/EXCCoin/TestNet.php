<?php namespace EXCCoin;

use EXCCoin\Crypto\ExchangecoinNetwork;
use EXCCoin\Client\Data as DataClient;
use EXCCoin\Client\Chain as ChainClient;
use EXCCoin\Client\Notifier as NotifierClient;

class TestNet extends ExchangecoinNetwork
{
    const DATA_URL = "https://testnet.dcrdata.org";
    const CHAIN_URL = "https://localhost:19109";
    const NOTIFIER_URL = "http://localhost:19119";

    const HD_PUBLIC_KEY_ID      = "\x04\x35\x87\xd1"; // tpub
    const HD_PRIVATE_KEY_ID     = "\x04\x35\x83\x97"; // tprv
    const PUB_KEY_HASH_ADDR_ID  = "\x0f\x21"; // Ts

    /**
     * @return TestNet
     */
    public static function instance()
    {
        return new TestNet();
    }

    /**
     * @param bool    $isPrivate
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
     * @param string                $url
     * @return \EXCCoin\Client\Data
     */
    public function getDataClient($url = self::DATA_URL)
    {
        return new DataClient($url);
    }

    /**
     * @inheritdoc
     */
    public function getChainClient($username, $password)
    {
        return new ChainClient(self::CHAIN_URL, ['auth' => [$username, $password]]);
    }

    /**
     * @inheritdoc
     */
    public function getNotifierClient()
    {
        return new NotifierClient(self::NOTIFIER_URL);
    }
}
