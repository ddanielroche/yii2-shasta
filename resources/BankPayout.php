<?php

namespace ddroche\shasta\resources;

/**
 * Class BankPayout
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#/definitions/BankPayout
 *
 * @property string $account_id
 * @property string $transaction_id
 * @property string $refund_transaction_id
 * @property string $bank_account_info
 * @property string $bank_account_id
 * @property string $value
 * @property string $concept
 * @property string $miniref
 * @property string $state
 *
 * TODO not tested
 */
class BankPayout extends ShastaResource
{
    /** @var string */
    public $account_id;
    /** @var string */
    public $transaction_id;
    /** @var string */
    public $refund_transaction_id;
    /** @var BankAccountInfo */
    public $bank_account_info;
    /** @var string */
    public $bank_account_id;
    /** @var array */
    public $value;
    /** @var string */
    public $concept;
    /** @var string */
    public $miniref;
    /** @var string */
    public $state;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['account_id', 'bank_account_id', 'bank_account_info', 'concept', 'value'], 'required', 'on' => static::SCENARIO_CREATE],
            [['account_id', 'bank_account_id', 'concept', 'value'], 'string', 'on' => static::SCENARIO_CREATE],
            [['bank_account_info'], 'safe', 'on' => static::SCENARIO_CREATE],
            [['transaction_id', 'refund_transaction_id', 'miniref', 'state', ], 'safe', 'on' => static::SCENARIO_LOAD],
        ]);
    }

    public function getResource()
    {
        return '/bank_payouts';
    }
}
