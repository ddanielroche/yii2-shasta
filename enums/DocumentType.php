<?php

namespace ddroche\shasta\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class TransactionType
 * @package ddroche\shasta\enums
 * @see https://doc.payments.shasta.me/#/definitions/CardPayin
 */
class DocumentType extends BaseEnum
{
    const NATIONAL_ID = 'national_id';
    const PASSPORT = 'passport';
    const DRIVING_LICENSE = 'driving_license';

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
        self::NATIONAL_ID => 'National ID',
        self::PASSPORT => 'Passport',
        self::DRIVING_LICENSE => 'Driving License',
    ];
}