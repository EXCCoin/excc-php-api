<?php
/**
 * excc-php-api (c) 2018 EXCCoin
 * Created by: widelec
 * Date: 12.06.2018
 */

namespace EXCCoin\Data;


class Block extends BlockHeader
{
    /**
     * Block constructor.
     * @param array       $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        if (!isset($this->data['size'])
            || (!isset($this->data['tx']) && !isset($this->data['stx']))) {
            throw new \RuntimeException('Invalid block data!');
        }
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return intval($this->data['size']);
    }

    /**
     * @return int
     */
    public function getRevocations()
    {
        return intval($this->data['revocations']);
    }

    /**
     * @return string[]
     */
    public function getTransactionsHashes()
    {
        return $this->data['tx'];
    }

    /**
     * @return string[]
     */
    public function getStakeTransactionsHashes()
    {
        return $this->data['stx'];
    }
}