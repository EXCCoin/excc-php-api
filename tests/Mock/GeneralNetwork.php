<?php namespace EXCCoin\Tests\Mock;

use EXCCoin\Crypto\ExchangecoinNetwork;

class GeneralNetwork extends ExchangecoinNetwork
{
    const HD_PUBLIC_KEY         = "\x04\x88\xb2\x1e"; // xpub
    const HD_PRIVATE_KEY        = "\x04\x88\xad\xe4"; // xprv
    const PUB_KEY_HASH_ADDR_ID  = "\x0f\x21";         // Ts
    const WIF_PRIVATE_KEY_ID    = "\x23\x0e";         // Pt


    public function getDataClient()
    {
        return null;
    }

    public function getChainClient($username, $password)
    {
        return null;
    }

    public function getNotifierClient()
    {
        return null;
    }

    public static function instance()
    {
        return new static();
    }

    public function hashKey256($key)
    {
        return hash('sha256', $key, true);
    }

    public function HDVersion($isPrivate)
    {
        // mainnet: 0x0488B21E public, 0x0488ADE4 private; testnet: 0x043587CF public, 0x04358394 private
        return $isPrivate ? self::HD_PRIVATE_KEY : self::HD_PUBLIC_KEY;
    }

    public function HDPubKeyHashAddrId()
    {
        return static::PUB_KEY_HASH_ADDR_ID;
    }

    public function WIFPrivKeyId()
    {
        return self::WIF_PRIVATE_KEY_ID;
    }
}
