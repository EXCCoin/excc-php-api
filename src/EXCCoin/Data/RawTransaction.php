<?php
/**
 * excc-php-api (c) 2018 EXCCoin
 * Created by: widelec
 * Date: 13.06.2018
 */

namespace EXCCoin\Data;


class RawTransaction
{
    /**
     * @var string
     */
    protected $data;

    public function __construct($data)
    {
        if (((bool)preg_match('/^[0-9a-fA-F]{2,}$/i', $data) !== true)
            || (strlen($data)) % 2 !== 0) {
            throw new \RuntimeException('Invalid hex data');
        }

        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getHexData()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return intval(strlen($this->data) / 2);
    }

    /**
     * @return int
     */
    public function getSizeInKB()
    {
        return intval(ceil($this->getSize() / 1024.));
    }
}
