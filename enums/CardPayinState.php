<?php

namespace ddroche\shasta\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class CardPayinState
 * @package ddroche\shasta\enums
 * @see https://doc.payments.shasta.me/#/definitions/CardPayin
 */
class CardPayinState extends BaseEnum
{
    const processing = 'processing';
    const authorized = 'authorized';
    const declined = 'declined';
    const pending_secure = 'pending_secure';
    const settled = 'settled';

    /**
     * @var string message category
     * You can set your own message category for translate the values in the $list property
     * Values in the $list property will be automatically translated in the function `listData()`
     */
    public static $messageCategory = 'shasta';

    /**
     * @var array
     */
    public static $list = [
        self::processing => 'Processing',
        self::authorized => 'Authorized',
        self::declined => 'Declined',
        self::pending_secure => 'Pending Secure',
        self::settled => 'Settled',
    ];
}