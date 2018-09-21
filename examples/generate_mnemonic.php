<?php
/**
 * excc-php-api (c) 2018 EXCCoin
 * Created by: widelec
 * Date: 24.07.2018
 */

use EXCCoin\Utility\BIP39;

include __DIR__.'/../vendor/autoload.php';

// Generate entropy
$entropy = \EXCCoin\Crypto\ExtendedKey::generateEntropy();

/* encode to mnemonic */
$bip39 = new BIP39('en');
$words = $bip39->encode($entropy);

/* decode seed from mnemonic */
$seed = $bip39->decode($words);

if (!\EXCCoin\Crypto\ExtendedKey::verifySeed($seed, \EXCCoin\TestNet::instance())) {
    die("Invalid seed\n");
}

/*
 * for convenience format output like exccwallet
 */
echo "Your wallet generation seed is:\n";
echo implode(' ', array_slice($words, 0, 6))."\n";
echo implode(' ', array_slice($words, 6, 6))."\n";
echo implode(' ', array_slice($words, 12, 6))."\n";
echo implode(' ', array_slice($words, 18, 6))."\n";
echo sprintf("Hex: %s\n", bin2hex($seed));
