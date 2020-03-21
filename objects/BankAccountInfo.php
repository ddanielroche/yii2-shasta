<?php

namespace ddroche\shasta\objects;

use yii\base\Model;

/**
 * Class BankAccountInfo
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-BankAccountInfo
 */
class BankAccountInfo extends Model
{
    /** @var string */
    public $beneficiary_name;
    /** @var string */
    public $beneficiary_swift;
    /** @var string */
    public $beneficiary_zip_code;
    /** @var string */
    public $beneficiary_phone_number;
    /** @var string */
    public $beneficiary_email;
    /** @var string */
    public $iban;

    public function rules()
    {
        return [
            [['iban'], 'required'],
            [['beneficiary_name', 'beneficiary_swift', 'beneficiary_zip_code', 'beneficiary_phone_number', 'beneficiary_email', 'iban'], 'string'],
        ];
    }
}
