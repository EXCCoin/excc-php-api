<?php namespace EXCCoin\Tests\Crypto;

use EXCCoin\Crypto\ExtendedKey;
use EXCCoin\Crypto\NetworkFactory;
use EXCCoin\Client\Data as DataClient;
use EXCCoin\Client\Chain as ChainClient;
use EXCCoin\MainNet;
use EXCCoin\TestNet;
use PHPUnit\Framework\TestCase;

class NetworkTest extends TestCase
{
    public function test_testnet()
    {
        $testnet = TestNet::instance();
        $seed = hex2bin('000102030405060708090a0b0c0d0e0f');
        $master = ExtendedKey::newMaster($seed, $testnet);

        $this->assertEquals('tprvZUo1ZuEfLLFWfAYiMVaoDV1EeLmbSRuNzaSh7F4awft7dm8nHfFAFZyobWQyV8Qr26r8M2CmNw6nEb35HaECWFGy1vzx2ZGdyfBeacrhh3a', (string) $master);
        $this->assertEquals('tpubVhnMyQmZAhoosedBTX7oacwyCNc5qtdEMoNHudUCW1R6WZTvqCZQoNJHSn4H11puwdk4qyDv2ET637EDap4r8HH3odjBC5nEjmnPcvoqJyL', (string) $master->neuter());
        $this->assertEquals('TsVnSo9TUfZh6vH6QWjek3YP8dGvfYQGnCZ', $master->getAddress());
    }

    public function test_network_factory()
    {
        $this->assertInstanceOf(TestNet::class, NetworkFactory::fromExtendedKeyVersion(TestNet::HD_PRIVATE_KEY_ID));
        $this->assertInstanceOf(TestNet::class, NetworkFactory::fromExtendedKeyVersion(TestNet::HD_PUBLIC_KEY_ID));
        $this->assertInstanceOf(MainNet::class, NetworkFactory::fromExtendedKeyVersion(MainNet::HD_PRIVATE_KEY_ID));
        $this->assertInstanceOf(MainNet::class, NetworkFactory::fromExtendedKeyVersion(MainNet::HD_PUBLIC_KEY_ID));

        $testnet = TestNet::instance();

        $dataClient = $testnet->getDataClient();
        $this->assertInstanceOf(DataClient::class, $dataClient);

        $chainClient = $testnet->getChainClient('user', 'password');
        $this->assertInstanceOf(ChainClient::class, $chainClient);

        $mainnet = MainNet::instance();

        $dataClient = $mainnet->getDataClient();
        $this->assertInstanceOf(DataClient::class, $dataClient);

        $chainClient = $mainnet->getChainClient('user', 'password');
        $this->assertInstanceOf(ChainClient::class, $chainClient);
    }

    public function test_network_factory_exception()
    {
        $this->expectExceptionMessage('Unknown extended key version.');
        NetworkFactory::fromExtendedKeyVersion('fasdfasdf');
    }

    public function test_extended_key_wrong_checksum()
    {
        $this->expectExceptionMessage('Wrong checksum on encoding extended key!');

        $network = TestNet::instance();
        $network->extendedKeyBase58Decode('xprv9s21ZrQH143K4WLynvv1LXUFucq4mVd3igo4fqXUstW81m123DfSScqqVPzpm1c3K9ugwfZFnsmqX5cLESagEmAaCC4nEZB7XqQAWvCafNk');
    }

    public function test_extended_key_wrong_encode()
    {
        $this->expectExceptionMessage('Wrong key length.');

        $network = TestNet::instance();
        $network->extendedKeyBase58Decode($network->base58()->encode('fasdfadf'));
    }
}
