<?php
/**
 * excc-php-api (c) 2018 EXCCoin
 * Created by: widelec
 * Date: 12.06.2018
 */

namespace EXCCoin\Data;


class BlockHeader
{
    /**
     * @var array
     */
    protected $data;

    /**
     * Block header constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (!isset($data['hash']) || !isset($data['height'])) {
            throw new \RuntimeException('Wrong block header data!');
        }

        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->data['hash'];
    }

    /**
     * @return int
     */
    public function getConfirmations()
    {
        if (isset($this->data['confirmations'])) {
            return intval($this->data['confirmations']);
        }

        return 0;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return intval($this->data['height']);
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return intval($this->data['version']);
    }

    /**
     * @return string
     */
    public function getMerkleRoot()
    {
        return $this->data['merkleroot'];
    }

    /**
     * @return string
     */
    public function getStakeRoot()
    {
        return $this->data['stakeroot'];
    }

    /* TODO: add getters for fields related to voting */

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        $time = new \DateTime();
        $time->setTimestamp($this->data['time']);
        return $time;
    }

    /**
     * @return int
     */
    public function getNonce()
    {
        return intval($this->data['nonce']);
    }

    /**
     * @return int
     */
    public function getBits()
    {
        return intval(hexdec($this->data['bits']));
    }

    /**
     * @return double
     */
    public function getSBits()
    {
        return doubleval($this->data['sbits']);
    }

    /**
     * @return double
     */
    public function getDifficulty()
    {
        return doubleval($this->data['difficulty']);
    }

    /**
     * @return string
     */
    public function getPreviousBlockHash()
    {
        return $this->data['previousblockhash'];
    }

    /**
     * @return false|string
     */
    public function getNextBlockHash()
    {
        if (isset($this->data['nextblockhash'])) {
            return $this->data['nextblockhash'];
        }

        return false;
    }
}