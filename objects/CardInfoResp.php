<?php


namespace ddroche\shasta\objects;

/**
 * Class CardInfoResp
 * @package ddroche\shasta\objects
 * @see https://doc.payments.shasta.me/#/definitions/CardInfoResp
 *
 * @property string $number Card number without spaces
 * @property integer $expiration_month Expiration month (1-12)
 * @property integer $expiration_year Full expiration year
 * @property string $brand Card brand (visa, mastercard...)
 */
class CardInfoResp
{
    /** @var string Card number without spaces */
    public $number;
    /** @var integer Expiration month (1-12) */
    public $expiration_month;
    /** @var integer Full expiration year */
    public $expiration_year;
    /** @var string Card brand (visa, mastercard...) */
    public $brand;

    public function rules()
    {
        return [
            [['number'], 'string'],
            [['expiration_month',], 'integer', 'integerOnly' => true, 'min' => 1, 'max' => 12],
            [['expiration_year'], 'integer', 'integerOnly' => true, 'min' => date('Y') + 1],
            [['brand'], 'string'],
        ];
    }
}