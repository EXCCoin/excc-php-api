<?php namespace EXCCoin\Data;

class Transaction
{
    /**
     * @var array
     */
    protected $data;

    /**
     * Transaction constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (!isset($data['txid'])) {
            throw new \RuntimeException('Wrong transaction data!');
        }

        $this->data = $data;
    }

    /**
     * @param string $forAddress
     *
     * @return float|bool Return amount or FALSE on failure.
     */
    public function getOutAmount($forAddress)
    {
        if (isset($this->data['vout']) && is_array($this->data['vout'])) {
            foreach ($this->data['vout'] as $vout) {

                if (isset($vout['scriptPubKey']['addresses']) && is_array($vout['scriptPubKey']['addresses'])) {
                    foreach ($vout['scriptPubKey']['addresses'] as $address) {

                        if ($forAddress === $address && isset($vout['value'])) {
                            return (float) $vout['value'];
                        }
                    }
                }

            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getTxid()
    {
        return $this->data['txid'];
    }

    /**
     * @return null|\DateTime
     */
    public function getTime()
    {
        if (!isset($this->data['time'])) {
            return null;
        }

        $time = new \DateTime();
        $time->setTimestamp($this->data['time']);
        return $time;
    }

    /**
     * @return int
     */
    public function getConfirmations()
    {
        if (!isset($this->data['confirmations'])) {
            return 0;
        }

        return intval($this->data['confirmations']);
    }

    /**
     * @return null|string
     */
    public function getBlockHash()
    {
        if (!isset($this->data['blockhash'])) {
            return null;
        }

        return strval($this->data['blockhash']);
    }

    public function getBlockTime()
    {
        if (!isset($this->data['blocktime'])) {
            return null;
        }

        $time = new \DateTime();
        $time->setTimestamp($this->data['blocktime']);
        return $time;
    }

    /**
     * @return int
     */
    public function countVIns()
    {
        return count($this->data['vin']);
    }

    /**
     * @param $index
     * @return null|VIn
     */
    public function getVIn($index)
    {
        if (!isset($this->data['vin'][$index])) {
            return null;
        }

        return new VIn($this->data['vin'][$index]);
    }

    /**
     * @return int
     */
    public function countVOuts()
    {
        return count($this->data['vout']);
    }

    /**
     * @param $index
     * @return null|VOut
     */
    public function getVOut($index)
    {
        if (!isset($this->data['vout'][$index])) {
            return null;
        }

        return new VOut($this->data['vout'][$index]);
    }
}
