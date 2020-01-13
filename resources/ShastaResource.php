<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\Shasta;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\httpclient\Response;

/**
 * Class Shasta
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/
 *
 * @property string $id
 * @property string $created_at
 * @property string $project_id
 * @property array $meta
 */
abstract class ShastaResource extends Model
{
    const SCENARIO_LOAD = 'load';
    const SCENARIO_CREATE = 'create';

    /** @var string */
    public $id;

    /** @var string */
    public $created_at;

    /** @var string */
    public $project_id;

    /** @var array */
    public $meta;

    public abstract static function resource();

    public function rules()
    {
        return [
            [['id', 'created_at', 'project_id'], 'string', 'on' => static::SCENARIO_LOAD],
            ['meta', 'safe', 'on' => static::SCENARIO_LOAD],
        ];
    }

    /**
     * @return Shasta
     * @throws InvalidConfigException
     */
    public static function getShasta()
    {
        /** @var Shasta $shasta */
        $shasta = Yii::$app->get('shasta');
        return $shasta;
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws InvalidConfigException
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this->id == null) {
            return $this->insert($runValidation, $attributeNames);
        }

        return $this->update($runValidation, $attributeNames);
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
     * @param bool $runValidation whether to perform validation (calling [[\yii\base\Model::validate()|validate()]])
     * before saving the record. Defaults to `true`. If the validation fails, the record
     * will not be saved to the database and this method will return `false`.
     * @param array $attributes list of attributes that need to be saved. Defaults to `null`.
     * @return bool whether the attributes are valid and the record is inserted successfully.
     * @throws InvalidConfigException
     */
    public function insert($runValidation = true, $attributes = null)
    {
        $this->scenario = static::SCENARIO_CREATE;

        if ($runValidation && !$this->validate($attributes)) {
            Yii::info('Model not inserted due to validation error.', __METHOD__);
            Yii::info($this->getErrors(), __METHOD__);
            return false;
        }

        $attributes = $this->safeAttributes();
        $toArray = [];
        foreach ($attributes as $attribute) {
            if (isset($this->$attribute)) {
                $toArray[] = $attribute;
            }
        }

        $response = static::getShasta()->createRequest()
            ->setMethod('POST')
            ->setUrl(static::resource())
            ->setData($this->toArray($toArray))
            ->send();

        return $this->loadAttributes($response);
    }

    /**
     * @param bool $runValidation
     * @param null $attributes
     * @return bool
     * @throws InvalidConfigException
     */
    public function update($runValidation = true, $attributes = null)
    {
        if (!$this->id) {
            $this->addError('Id is require for update operation');
            return false;
        }
        $this->scenario = ShastaResource::SCENARIO_DEFAULT;

        if ($runValidation && !$this->validate($attributes)) {
            Yii::info('Model not updated due to validation error.', __METHOD__);
            Yii::info($this->getErrors(), __METHOD__);
            return false;
        }

        $attributes = $this->safeAttributes();
        $toArray = [];
        foreach ($attributes as $attribute) {
            if (isset($this->$attribute)) {
                $toArray[] = $attribute;
            }
        }

        static::resource();

        $response = static::getShasta()->createRequest()
            ->setMethod('PATCH')
            ->setUrl(static::resource() . "/$this->id")
            ->setData($this->toArray($toArray))
            ->send();

        return $this->loadAttributes($response);
    }

    /**
     * @param $condition
     * @return ShastaResource
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function findOne($condition)
    {
        /** @var ShastaResource $object */
        $object = new static(['id' => $condition]);
        $object->read();
        return $object;
    }

    /**
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function read()
    {
        if (!$this->id) {
            $this->addError('Id is require for read operation');
            return false;
        }

        $response = static::getShasta()->createRequest()
            ->setMethod('GET')
            ->setUrl(static::resource() . "/$this->id")
            ->send();

        return $this->loadAttributes($response);
    }

    /**
     * @param array $condition
     * @return array
     * @throws InvalidConfigException
     */
    public static function findAll($condition = [])
    {
        $response = static::getShasta()->createRequest()
            ->setFormat(Client::FORMAT_URLENCODED)
            ->setMethod('GET')
            ->setUrl(static::resource())
            ->setData($condition)
            ->send();

        if (!$response->isOk) {
            $tmp = new static();
            $tmp->addError('Error' . $response->statusCode, $response->data);
            return [$tmp];
        }

        $result = [];
        foreach ($response->data['data'] as $record) {
            $tmp = new static();
            $tmp->setAttributes($record);
            $result[] = $tmp;
        }

        return $result;
    }

    /**
     * @param Response $response
     * @return bool
     */
    public function loadAttributes(Response $response)
    {
        if (!$response->isOk) {
            $this->addError('Error' . $response->statusCode, $response->data);
            Yii::info($this->getErrors(), __METHOD__);
            return false;
        }
        $this->scenario = ShastaResource::SCENARIO_LOAD;
        $this->setAttributes($response->data);

        return true;
    }
}
