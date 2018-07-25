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
        if (!isset($data['txid']) && !isset($data['coinbase']) && !isset($this->data['stakebase'])) {
            throw new \RuntimeException('Missing input source!');
        }

        /* coinbase and stakebase vins will not have a coresponding block data */
        if (!isset($data['coinbase']) && !isset($this->data['stakebase'])) {
            if (!isset($data['blockheight']) || !isset($data['blockindex'])) {
                throw new \RuntimeException('Missing block data!');
            }
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

        if (isset($this->data['stakebase'])) {
            return 'stakebase';
        }

        return strval($this->data['txid']);
    }

    /**
     * @return string
     */
    public function getCoinbase()
    {
        if (isset($this->data['txid'])) {
            return 'input';
        }

        if (isset($this->data['stakebase'])) {
            return 'stakebase';
        }

        return strval($this->data['coinbase']);
    }

    /**
     * @return string
     */
    public function getStakebase()
    {
        if (isset($this->data['coinbase'])) {
            return 'coinbase';
        }

        if (isset($this->data['txid'])) {
            return 'input';
        }

        return strval($this->data['stakebase']);
    }

    /**
     * @return int|null
     */
    public function getVOut()
    {
        if (!isset($this->data['vout'])) {
            return null;
        }

        return intval($this->data['vout']);
    }

    /**
     * @return int|null
     */
    public function getBlockHeight()
    {
        if (!isset($this->data['blockheight'])) {
            return null;
        }

        return intval($this->data['blockheight']);
    }

    /**
     * @return int|null
     */
    public function getBlockIndex()
    {
        if (!isset($this->data['blockindex'])) {
            return null;
        }

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

    /**
     * @return int|null
     */
    public function getTree()
    {
        if (!isset($this->data['tree'])) {
            return null;
        }

        return intval($this->data['tree']);
    }

    /**
     * @return int
     */
    public function getSequence()
    {
        return intval($this->data['sequence']);
    }
}