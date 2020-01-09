<?php

namespace ddroche\shasta\resources;

use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\httpclient\Response;

/**
 * Class Project
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-Project
 *
 * @property string $default_card_payin_instant_account_id
 * @property string $default_card_payin_fee_account_id
 * @property string $card_payin_fee
 */
class Project extends ShastaResource
{
    /** @var string Account where to get the temporary hold value */
    public $default_card_payin_instant_account_id;
    /**
     * @var string The account where the fee is substracted. If this account has a negative balance,
     * this amount will be a debt and your card pay in may stop working (only in live environment)
     */
    public $default_card_payin_fee_account_id;
    /**
     * @return string The percent fee applied to card pay ins
     */
    public $card_payin_fee;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['default_card_payin_instant_account_id', 'default_card_payin_fee_account_id', 'card_payin_fee'], 'string'],
        ]);
    }

    public function getResource()
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
        $resource = new static();
        $response = $resource->getRequest()
            ->setMethod('GET')
            ->setUrl("$resource->resource")
            ->send();

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
        $response = $this->getRequest()
            ->setMethod('PATCH')
            ->setUrl("$this->resource")
            ->setData($body)
            ->send();

        return $response;
    }
}
