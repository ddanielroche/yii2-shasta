<?php

namespace ddroche\shasta\traits;
use ddroche\shasta\resources\ShastaResource;
use yii\base\Exception as BaseException;

trait RelationalTrait {

    protected $_related;

    /**
     * @param string $resource
     * @param string $attribute
     * @return ShastaResource|null
     * @throws BaseException
     */
    public function hasOne($resource, $attribute)
    {
        if (!is_subclass_of($resource, ShastaResource::class)) {
            throw new BaseException('ShastaResource class');
        }
        if (!$this->hasProperty($attribute)) {
            throw new BaseException("attribute $attribute not exit in this class");
        }
        /** @var ShastaResource $resource */
        if (!isset($this->_related[$attribute])) {
            $this->_related[$attribute] = $resource::findOne($this->$attribute);
        }

        return $this->_related[$attribute];
    }
}