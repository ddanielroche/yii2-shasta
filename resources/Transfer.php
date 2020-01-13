<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\objects\Value;

/**
 * Class Transfer
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-Transfer
 *
 * @property string $source_account_id
 * @property string $destination_account_id
 * @property Value $value
 */
class Transfer extends ShastaResource
{
    /** @var string */
    public $source_account_id;
    /** @var string */
    public $destination_account_id;
    /** @var Value */
    public $value;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['source_account_id', 'destination_account_id', 'value'], 'required', 'on' => static::SCENARIO_CREATE],
            [['source_account_id', 'destination_account_id'], 'string', 'on' => static::SCENARIO_CREATE],
            [['value'], 'safe', 'on' => static::SCENARIO_CREATE],
        ]);
    }

    public static function resource()
    {
        return '/transfers';
    }
}
