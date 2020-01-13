<?php

namespace ddroche\shasta\resources;

use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\httpclient\Response;

/**
 * Class CardVerification
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-CardVerification
 *
 * @property CardInfo card_info
 * @property string $card_token_id
 * @property string $card_id
 * @property boolean $is_secure
 * @property string $secure_redirect_url
 *
 * TODO not tested
 */
class CardVerification extends ShastaResource
{
    /** @var CardInfo */
    public $card_info;
    /** @var string */
    public $card_token_id;
    /** @var string */
    public $card_id;
    /** @var boolean */
    public $is_secure;
    /** @var string */
    public $secure_redirect_url;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['card_info', 'card_token_id', 'card_id', 'is_secure', 'secure_redirect_url'], 'required', 'on' => static::SCENARIO_CREATE],
            [['card_info'], 'safe', 'on' => static::SCENARIO_CREATE],
            [['card_token_id', 'card_id', 'secure_redirect_url'], 'string', 'on' => static::SCENARIO_CREATE],
            [['is_secure'], 'boolean', 'on' => static::SCENARIO_CREATE],
        ]);
    }

    public static function resource()
    {
        return '/acquiring/card_verifications';
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
