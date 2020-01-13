<?php

namespace ddroche\shasta\objects;

use tigrov\intldata\Country;
use yii\base\Model;

/**
 * Class Address
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#/definitions/Address
 *
 * @property string $line_1
 * @property string $line_2
 * @property string $postal_code
 * @property string $city
 * @property string $region
 * @property string $country
 */
class Address extends Model
{
    /** @var string */
    public $line_1;
    /** @var string */
    public $line_2;
    /** @var string */
    public $postal_code;
    /** @var string */
    public $city;
    /** @var string */
    public $region;
    /** @var string */
    public $country;

    public function rules()
    {
        return [
            [['line_1', 'line_2', 'postal_code', 'city', 'region', 'country'], 'string'],
            ['country', 'in', 'range' => Country::CODES],
        ];
    }
}
