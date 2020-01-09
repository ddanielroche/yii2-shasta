<?php

namespace ddroche\shasta\resources;

/**
 * Class BankAccount
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-BankAccount
 *
 * @property string $customer_id
 * @property BankAccountInfo $bank_account_info
 *
 * TODO Invalid JSON in request body: json: unknown field "id"
 */
class BankAccount extends ShastaResource
{
    /** @var string */
    public $customer_id;
    /** @var BankAccountInfo */
    public $bank_account_info;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['bank_account_info'], 'safe', 'on' => static::SCENARIO_CREATE],
            [['customer_id'], 'string'],
        ]);
    }


    public function getResource()
    {
        return '/bank_accounts';
    }
}
