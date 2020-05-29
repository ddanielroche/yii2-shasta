<?php

namespace ddroche\shasta\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class Currency
 * @package ddroche\shasta\enums
 * @see https://doc.payments.shasta.me/#/definitions/Currency
 */
class DocumentStatus extends BaseEnum
{
    const UNVERIFIED = 'unverified';
    const PENDING = 'pending';
    const VERIFIED = 'verified';
    const REFUSED  = 'refused';

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
        self::UNVERIFIED => 'Unverified',
        self::PENDING => 'Pending',
        self::VERIFIED => 'Verified',
        self::REFUSED => 'Refused',
    ];
}