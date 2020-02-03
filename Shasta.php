<?php

namespace ddroche\shasta;

use ddroche\shasta\resources\ShastaResource;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use Yii\httpclient\Exception;
use yii\httpclient\Request;
use yii\httpclient\Response;

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

    /**
     * @param ShastaResource $shastaResource
     * @param bool $runValidation
     * @param null $attributes
     * @return bool
     * @throws InvalidConfigException
     * @deprecated
     */
    public function create(ShastaResource &$shastaResource, $runValidation = true, $attributes = null)
    {
        try{
            return $shastaResource->insert($runValidation, $attributes);
        } catch (Exception $e){
            return false;
        }
    }

    /**
     * @param ShastaResource $shastaResource
     * @param bool $runValidation
     * @param null $attributes
     * @return bool
     * @throws InvalidConfigException
     * @deprecated
     */
    public function update(ShastaResource &$shastaResource, $runValidation = true, $attributes = null)
    {
        try{
            return $shastaResource->update($runValidation, $attributes);
        } catch (Exception $e){
            return false;
        }
    }

    /**
     * @param ShastaResource $shastaResource
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     * @deprecated
     */
    public function read(ShastaResource &$shastaResource)
    {
        try{
            return $shastaResource->read();
        } catch (Exception $e){
            return false;
        }
    }

    /**
     * @param ShastaResource $shastaResource
     * @param array $condition
     * @return array
     * @throws InvalidConfigException
     * @deprecated
     */
    public function readAll(ShastaResource &$shastaResource, $condition = [])
    {
        try{
            return $shastaResource::findAll($condition);
        } catch (Exception $e){
            return false;
        }
    }

    /**
     * @param ShastaResource $shastaResource
     * @param Response $response
     * @return bool
     * @deprecated
     */
    public function load(ShastaResource &$shastaResource, Response $response)
    {
        try{
            if (!$response->isOk) {
                $shastaResource->addError('Error' . $response->statusCode, $response->data);
                return false;
            }
            $shastaResource->scenario = ShastaResource::SCENARIO_LOAD;
            $shastaResource->setAttributes($response->data);

            return true;
        } catch (Exception $e){
            return false;
        }
    }
}
