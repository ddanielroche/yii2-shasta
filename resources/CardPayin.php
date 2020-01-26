<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\enums\CardPayinState;
use ddroche\shasta\objects\CardInfo;
use ddroche\shasta\objects\Value;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\httpclient\Response;
use ddroche\shasta\traits\RelationalTrait;
use ddroche\shasta\validators\ExistValidator;
use ddroche\shasta\validators\ObjectValidator;

/**
 * Class CardPayin
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-CardPayin
 *
 * TODO not tested
 */
class CardPayin extends ShastaResource
{
    use RelationalTrait;

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
            [['account_id', 'value', 'is_secure', 'fee_account_id', ], 'required', 'on' => static::SCENARIO_CREATE],
            [['card_info', 'card_token_id', 'card_id'], 'validateCardInfo', 'on' => static::SCENARIO_CREATE],
            [['card_info'], ObjectValidator::class, 'targetClass' => CardInfo::class, 'on' => static::SCENARIO_CREATE],
            [['account_id'], ExistValidator::class, 'targetRelation' => 'account', 'on' => static::SCENARIO_CREATE],
            ['secure_redirect_url', 'validateSecureRedirectUrl', 'on' => static::SCENARIO_CREATE],
            ['instant_account_id', 'validateInstantAccountId', 'on' => static::SCENARIO_CREATE],
            [['account_id'], ExistValidator::class, 'targetRelation' => 'account', 'on' => static::SCENARIO_LOAD],
            ['state', 'in', 'range' => CardPayinState::getConstantsByName()],
        ]);
    }

    public function validateSecureRedirectUrl()
    {
        if($this->is_secure && !$this->secure_redirect_url){
            $this->addError('secure_redirect_url', 'secure_redirect_url must be present');
        }
    }

    public function validateInstantAccountId()
    {
        if($this->is_instant && !$this->instant_account_id){
            $this->addError('instant_account_id', 'instant_account_id must be present');
        }
    }

    public function validateCardInfo($attribute)
    {
        $counter = 0;
        if($this->card_token_id)$counter++;
        if($this->card_info)$counter++;
        if($this->card_id)$counter++;
        if ($counter == 0 || $counter > 1) {
            $this->addError($attribute, 'Exactly one of card_id, card_token_id or card_info must be present');
        }
    }

    public static function resource()
    {
        return '/acquiring/card_payins';
    }

    /**
     * @return ShastaResource|null
     * @throws Exception
     */
    public function getAccount()
    {
        return $this->hasOne('ddroche\shasta\resources\Account', 'account_id');
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
