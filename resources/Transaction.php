<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\objects\Value;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
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

    public static function resource()
    {
        return '/transactions';
    }

    /**
     * List account transactions
     * @see https://doc.payments.shasta.me/#operation--accounts--account_id--transactions-get
     *
     * @param int $limit
     * @param string $cursor
     *
     * @return Response
     *
     * @throws InvalidConfigException
     */
    public function allAccountsTransactions($limit, $cursor)
    {
        $response = static::getShasta()->createRequest()
            ->setFormat(Client::FORMAT_URLENCODED)
            ->setMethod('GET')
            ->setUrl("/accounts/$this->account_id" . static::resource())
            ->setData([
                'limit' => $limit,
                'cursor' => $cursor,
            ])
            ->send();

        return $response;
    }

}
