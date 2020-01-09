<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\enums\CardPayinsRefundState;
use ddroche\shasta\objects\Value;

/**
 * Class CardPayinsRefund
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-CardPayinRefund
 */
class CardPayinsRefund extends ShastaResource
{
    /** @var string */
    public $account_id;
    /** @var string */
    public $card_payin_id;
    /** @var string */
    public $transaction_id;
    /** @var Value */
    public $value;
    /** @var string */
    public $state;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['card_payin_id'], 'required', 'on' => static::SCENARIO_CREATE],
            [['card_payin_id'], 'string', 'on' => static::SCENARIO_CREATE],
            ['state', 'in', 'range' => CardPayinsRefundState::getConstantsByName(), 'on' => static::SCENARIO_CREATE],
        ]);
    }

    public function getResource()
    {
        return '/acquiring/card_payin_refunds';
    }
}
