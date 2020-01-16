<?php

namespace ddroche\shasta\objects;

use yii\base\Model;

/**
 * Class CardInfo
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#/definitions/CardInfo
 *
 * @property string $number Card number without spaces
 * @property integer $expiration_month Expiration month (1-12)
 * @property integer $expiration_year Full expiration year
 * @property string $cvv
 */
class CardInfo extends Model
{
    /** @var string Card number without spaces */
    public $number;
    /** @var integer Expiration month (1-12) */
    public $expiration_month;
    /** @var integer Full expiration year */
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
