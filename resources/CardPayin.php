<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\enums\CardPayinState;
use ddroche\shasta\objects\CardInfo;
use ddroche\shasta\objects\Value;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\httpclient\Response;

/**
 * Class CardPayin
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-CardPayin
 *
 * TODO not tested
 */
class CardPayin extends ShastaResource
{
    /** @var string */
    public $account_id;
    /** @var Value */
    public $value;
    /** @var CardInfo */
    public $card_info;
    /** @var string */
    public $card_token_id;
    /** @var string */
    public $card_id;
    /** @var string */
    public $transaction_id;
    /** @var string */
    public $state;
    /** @var boolean */
    public $is_secure;
    /** @var string */
    public $secure_start_url;
    /** @var string */
    public $secure_redirect_url;
    /** @var boolean */
    public $is_instant;
    /** @var string */
    public $instant_account_id;
    /** @var string */
    public $instant_authorized_transaction_id;
    /** @var string */
    public $instant_settled_transaction_id;
    /** @var string */
    public $fee_account_id;
    /** @var Value */
    public $refunded_value;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['account_id', 'value', 'card_info', 'card_token_id', 'card_id', 'is_secure', 'secure_redirect_url', 'is_instant', 'instant_account_id', 'fee_account_id', ], 'required', 'on' => static::SCENARIO_CREATE],
            ['state', 'in', 'range' => CardPayinState::getConstantsByName(), 'on' => static::SCENARIO_CREATE],
        ]);
    }

    public static function resource()
    {
        return '/acquiring/card_payins';
    }

    /**
     * Finish card verification
     * @see https://doc.payments.shasta.me/#operation--acquiring-card_verifications--card_verification_id--finish-post
     *
     * @return Response
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function finish()
    {
        $response = static::getShasta()->createRequest()
            ->setMethod('POST')
            ->setUrl(static::resource() . "/$this->id/finish")
            ->send();

        return $response;
    }
}
