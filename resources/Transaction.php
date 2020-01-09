<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\objects\Value;
use yii\base\InvalidConfigException;
use yii\httpclient\Response;

/**
 * Class Transaction
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-Transaction
 *
 * TODO not tested
 */
class Transaction extends ShastaResource
{
    /** @var string */
    public $account_id;
    /** @var string */
    public $type;
    /** @var Value */
    public $value;
    /** @var Value */
    public $balance_after;
    /** @var string */
    public $transfer_id;
    /** @var string */
    public $card_payin_id;
    /** @var string */
    public $card_payin_refund_id;
    /** @var string */
    public $bank_payin_id;
    /** @var string */
    public $bank_payout_id;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['account_id', 'type', 'value', 'balance_after', 'transfer_id', 'card_payin_id', 'card_payin_refund_id', 'bank_payin_id', 'bank_payout_id'], 'required', 'on' => static::SCENARIO_CREATE],
            [['account_id', 'type', 'transfer_id', 'card_payin_id', 'card_payin_refund_id', 'bank_payin_id', 'bank_payout_id'], 'string', 'on' => static::SCENARIO_CREATE],
            [['value', 'balance_after'], 'safe', 'on' => static::SCENARIO_CREATE],
        ]);
    }

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
