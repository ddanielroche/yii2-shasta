<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\objects\BankAccountInfo;
use ddroche\shasta\traits\RelationalTrait;
use yii\base\Exception;

/**
 * Class BankAccount
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-BankAccount
 *
 * @property string $customer_id
 * @property BankAccountInfo $bank_account_info
 *
 * @property Customer $customer
 */
class BankAccount extends ShastaResource
{
    use RelationalTrait;

    /** @var string */
    public $customer_id;
    /** @var BankAccountInfo */
    public $bank_account_info;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['bank_account_info'], 'ddroche\shasta\validators\ObjectValidator', 'targetClass' => 'ddroche\shasta\objects\BankAccountInfo', 'on' => [static::SCENARIO_CREATE, static::SCENARIO_LOAD]],
            [['customer_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'customer', 'on' => [static::SCENARIO_CREATE]],
            [['customer_id'], 'string', 'on' => [static::SCENARIO_LOAD]],
        ]);
    }


    public static function resource()
    {
        return '/bank_accounts';
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
