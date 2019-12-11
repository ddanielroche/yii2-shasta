<?php

namespace ddroche\shasta;

use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Request;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\controllers';

    /**
     * @var string The Shasta API end point
     */
    public $apiEndPoint;
    /**
     * @var string The Shasta API Key
     */
    public $apiKey;
    /**
     * @var Client The HTTP Client to access Shasta service
     */
    private $_client;

    public function init()
    {
        parent::init();
        $this->_client = new Client([
            'baseUrl' => $this->apiEndPoint,
            'requestConfig' => [
                'format' => Client::FORMAT_JSON
            ],
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);
    }

    /**
     * @return Request
     * @throws InvalidConfigException
     */
    public function getRequest()
    {
        $request = $this->_client->createRequest();
        $request->headers->set('Authorization', $this->apiKey);
        return $request;
    }
}