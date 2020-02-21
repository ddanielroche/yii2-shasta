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
            [['account_id', 'concept', 'value'], 'required', 'on' => static::SCENARIO_CREATE],
            [['account_id', 'bank_account_id', 'concept', 'value'], 'string', 'on' => static::SCENARIO_CREATE],
            [['bank_account_id', 'bank_account_info'], 'validateBankAccount', 'on' => static::SCENARIO_CREATE],
            [['transaction_id', 'refund_transaction_id', 'miniref', 'state', ], 'safe', 'on' => static::SCENARIO_LOAD],
        ]);
    }

    public static function resource()
    {
        return '/bank_payouts';
    }

    public function validateBankAccount()
    {
        if ($this->bank_account_id && $this->bank_account_info || !isset($this->bank_account_id) && !isset($this->bank_account_info)) {
            $this->addError('bank_account_info', 'Exactly one of bank_account_id or bank_account_info must be present');
        }
    }
}
