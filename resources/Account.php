<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\enums\Currency;
use ddroche\shasta\objects\AutoBankPayout;
use ddroche\shasta\traits\RelationalTrait;
use yii\base\Exception;

/**
 * Class Account
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-Account
 *
 * @property string $currency
 * @property string $customer_id
 * @property array $balance
 * @property AutoBankPayout|array $auto_bank_payout
 * @property boolean $allow_negative_balance
 */
class Account extends ShastaResource
{
    use RelationalTrait;

    /** @var string */
    public $currency;
    /** @var string */
    public $customer_id;
    /** @var array */
    public $balance;
    /** @var AutoBankPayout|array */
    public $auto_bank_payout;
    /** @var boolean */
    public $allow_negative_balance;

    /**
     * Rules established according to the documentation
     *
     * @see https://doc.payments.shasta.me/#tag-Accounts
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            /** @see https://doc.payments.shasta.me/#operation--accounts-post */
            [['currency'], 'required', 'on' => static::SCENARIO_CREATE],
            ['currency', 'in', 'range' => Currency::getConstantsByName(), 'on' => static::SCENARIO_CREATE],
            [['currency'], 'string', 'on' => static::SCENARIO_CREATE],
            [['customer_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'customer'],
            ['allow_negative_balance', 'boolean'],
            ['allow_negative_balance', 'default', 'value' => false],
            [['auto_bank_payout'], 'ddroche\shasta\validators\ObjectValidator', 'targetClass' => AutoBankPayout::class],
            [['balance'], 'safe', 'on' => static::SCENARIO_LOAD],
        ]);
    }

    public static function resource()
    {
        return '/accounts';
    }

    /**
     * @return ShastaResource|null
     * @throws Exception
     */
    public function getCustomer()
    {
        return $this->hasOne('ddroche\shasta\resources\Customer', 'customer_id');
    }
}
