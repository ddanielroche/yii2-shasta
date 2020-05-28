<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\objects\BankAccountInfo;
use ddroche\shasta\objects\Value;
use ddroche\shasta\traits\RelationalTrait;
use yii\base\Exception;

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
    use RelationalTrait;

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
    /** @var array|Value */
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
            [['account_id', 'value'], 'required', 'on' => static::SCENARIO_CREATE],
            [['account_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'account', 'on' => static::SCENARIO_CREATE],
            [['bank_account_info'], 'ddroche\shasta\validators\ObjectValidator', 'targetClass' => BankAccountInfo::class, 'on' => static::SCENARIO_CREATE],
            [['bank_account_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'bankAccount', 'on' => static::SCENARIO_CREATE],
            [['bank_account_info', 'bank_account_id'], 'validateBankAccountInfo', 'skipOnEmpty' => false, 'on' => static::SCENARIO_CREATE],
            [['value'], 'ddroche\shasta\validators\ObjectValidator', 'targetClass' => Value::class, 'on' => static::SCENARIO_CREATE],
            [['concept'], 'string', 'max' => 130, 'on' => static::SCENARIO_CREATE],

            [['account_id', 'bank_account_id', 'bank_account_info', 'concept', 'value', 'transaction_id', 'refund_transaction_id', 'miniref', 'state'], 'safe', 'on' => static::SCENARIO_LOAD],
        ]);
    }

    public function validateBankAccountInfo($attribute)
    {
        $counter = 0;
        if(isset($this->bank_account_info))
            $counter++;
        if(isset($this->bank_account_id))
            $counter++;
        if ($counter !== 1) {
            $this->addError($attribute, 'Exactly one of bank_account_info or bank_account_id');
        }
    }

    /**
     * @return ShastaResource|null
     * @throws Exception
     */
    public function getAccount()
    {
        return $this->hasOne('ddroche\shasta\resources\Account', 'account_id');
    }

    /**
     * @return ShastaResource|null
     * @throws Exception
     */
    public function getBankAccount()
    {
        return $this->hasOne('ddroche\shasta\resources\BankAccount', 'bank_account_id');
    }

    public static function resource()
    {
        return '/bank_payouts';
    }
}
