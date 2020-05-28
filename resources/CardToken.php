<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\objects\CardInfo;
use yii\base\NotSupportedException;

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
            [['card_info'], 'ddroche\shasta\validators\ObjectValidator', 'targetClass' => CardInfo::class, 'on' => static::SCENARIO_CREATE],
            [['card_info'], 'safe', 'on' => static::SCENARIO_LOAD],
        ]);
    }

    /**
     * @param bool $runValidation
     * @param null $attributes
     * @return bool|void
     * @throws NotSupportedException
     */
    public function update($runValidation = true, $attributes = null)
    {
        throw new NotSupportedException();
    }

    /**
     * @param bool $runValidation
     * @param null $attributes
     * @return bool|void
     * @throws NotSupportedException
     */
    public static function findAll($runValidation = true, $attributes = null)
    {
        throw new NotSupportedException();
    }

    public static function resource()
    {
        return '/acquiring/card_tokens';
    }
}
