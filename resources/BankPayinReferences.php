<?php

namespace ddroche\shasta\resources;

/**
 * Class BankPayinReferences
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-BankPayinReference
 *
 * @property string $account_id
 * @property string $reference
 *
 * TODO unknown field "account_id"
 */
class BankPayinReferences extends ShastaResource
{
    /** @var string */
    public $account_id;
    /** @var string */
    public $reference;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['account_id'], 'string', 'on' => static::SCENARIO_CREATE],
            [['account_id'], 'string', 'on' => static::SCENARIO_DEFAULT],
            [['reference'], 'string', 'on' => static::SCENARIO_LOAD],
        ]);
    }

    public function getResource()
    {
        return '/bank_payin_references';
    }
}
