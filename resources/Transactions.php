<?php

namespace ddroche\shasta\resources;

use yii\base\InvalidConfigException;
use yii\httpclient\Response;

class Transactions extends ShastaResource
{
    public function getResource()
    {
        return '/transactions';
    }

    /**
     * List account transactions
     *
     * @param int $limit
     * @param string $cursor
     * @param string $account_id
     *
     * @return Response
     *
     * @throws InvalidConfigException
     */
    public function allAccountsTransactions($limit, $cursor, $account_id)
    {
        $response = $this->getRequest()
            ->setMethod('GET')
            ->setUrl("/accounts/$account_id/$this->resource")
            ->setData([
                'limit' => $limit,
                'cursor' => $cursor,
            ])
            ->send();

        return $response;
    }

}
