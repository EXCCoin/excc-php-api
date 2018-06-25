<?php
/**
 * excc-php-api (c) 2018 EXCCoin
 * Created by: widelec
 * Date: 14.06.2018
 */

namespace EXCCoin\Data;


class VIn
{
    /**
     * @var array
     */
    protected $data;

    /**
     * VIn constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (!isset($data['blockheight']) || !isset($data['blockindex'])) {
            throw new \RuntimeException('Missing block data!');
        }

        if (!isset($data['txid']) && !isset($data['coinbase'])) {
            throw new \RuntimeException('Missing input source!');
        }

        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getTxId()
    {
        if (isset($this->data['coinbase'])) {
            return 'coinbase';
        }

        return $this->data['txid'];
    }

    /**
     * @return string
     */
    public function getCoinbase()
    {
        if (isset($this->data['txid'])) {
            return 'input';
        }

        return $this->data['coinbase'];
    }

    /**
     * @return int
     */
    public function getVOut()
    {
        return intval($this->data['vout']);
    }

    /**
     * @return int
     */
    public function getBlockHeight()
    {
        return intval($this->data['blockheight']);
    }

    /**
     * @return int
     */
    public function getBlockIndex()
    {
        return intval($this->data['blockindex']);
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        if (!isset($this->data['amountin'])) {
            return 0;
        }

        $value = round($this->data['amountin'] * 1e8);

        return intval($value);
    }
}