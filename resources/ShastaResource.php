<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\httpclient\Exception;
use yii\httpclient\Request;
use yii\httpclient\Response;

/**
 * Class Shasta
 *
 * @see https://doc.payments.shasta.me/
 *
 * @package ddroche\shasta\resource
 *
 * @property string $resource
 */
abstract class ShastaResource extends Model
{
    public abstract function getResource();

    /**
     * @return Request
     * @throws InvalidConfigException
     */
    public function getRequest()
    {
        /** @var Module $shasta */
        $shasta = Yii::$app->modules['shasta'];
        return $shasta->getRequest();
    }

    /**
     * Get Collection
     *
     * @param int $limit
     * @param string $cursor
     *
     * @return Response
     *
     * @throws InvalidConfigException
     */
    public function all($limit, $cursor)
    {
        $response = $this->getRequest()
            ->setMethod('GET')
            ->setUrl($this->resource)
            ->setData([
                'limit' => $limit,
                'cursor' => $cursor,
            ])
            ->send();

        return $response;
    }

    /**
     * Create Object
     *
     * @param array $body
     *
     * @return Response
     *
     * @throws InvalidConfigException
     */
    protected function create($body)
    {
        $response = $this->getRequest()
            ->setMethod('POST')
            ->setUrl($this->resource)
            ->setData($body)
            ->send();

        return $response;
    }

    /**
     * Get Object
     *
     * @param string $id
     *
     * @return Response
     *
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function read($id)
    {
        $response = $this->getRequest()
            ->setMethod('GET')
            ->setUrl("$this->resource/$id")
            ->send();

        return $response;
    }

    /**
     * Edit Object
     *
     * @param string $id
     * @param array $body
     *
     * @return Response
     *
     * @throws InvalidConfigException
     */
    public function update($id, $body)
    {
        $response = $this->getRequest()
            ->setMethod('PATCH')
            ->setUrl("$this->resource/$id")
            ->setData($body)
            ->send();

        return $response;
    }
}
