<?php

namespace ddroche\shasta\resources;

use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\httpclient\Response;

class CardPayins extends ShastaResource
{
    public function getResource()
    {
        return '/acquiring/card_payins';
    }

    /**
     * Finish card verification
     * @see https://doc.payments.shasta.me/#operation--acquiring-card_verifications--card_verification_id--finish-post
     *
     * @param $id
     * @return Response
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function finish($id)
    {
        $response = $this->getRequest()
            ->setMethod('POST')
            ->setUrl("$this->resource/$id/finish")
            ->send();

        return $response;
    }
}
