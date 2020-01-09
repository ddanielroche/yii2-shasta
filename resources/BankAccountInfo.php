<?php

namespace ddroche\shasta\resources;

/**
 * Class BankAccountInfo
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-BankAccount
 *
 * @property string $beneficiary_name
 * @property string $beneficiary_swift
 * @property string $iban
 */
class BankAccountInfo extends ShastaResource
{
    /** @var string */
    public $beneficiary_name;
    /** @var string */
    public $beneficiary_swift;
    /** @var string */
    public $iban;

    public function rules()
    {
        return [
            [['beneficiary_name', 'beneficiary_swift', 'iban'], 'string']
        ];
    }


    public function getResource()
    {
        return '/bank_accounts';
    }
}
