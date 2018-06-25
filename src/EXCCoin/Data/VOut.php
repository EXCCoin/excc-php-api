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
        return $this->data['scriptPubKey']['addresses'];
    }

    /**
     * @return int
     */
    public function countAddresses()
    {
        return count($this->data['scriptPubKey']['addresses']);
    }
}