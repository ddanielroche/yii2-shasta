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
     * Create resource into the Shasta RESTful API using ShastaResource object
     *
     * Usage example:
     *
     * ```php
     * $customer = new Customer;
     * $customer->first_name = $first_name;
     * $customer->last_name = $last_name;
     * $this->create($customer);
     * ```
     *
     * @param ShastaResource $shastaResource
     * @param bool $runValidation whether to perform validation (calling [[\yii\base\Model::validate()|validate()]])
     * before saving the record. Defaults to `true`. If the validation fails, the record
     * will not be saved to the database and this method will return `false`.
     * @param array $attributes list of attributes that need to be saved. Defaults to `null`.
     * @return bool whether the attributes are valid and the record is inserted successfully.
     * @throws InvalidConfigException
     */
    public function create(ShastaResource &$shastaResource, $runValidation = true, $attributes = null)
    {
        $shastaResource->scenario = ShastaResource::SCENARIO_CREATE;
        
        if ($runValidation && !$shastaResource->validate($attributes)) {
            Yii::info('Model not inserted due to validation error.', __METHOD__);
            return false;
        }

        $attributes = $shastaResource->safeAttributes();
        $toArray = [];
        foreach ($attributes as $attribute) {
            if (isset($shastaResource->$attribute)) {
                $toArray[] = $attribute;
            }
        }

        $response = $this->createRequest()
            ->setMethod('POST')
            ->setUrl($shastaResource->resource)
            ->setData($shastaResource->toArray($toArray))
            ->send();

        return $this->load($shastaResource, $response);
    }

    /**
     * @param ShastaResource $shastaResource
     * @param bool $runValidation
     * @param null $attributes
     * @return bool
     * @throws InvalidConfigException
     */
    public function update(ShastaResource &$shastaResource, $runValidation = true, $attributes = null)
    {
        if (!$shastaResource->id) {
            $shastaResource->addError('Id is require for update operation');
            return false;
        }
        $shastaResource->scenario = ShastaResource::SCENARIO_DEFAULT;

        if ($runValidation && !$shastaResource->validate($attributes)) {
            Yii::info('Model not updated due to validation error.', __METHOD__);
            return false;
        }

        $attributes = $shastaResource->safeAttributes();        
        $toArray = [];
        foreach ($attributes as $attribute) {
            if (isset($shastaResource->$attribute)) {
                $toArray[] = $attribute;
            }
        }
        
        $response = $this->createRequest()
            ->setMethod('PATCH')
            ->setUrl("$shastaResource->resource/$shastaResource->id")
            ->setData($shastaResource->toArray($toArray))
            ->send();
        return $this->load($shastaResource, $response);
    }

    /**
     * @param ShastaResource $shastaResource
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function read(ShastaResource &$shastaResource)
    {
        if (!$shastaResource->id) {
            $shastaResource->addError('Id is require for read operation');
            return false;
        }

        $response = $this->createRequest()
            ->setMethod('GET')
            ->setUrl("$shastaResource->resource/$shastaResource->id")
            ->send();

        return $this->load($shastaResource, $response);
    }

    /**
     * @param ShastaResource $shastaResource
     * @param array $condition
     * @return array
     * @throws InvalidConfigException
     */
    public function readAll(ShastaResource &$shastaResource, $condition = [])
    {
        $response = $this->createRequest()
            ->setFormat(Client::FORMAT_URLENCODED)
            ->setMethod('GET')
            ->setUrl($shastaResource->resource)
            ->setData($condition)
            ->send();

        if (!$response->isOk) {
            $shastaResource->addError('Error' . $response->statusCode, $response->data);
            return [];
        }

        $result = [];
        foreach ($response->data['data'] as $record) {
            $tmp = clone $shastaResource;
            $tmp->setAttributes($record);
            $result[] = $tmp;
        }

        return $result;
    }

    /**
     * @param ShastaResource $shastaResource
     * @param Response $response
     * @return bool
     */
    public function load(ShastaResource &$shastaResource, Response $response)
    {
        if (!$response->isOk) {
            $shastaResource->addError('Error' . $response->statusCode, $response->data);
            return false;
        }
        $shastaResource->scenario = ShastaResource::SCENARIO_LOAD;
        $shastaResource->setAttributes($response->data);

        return true;
    }
}