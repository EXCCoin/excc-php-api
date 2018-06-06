# EXCC (ExchangeCoin) PHP API

PHP API for the [ExchangeCoin](https://excc.co) Cryptocurrency

## Installation

Add composer package to your project
```bash
composer require EXCCoin/excc-php-api
```

Make sure [GMP PHP extesion](http://php.net/manual/en/book.gmp.php) is installed. In ubuntu:
```bash
sudo apt install php7.0-gmp
```

### From repository

You also can clone git package with
```bash
git clone https://github.com/EXCCoin/excc-php-api.git
```

But still you will need to fetch library dependencies with [composer](https://getcomposer.org/doc/00-intro.md).
```bash
copmoser install --no-dev
```

Don't forget to include composer autoloader.
```php
include __DIR__.'/../vendor/autoload.php';
```

## Usage examples

Library have wide functionality, so you could find usage exmaples in `examples` library or looking into PHPUnit tests.

### Generating seed

First of all we need to get Network intance to start working with library.

```php
$testnet = \EXCCoin\TestNet::instance();
```

And mainnet accordingly
```php
$mainnet = \EXCCoin\MainNet::instance();
```

Now lets generate a seed, that will be also verified for usage on testnet.
Defaut account and branch address will be derivded to verify the seed.

```php
$seed = \EXCCoin\Crypto\ExtendedKey::generateSeed($testnet);
```

### HD Wallets

When we have usable seed we can create HD master key.

```php
$master = \EXCCoin\Crypto\ExtendedKey::newMaster($seed, $testnet);
```

`newMaster` method will return `ExtendedKey` object, that have variant API for working with HD wallets.

#### `ExtendedKey::privateChildKey($index)`

Derives HD private child key from parent HD private key, returns `ExtendedKey` object.

#### `ExtendedKey::hardenedChildKey($index)`

Derives HD hardened child key from parent HD private key, returns `ExtendedKey` object.

Can't be dervied from HD public key.

#### `ExtendedKey::publicChildKey($index)`

Derives HD public child key from parent HD private or public key, returns `ExtendedKey` object.

#### `ExtendedKey::neuter`

Verify that extended key is public, returns `ExtendedKey` object.

### Default account

Using this basic methods we can derive default account HD private and public keys accourding to [BIP44](https://github.com/bitcoin/bips/blob/master/bip-0044.mediawiki).

HD path (m\44'\42'\0')

```php
$defaultAccountPrivateKey = $master
    ->hardenedChildKey(44)
    ->hardenedChildKey(42)
    ->hardenedChildKey(0);

$defaultAccountPublicKey = $master->neuter();
```

`ExtendedKey` implements `__toString()` method, so you can easily get Base58 representation of HD key.

```php
echo sprintf("Default account HD private key: %s\n", $defaultAccountPrivateKey);
echo sprintf("Default account HD public key: %s\n", $defaultAccountPublicKey);
```

From default account we can derive 0 branch and 0 index and the get default address.

```php
$defaultAddress = $defaultAccountPublicKey
    ->publicChildKey(0)
    ->publicChildKey(0)
    ->getAddress();

echo sprintf("Default address: %s\n", $defaultAddress);
```


