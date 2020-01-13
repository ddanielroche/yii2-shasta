<?php

namespace ddroche\shasta\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class EmploymentStatus
 * @package ddroche\shasta\enums
 * @see https://doc.payments.shasta.me/#/definitions/CardPayin
 */
class EmploymentStatus extends BaseEnum
{
    const student = 'student';
    const employed = 'employed';
    const self_employed = 'self_employed';
    const searching = 'searching';
    const not_employed = 'not_employed';

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
        self::student => 'Student',
        self::employed => 'Employed',
        self::self_employed => 'Self Employed',
        self::searching => 'Searching',
        self::not_employed => 'Not Employed',
    ];
}