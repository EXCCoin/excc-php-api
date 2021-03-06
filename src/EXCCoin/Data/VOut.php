<?php
/**
 * excc-php-api (c) 2018 EXCCoin
 * Created by: widelec
 * Date: 14.06.2018
 */

namespace EXCCoin\Data;


class VOut
{
    /**
     * @var array
     */
    protected $data;

    /**
     * VOut constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (!isset($data['value']) || !isset($data['n']) || !isset($data['version'])
            || !isset($data['scriptPubKey'])) {
            throw new \RuntimeException('Wrong transaction data!');
        }

        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        $value = round($this->data['value'] * 1e8);

        return intval($value);
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return intval($this->data['n']);
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return intval($this->data['version']);
    }

    /**
     * @return string[]
     */
    public function getAddresses()
    {
        if (!isset($this->data['scriptPubKey']['addresses'])) {
            return [];
        }

        return $this->data['scriptPubKey']['addresses'];
    }

    /**
     * @return int
     */
    public function countAddresses()
    {
        if (!isset($this->data['scriptPubKey']['addresses'])) {
            return 0;
        }

        return count($this->data['scriptPubKey']['addresses']);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->data['scriptPubKey']['type'];
    }

    /**
     * @return int|null
     */
    public function getRequestedSignaturesCount()
    {
        if (!isset($this->data['scriptPubKey']['reqSigs'])) {
            return null;
        }

        return intval($this->data['scriptPubKey']['reqSigs']);
    }
}