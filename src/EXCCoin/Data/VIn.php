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
        if (!isset($data['txid']) || !isset($data['vout']) || !isset($data['blockheight'])
            || !isset($data['blockindex'])) {
            throw new \RuntimeException('Wrong transaction data!');
        }

        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getTxId()
    {
        return $this->data['txid'];
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