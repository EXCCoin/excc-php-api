<?php
/**
 * excc-php-api (c) 2018 EXCCoin
 * Created by: widelec
 * Date: 13.06.2018
 */

namespace EXCCoin\Client;


use EXCCoin\Client\Traits\CallsRPC;

class Notifier
{
    use CallsRPC;

    public function addAddressesToWatchList($addresses, $clear = false)
    {
        $result = false;

        $response = $this->post('/add', ['clear' => $clear, 'addresses' => $addresses]);

        if ($response !== false) {
            $result = $response;
        }

        return $result;
    }
}