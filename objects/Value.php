<?php

namespace ddroche\shasta\objects;

use ddroche\shasta\enums\Currency;
use yii\base\Model;

/**
 * Class Value
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#/definitions/Value
 *
 * @property string $currency
 * @property string $amount
 */
class Value extends Model
{
    /** @var string */
    public $currency;
    /** @var string */
    public $amount;

    public function rules()
    {
        return [
            [['currency', 'amount',], 'string'],
            ['currency', 'in', 'range' => Currency::getConstantsByName()],
            ['amount', 'number', 'skipOnEmpty' => false],
        ];
    }
}
