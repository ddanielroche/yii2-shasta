<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\objects\CardInfo;
use ddroche\shasta\objects\Value;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\httpclient\Response;
use ddroche\shasta\traits\RelationalTrait;

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
            [['account_id', 'value'], 'required', 'on' => static::SCENARIO_CREATE],
            [['card_info', 'card_token_id', 'card_id'], 'validateCardInfo', 'skipOnEmpty' => false, 'on' => static::SCENARIO_CREATE],
            [['value'], 'ddroche\shasta\validators\ObjectValidator', 'targetClass' => Value::class, 'on' => static::SCENARIO_CREATE],
            [['card_info'], 'ddroche\shasta\validators\ObjectValidator', 'targetClass' => CardInfo::class, 'on' => static::SCENARIO_CREATE],
            [['account_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'account', 'on' => static::SCENARIO_CREATE],
            [['card_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'card', 'on' => static::SCENARIO_CREATE],
            [['card_token_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'cardToken', 'on' => static::SCENARIO_CREATE],
            [['instant_account_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'instantAccount', 'on' => static::SCENARIO_CREATE],
            [['is_instant', 'is_secure'], 'boolean', 'on' => static::SCENARIO_CREATE],
            ['secure_redirect_url', 'validateSecureRedirectUrl', 'on' => static::SCENARIO_CREATE],
            ['instant_account_id', 'validateInstantAccountId', 'skipOnEmpty' => false, 'on' => static::SCENARIO_CREATE],

            [['card_id', 'card_info', 'account_id', 'transaction_id', 'value', 'state', 'is_secure', 'secure_start_url', 'secure_redirect_url', 'is_instant', 'instant_account_id', 'instant_authorized_transaction_id', 'instant_settled_transaction_id', 'fee_account_id', 'refunded_value'], 'safe', 'on' => static::SCENARIO_LOAD],
        ]);
    }

    public function validateSecureRedirectUrl()
    {
        if($this->is_secure && !$this->secure_redirect_url){
            $this->addError('secure_redirect_url', 'secure_redirect_url must be present');
        }
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function validateInstantAccountId()
    {
        if($this->is_instant) {
            $currency = $this->getAccount()->balance['currency'];
            $projecto = Project::getProject();
            if(!$this->instant_account_id) {
                if (!array_key_exists($currency, $projecto->default_card_payin_instant_account_ids)) {
                    $this->addError('instant_account_id', 'instant_account_id must be present');
                    return;
                } else {
                    $this->instant_account_id = $projecto->default_card_payin_instant_account_ids[$currency];
                }
            }
            /*$balance = $this->getInstantAccount()->balance;
            if (doubleval($this->value->amount) > doubleval($balance['amount'])) {
                $this->addError('instant_account_id', "Not enough balance in account $this->instant_account_id: want {$this->value->amount} {$this->value->currency}, but only have {$balance['amount']} {$balance['currency']}");
            }*/
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
     * @throws \yii\base\Exception
     */
    public function getCard()
    {
        return $this->hasOne('ddroche\shasta\resources\Card', 'card_id');
    }

    /**
     * @return ShastaResource|null
     * @throws \yii\base\Exception
     */
    public function getCardToken()
    {
        return $this->hasOne('ddroche\shasta\resources\CardToken', 'card_token_id');
    }

    /**
     * @return ShastaResource|null
     * @throws \yii\base\Exception
     */
    public function getAccount()
    {
        return $this->hasOne('ddroche\shasta\resources\Account', 'account_id');
    }

    /**
     * @return ShastaResource|null
     * @throws \yii\base\Exception
     */
    public function getInstantAccount()
    {
        return $this->hasOne('ddroche\shasta\resources\Account', 'instant_account_id');
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
