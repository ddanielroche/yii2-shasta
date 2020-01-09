<?php

namespace ddroche\shasta\resources;

/**
 * Class Card
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-Card
 *
 * @property string $customer_id
 * @property string $card_token_id
 * @property CardInfo $card_info
 *
 * TODO Verification of card was declined.
 */
class Card extends ShastaResource
{
    /** @var string */
    public $customer_id;
    /** @var string */
    public $card_token_id;
    /** @var CardInfo */
    public $card_info;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['card_token_id'], 'string', 'on' => static::SCENARIO_CREATE],
            [['customer_id'], 'string'],
            ['card_info', 'validateCardInfo'],
        ]);
    }

    public function validateCardInfo()
    {
        if ($this->card_token_id && $this->card_info) {
            $this->addError('card_info', 'Exactly one of card_token_id or card_info must be present');
        }
    }

    public function getResource()
    {
        return '/acquiring/cards';
    }
}
