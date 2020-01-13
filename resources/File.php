<?php

namespace ddroche\shasta\resources;

use Yii;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

/**
 * Class File
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-File
 *
 * @property string $md5
 * @property string $crc32
 * @property string $filename
 * @property integer $filetype
 * @property boolean $public
 * @property string $url
 * @property string $signed_url
 */
class File extends ShastaResource
{
    /** @var string */
    public $md5;
    /** @var string */
    public $crc32;
    /** @var string */
    public $filename;
    /** @var integer */
    public $filetype;
    /** @var boolean */
    public $public;
    /** @var string */
    public $url;
    /** @var string */
    public $signed_url;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['md5', 'crc32', 'filename', 'url', 'signed_url'], 'string', 'on' => static::SCENARIO_LOAD],
            [['filetype'], 'integer', 'on' => static::SCENARIO_LOAD],
            [['public'], 'boolean'],
        ]);
    }

    public static function resource()
    {
        return '/files';
    }

    /**
     * @param string $fileName
     * @return bool
     * @throws InvalidConfigException
     */
    public function upload($fileName)
    {
        if (!$this->validate()) {
            Yii::info('Model not inserted due to validation error.', __METHOD__);
            return false;
        }

        $attributes = $this->safeAttributes();
        $toArray = [];
        foreach ($attributes as $attribute) {
            if (isset($this->$attribute)) {
                $toArray[] = $attribute;
            }
        }
        $shasta = static::getShasta();
        $response = $shasta->createRequest()
            ->setMethod('POST')
            ->setUrl($this->resource)
            ->addFile('file', $fileName)
            ->setData($this->toArray($toArray))
            ->send();

        return $shasta->load($this, $response);
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws Exception
     *
     * TODO Method 'DELETE' not allowed for path '/v1/files/file_3qm8s8zls6dyvkt7r7e1'
     */
    public function delete()
    {
        if (!$this->id) {
            $this->addError('Id is require for read operation');
            return false;
        }

        $shasta = static::getShasta();
        $response = $shasta->createRequest()
            ->setMethod('DELETE')
            ->setUrl("$this->resource/$this->id")
            ->send();

        if (!$response->isOk) {
            $this->addError('Error' . $response->statusCode, $response->data);
            return false;
        }

        return true;
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws Exception
     *
     * TODO Path '/v1/files/file_3qm8t81tqct1fh2jlp7h/download' not found
     */
    public function download()
    {
        if (!$this->id) {
            $this->addError('Id is require for read operation');
            return false;
        }

        $shasta = static::getShasta();
        $response = $shasta->createRequest()
            ->setMethod('GET')
            ->setUrl("$this->resource/$this->id/download")
            ->send();

        return $shasta->load($this, $response);
    }
}