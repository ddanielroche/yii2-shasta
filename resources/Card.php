<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\objects\CardInfo;
use ddroche\shasta\objects\CardInfoResp;
use ddroche\shasta\traits\RelationalTrait;
use ddroche\shasta\validators\ExistValidator;
use ddroche\shasta\validators\ObjectValidator;
use yii\base\Exception;

/**
 * Class Card
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-Card
 *
 * @property string $customer_id
 * @property string $card_token_id
 * @property CardInfo|CardInfoResp|array $card_info
 *
 * TODO Verification of card was declined.
 */
class Card extends ShastaResource
{
    use RelationalTrait;

    /** @var string */
    public $customer_id;
    /** @var string */
    public $card_token_id;
    /** @var CardInfo|CardInfoResp|array */
    public $card_info;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['card_token_id'], 'string', 'on' => static::SCENARIO_CREATE],
            [['card_info'], ObjectValidator::class, 'targetClass' => CardInfo::class, 'on' => static::SCENARIO_CREATE],
            [['card_info'], ObjectValidator::class, 'targetClass' => CardInfoResp::class, 'on' => static::SCENARIO_LOAD],
            [['customer_id'], ExistValidator::class, 'targetRelation' => 'customer'],
            ['card_info', 'validateCardInfo'],
        ]);
    }

    public function validateCardInfo()
    {
        if ($this->card_token_id && $this->card_info) {
            $this->addError('card_info', 'Exactly one of card_token_id or card_info must be present');
        }
    }

    public static function resource()
    {
        return '/acquiring/cards';
    }

    /**
     * @return ShastaResource|null
     * @throws Exception
     */
    public function getCustomer()
    {
        return $this->hasOne('ddroche\shasta\resources\Customer', 'customer_id');
    }
}
