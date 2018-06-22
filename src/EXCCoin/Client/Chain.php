<?php
/**
 * excc-php-api (c) 2018 EXCCoin
 * Created by: widelec
 * Date: 11.06.2018
 */

namespace EXCCoin\Client;


use EXCCoin\Client\Traits\CallsRPC;
use EXCCoin\Data\Block;
use EXCCoin\Data\BlockHeader;
use EXCCoin\Data\RawTransaction;
use EXCCoin\Data\Transaction;

class Chain
{
    use CallsRPC;

    /**
     * Estimates minimum fee per kB needed for transaction
     * to be included in next $numBlocks blocks.
     *
     * @param int $numBlocks
     * @return false|int
     */
    public function getFeeEstimate($numBlocks = 1)
    {
        $result = false;

        $response = $this->request('estimatefee', [$numBlocks]);

        if ($response !== false && is_double($response)) {
            $result = intval(round($response * 1e8));
        }

        return $result;
    }

    /**
     * Returns requested transaction.
     *
     * @param string            $transactionHash
     * @return false|Transaction
     */
    public function getTransaction($transactionHash)
    {
        $result = false;

        $response = $this->request('getrawtransaction', [$transactionHash, 1]);

        if ($response !== false && is_array($response)) {
            $result = new Transaction($response);
        }

        return $result;
    }

    /**
     * Returns highest (latest) block hash.
     *
     * @return false|string
     */
    public function getBestBlockHash()
    {
        $result = false;

        $response = $this->request('getbestblockhash');

        if ($response !== false && is_string($response)) {
            $result = $response;
        }

        return $result;
    }

    /**
     * Returns header of requested block.
     *
     * @param                     $blockHash
     * @return false|BlockHeader
     */
    public function getBlockHeader($blockHash)
    {
        $result = false;

        $response = $this->request('getblockheader', [$blockHash, true]);

        if ($response !== false && is_array($response)) {
            $result = new BlockHeader($response);
        }

        return $result;
    }

    /**
     * Returns requested block.
     *
     * @param              $blockHash
     * @return false|Block
     */
    public function getBlock($blockHash)
    {
        $result = false;

        $response = $this->request('getblock', [$blockHash, true]);

        if ($response !== false && is_array($response)) {
            $result = new Block($response);
        }

        return $result;
    }

    /**
     * Returns transactions relevant to given address.
     *
     * @param string               $address
     * @param int                  $skip
     * @param int                  $count
     * @return false|Transaction[]
     */
    public function getRelevantTransactions($address, $skip = 0, $count = 100)
    {
        $result = false;

        $response = $this->request('searchrawtransactions',
            [$address, 1, $skip, $count]);

        if ($response !== false && is_array($response)) {
            $result = [];

            foreach ($response as $transactionData) {
                $result[] = new Transaction($transactionData);
            }
        }

        return $result;
    }

    /**
     * Returns new (unsigned!) RawTransaction containing requested inputs and outputs.
     *
     * @param array             $inputs UTXOs array in form of
     *                                  [['txid' => $txId, 'vout' => $vOut],
     *                                   ['txid' => $txId2, 'vout' => $vOut2]].
     * @param array             $outputs Addresses and amounts array
     *                                   in form of [$address => $amount, $address2 => $amount2].
     * @return false|RawTransaction
     */
    public function createTransaction(array $inputs, array $outputs)
    {
        $result = false;

        $response = $this->request('createrawtransaction',
            [$inputs, $outputs]);

        if ($response !== false && is_string($response)) {
            $result = new RawTransaction($response);
        }

        return $result;
    }

    /**
     * Decodes provided RawTransaction to Transaction.
     * TODO: implement this in PHP so it can be done without call to RPC.
     *
     * @param RawTransaction     $rawTransaction
     * @return false|Transaction
     */
    public function decodeRawTransaction(RawTransaction $rawTransaction)
    {
        $result = false;

        $response = $this->request('decoderawtransaction',
            [$rawTransaction->getHexData()]);

        if ($response !== false && is_array($response)) {
            $result = new Transaction($response);
        }

        return $result;
    }

    /**
     * Signs RawTransaction using given keys.
     * NOTE: This is using the not yet released RPC method.
     * TODO: Replace this with signing implementation in PHP.
     *
     * @param RawTransaction        $rawTransaction
     * @param string[]              $keys
     * @return false|RawTransaction
     */
    public function signRawTransaction(RawTransaction $rawTransaction, array $keys)
    {
        $result = false;

        $response = $this->request('signrawtxwith',
            [$rawTransaction->getHexData(), $keys]);

        if ($response !== false && is_array($response)
            && isset($response['hex']) && isset($response['complete'])) {
            if ($response['complete']) {
                $result = new RawTransaction($response['hex']);
            }
        }

        return $result;
    }

    /**
     * Broadcasts given RawTransaction to network.
     *
     * @param RawTransaction $rawTransaction
     * @param bool           $allowHighFees
     * @return false|string
     */
    public function sendRawTransaction(RawTransaction $rawTransaction, $allowHighFees = false)
    {
        $result = false;

        $response = $this->request('sendrawtransaction',
            [$rawTransaction->getHexData(), $allowHighFees]);

        if ($response !== false && is_string($response)) {
            $result = $response;
        }

        return $result;
    }
}
