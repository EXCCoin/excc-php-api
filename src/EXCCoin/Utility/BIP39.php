<?php
/**
 * excc-php-api (c) 2018 EXCCoin
 * Created by: widelec
 * Date: 24.07.2018
 */

namespace EXCCoin\Utility;


class BIP39
{
    const CHUNK_SIZE = 11;

    private $words;

    public function __construct($language = 'en')
    {
        $this->words = $this->loadWordsList($language);

        if ($this->words === false) {
            throw new \RuntimeException("Failed to read words list for $language");
        }
    }

    private function loadWordsList($language)
    {
        try
        {
            $data = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'WordsLists'.DIRECTORY_SEPARATOR.$language.'.txt');
        } catch (\Exception $e) {
            return false;
        }

        if ($data === false) {
            return false;
        }

        $words = array_filter(explode("\n", $data), 'strlen');

        return $words;
    }

    private function bytesToBits($data)
    {
        $result = [];

        for ($i = 0, $length = strlen($data); $i < $length; $i++) {
            $byte = ord(substr($data, $i, 1));
            $bits = strrev(decbin($byte));

            for ($j = 0; $j < 8; $j++) {
                $bit = substr($bits, $j, 1);
                $pos = ($i * 8) + 8 - ($j + 1);

                $result[$pos] = $bit == 0 ? '0' : '1';
            }
        }
        ksort($result);
        return $result;
    }

    public function checksum($data)
    {
        $hash = hash('sha256', $data, true);
        $length = strlen($data) * 8 / 32;
        $bits = $this->bytesToBits($hash);

        return array_slice($bits, 0, $length);
    }

    public function checksumed($data)
    {
        return array_merge($this->bytesToBits($data), $this->checksum($data));
    }

    public function encode($data)
    {
        $bits = $this->checksumed($data);
        $result = [];

        for ($i = 0, $length = count($bits); $i < $length; $i += self::CHUNK_SIZE) {
            $slice = array_slice($bits, $i, self::CHUNK_SIZE);
            $string = implode('', $slice);
            $int = intval($string, 2);

            $result[] = $this->words[$int];
        }

        return $result;
    }

    public function decode($words, $password = '')
    {
        $mnemonic = implode(' ', array_map('strtolower', $words));
        $salt = 'mnemonic'.$password;

        return hash_pbkdf2('sha512', $mnemonic, $salt, 2048, 64, true);
    }
}
