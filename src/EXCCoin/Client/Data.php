<?php namespace EXCCoin\Client;

use EXCCoin\Client\Traits\CallsRPC;
use EXCCoin\Data\Transaction;

class Data
{
    use CallsRPC;

    /**
     * Get address raw presentation.
     *
     * @param string         $address   Address
     * @param \DateTime|null $from      Filter older transactions
     *
     * @return array|bool|Transaction[]
     */
    public function getAddressRaw($address, \DateTime $from = null)
    {
        $result = false;

        $response = $this->get(sprintf('/api/address/%s/raw', $address));

        if ($response !== false && is_array($response)) {
            $result = [];
            foreach ($response as $data) {
                $transaction = new Transaction($data);

                if ($transaction->getOutAmount($address) !== false) {
                    if ($from === null || $transaction->getTime() > $from) {
                        $result[] = $transaction;
                    }
                }
            }
        }

        return $result;
    }
}
