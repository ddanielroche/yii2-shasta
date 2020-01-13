<?php

namespace ddroche\shasta\resources;

/**
 * Class BankPayins
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#/definitions/BankPayin
 *
 * @property string $bank_payin_reference_id
 * @property string $account_id
 * @property string $transaction_id
 *
 * TODO not tested
 */
class BankPayins extends ShastaResource
{
    /** @var string */
    public $bank_payin_reference_id;
    /** @var string */
    public $account_id;
    /** @var string */
    public $transaction_id;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['bank_payin_reference_id', 'account_id', 'transaction_id', ], 'string'],
        ]);
    }

    public static function resource()
    {
        return '/bank_payins';
    }
}
