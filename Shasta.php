<?php

namespace ddroche\shasta;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Request;

/**
 * Class Module
 * @package ddroche\shasta
 * @see https://doc.payments.shasta.me/
 *
 * @property string $apiBaseUrl The Shasta API Base URL
 * @property string $apiKey The Shasta API Key
 * @property string $httpClient The HTTP Client to access Shasta service
 */
class Shasta extends Component
{
    /**
     * @var string The Shasta API Base URL
     */
    public $apiBaseUrl;
    /**
     * @var string The Shasta API Key
     */
    public $apiKey;
    /**
     * @var Client The HTTP Client to access Shasta service
     */
    private $_httpClient;

    /**
     * @return Client
     * @throws InvalidConfigException
     */
    public function getHttpClient()
    {
        if (!is_object($this->_httpClient)) {
            $this->_httpClient = Yii::createObject([
                'class' => 'yii\httpclient\Client',
                'baseUrl' => $this->apiBaseUrl,
            ]);
        }
        return $this->_httpClient;
    }

    /**
     * @return Request
     * @throws InvalidConfigException
     */
    public function createRequest()
    {
        $request = $this->getHttpClient()
            ->createRequest()
            ->setFormat(Client::FORMAT_JSON);
        $request->headers->set('Authorization', $this->apiKey);
        return $request;
    }
}