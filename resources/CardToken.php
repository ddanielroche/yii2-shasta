<?php

namespace ddroche\shasta\resources;

/**
 * Class CardToken
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-Account
 *
 * @property CardInfo $card_info
 */
class CardToken extends ShastaResource
{
    /** @var CardInfo */
    public $card_info;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['card_info'], 'safe', 'on' => static::SCENARIO_CREATE],
            [['card_info'], 'safe', 'on' => static::SCENARIO_LOAD],
        ]);
    }

    public function getResource()
    {
        return '/acquiring/card_tokens';
    }
}
