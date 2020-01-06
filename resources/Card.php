<?php

namespace ddroche\shasta\resources;

class Card extends ShastaResource
{
    /** @var string */
    public $customer_id;
    /** @var array */
    public $card_info;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['customer_id'], 'string'],
            [['card_info'], 'safe'],
        ]);
    }

    public function getResource()
    {
        return '/acquiring/cards';
    }
}
