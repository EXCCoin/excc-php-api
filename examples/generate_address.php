<?php
include __DIR__.'/../vendor/autoload.php';

$testnet = \EXCCoin\TestNet::instance();

// Generate entropy
$entropy = \EXCCoin\Crypto\ExtendedKey::generateEntropy(16);

// Generate mnemonic
$bip39 = new \EXCCoin\Utility\BIP39();
$mnemonic = $bip39->encode($entropy);

echo sprintf("Wallet mnemonic: %s\n", implode(' ', $mnemonic));

// Decode mnemonic to seed
$seed = $bip39->decode($mnemonic);

if (!\EXCCoin\Crypto\ExtendedKey::verifySeed($seed, $testnet)) {
    die("Invalid seed\n");
}

echo sprintf("Seed hex: %s\n", bin2hex($seed));

// Generate HD master key
$master = \EXCCoin\Crypto\ExtendedKey::newMaster($seed, $testnet);
echo sprintf("Master HD key: %s\n\n", $master);

// Default account HD private key
$defaultAccountPrivateKey = $master
    ->deriveCoinTypeKey() /* NOTE: derives *both* BIP44 purpose and coin type */
    ->deriveAccountKey(0);

// Default account HD public key
$defaultAccountPublicKey = $defaultAccountPrivateKey->neuter();

echo sprintf("Default account HD private key: %s\n", $defaultAccountPrivateKey);
echo sprintf("Default account HD public key: %s\n", $defaultAccountPublicKey);

// Default address
$defaultAddress = $defaultAccountPublicKey
    ->publicChildKey(0)
    ->publicChildKey(0)
    ->getAddress();

echo sprintf("Default address: %s\n", $defaultAddress);

$privKey = $defaultAccountPrivateKey
    ->privateChildKey(0)
    ->privateChildKey(0)
    ->privateKey();

echo sprintf("Default address private key: %s\n", $testnet->base58EncodePrivateKey($privKey));
