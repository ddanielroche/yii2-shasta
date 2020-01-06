<?php

namespace ddroche\shasta\resources;

use yii\base\Model;

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
            [['line_1', 'line_2', 'postal_code', 'city', 'region', 'country'], 'string']
        ];
    }
}
