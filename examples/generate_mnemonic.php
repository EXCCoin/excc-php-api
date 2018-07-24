<?php
/**
 * excc-php-api (c) 2018 EXCCoin
 * Created by: widelec
 * Date: 24.07.2018
 */

use EXCCoin\Utility\BIP39;

include __DIR__.'/../vendor/autoload.php';

$testnet = \EXCCoin\TestNet::instance();

// Generate seed for test net
$seed = \EXCCoin\Crypto\ExtendedKey::generateSeed($testnet);

$bip39 = new BIP39('en');

$words = $bip39->encode($seed);

/*
 * for convenience format output like exccwallet
 */
echo "Your wallet generation seed is:\n";
echo implode(' ', array_slice($words, 0, 6))."\n";
echo implode(' ', array_slice($words, 6, 6))."\n";
echo implode(' ', array_slice($words, 12, 6))."\n";
echo implode(' ', array_slice($words, 18, 6))."\n";
echo sprintf("Hex: %s\n", bin2hex($seed));
