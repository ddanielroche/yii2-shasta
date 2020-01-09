<?php

namespace ddroche\shasta\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class TransactionType
 * @package ddroche\shasta\enums
 * @see https://doc.payments.shasta.me/#/definitions/CardPayin
 */
class TransactionType extends BaseEnum
{
    const magic = 'magic';
    const transfer_source = 'transfer_source';
    const transfer_destination = 'transfer_destination';
    const card_payin = 'bank_payin';
    const bank_payout = 'bank_payout';
    const card_payin_refund = 'card_payin_refund';
    const card_payin_instant_authorized = 'card_payin_instant_authorized';
    const card_payin_instant_settled = 'card_payin_instant_settled';

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
        self::magic => 'Processing',
        self::transfer_source => 'Authorized',
        self::transfer_destination => 'Declined',
        self::card_payin => 'Pending Secure',
        self::bank_payout => 'Settled',
        self::card_payin_refund => 'Settled',
        self::card_payin_instant_authorized => 'Settled',
        self::card_payin_instant_settled => 'Settled',
    ];
}