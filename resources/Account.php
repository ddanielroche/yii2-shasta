<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\enums\Currency;

/**
 * Class Account
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-Account
 *
 * @property string $currency
 * @property string $customer_id
 * @property array $balance
 * @property array $auto_bank_payout
 * @property boolean $allow_negative_balance
 */
class Account extends ShastaResource
{
    /** @var string */
    public $currency;
    /** @var string */
    public $customer_id;
    /** @var array */
    public $balance;
    /** @var array */
    public $auto_bank_payout;
    /** @var boolean */
    public $allow_negative_balance;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['currency'], 'required', 'on' => static::SCENARIO_CREATE],
            ['currency', 'in', 'range' => Currency::getConstantsByName(), 'on' => static::SCENARIO_CREATE],
            [['currency'], 'string', 'on' => static::SCENARIO_CREATE],
            ['allow_negative_balance', 'boolean'],
            ['allow_negative_balance', 'default', 'value' => false],
            [['customer_id'], 'string'],
            [['auto_bank_payout'], 'safe'],
            [['balance'], 'safe', 'on' => static::SCENARIO_LOAD],
        ]);
    }

    public static function resource()
    {
        return '/accounts';
    }
}
