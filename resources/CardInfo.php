<?php

namespace ddroche\shasta\resources;

use yii\base\Model;

class CardInfo extends Model
{
    /** @var string Card number without spaces */
    public $number;
    /** @var integer Expiration month (1-12) */
    public $expiration_month;
    /** @var string Full expiration year */
    public $expiration_year;
    /** @var string */
    public $cvv;

    public function rules()
    {
        return [
            [['cvv'], 'string', 'length' => [3, 4]],
            [['number'], 'string'],
            [['expiration_month',], 'integer', 'integerOnly' => true, 'min' => 1, 'max' => 12],
            [['expiration_year'], 'integer', 'integerOnly' => true, 'min' => date('Y') + 1],
        ];
    }
}
