<?php

namespace ddroche\shasta\resources;

use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\httpclient\Response;

/**
 * Class Project
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-Project
 */
class Project extends ShastaResource
{
    /** @var string Account where to get the temporary hold value */
    public $default_card_payin_instant_account_id;
    /** @var array Accounts where to get the temporary hold value. Ex {"USD":"acc_....", "EUR":"acc_...."}*/
    public $default_card_payin_instant_account_ids;
    /**
     * @var string The account where the fee is substracted. If this account has a negative balance,
     * this amount will be a debt and your card pay in may stop working (only in live environment)
     */
    public $default_card_payin_fee_account_id;
    /**
     * @var array The accounts where the fee is substracted. Ex {"USD":"acc_....", "EUR":"acc_...."}
     * If this account has a negative balance, this amount will be a debt and your card pay in
     * may stop working (only in live environment)
     */
    public $default_card_payin_fee_account_ids;
    /**
     * @return string The percent fee applied to card pay ins
     */
    public $card_payin_fee;
    /**
     * @return string The percent fee applied to card pay ins outside of EU
     */
    public $card_payin_fee_non_eu;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['default_card_payin_instant_account_id', 'default_card_payin_fee_account_id', 'card_payin_fee', 'card_payin_fee_non_eu'], 'string'],
            [['default_card_payin_instant_account_ids', 'default_card_payin_fee_account_ids'], 'safe'],
        ]);
    }

    public static function resource()
    {
        return 'project';
    }

    /**
     * Get Project
     *
     * @return Project
     *
     * @throws InvalidConfigException
     * @throws Exception
     */
    public static function getProject()
    {
        $response = static::getShasta()->createRequest()
            ->setMethod('GET')
            ->setUrl(static::resource())
            ->send();

        $resource = new static();
        if ($response->isOk) {
            $resource->attributes = $response->data;
            return $resource;
        } else {
            throw new Exception('Error Load Resource');
        }
    }

    /**
     * Edit Project
     *
     * @param array $body
     *
     * @return Response
     *
     * @throws InvalidConfigException
     */
    public function setProject($body)
    {
        $response = static::getShasta()->createRequest()
            ->setMethod('PATCH')
            ->setUrl(static::resource())
            ->setData($body)
            ->send();

        return $response;
    }
}
