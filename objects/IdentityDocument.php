<?php

namespace ddroche\shasta\objects;

use ddroche\shasta\enums\DocumentType;
use ddroche\shasta\enums\DocumentStatus;
use ddroche\shasta\resources\ShastaResource;
use ddroche\shasta\traits\RelationalTrait;
use tigrov\intldata\Country;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class IdentityDocument
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#/definitions/IdentityDocument
 */
class IdentityDocument extends Model
{
    use RelationalTrait;

    /** @var string Document type */
    public $type;
    /** @var string Country */
    public $country;
    /** @var string Document number */
    public $number;
    /** @var string Document status */
    public $status;
    /** @var string Document Expiration Date*/
    public $expiration_date;
    /** @var string */
    public $front_file_id;
    /** @var string */
    public $back_file_id;
    /** @var string */
    public $selfie_file_id;
    /** @var string */
    public $verification_file_id;

    public function rules()
    {
        return [
            [['type', 'country', 'number', 'status', 'expiration_date', 'front_file_id', 'back_file_id', 'selfie_file_id', 'verification_file_id'], 'string'],
            ['type', 'in', 'range' => DocumentType::getConstantsByName()],
            ['status', 'in', 'range' => DocumentStatus::getConstantsByName()],
            ['country', 'in', 'range' => Country::CODES],
            [['front_file_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'frontFile'],
            [['back_file_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'backFile'],
            [['selfie_file_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'selfieFile'],
            [['verification_file_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'verificationFile'],
        ];
    }

    public function fields()
    {
        return array_merge(parent::fields(), ['frontFile', 'backFile', 'selfieFile', 'verificationFile']);
    }

    /**
     * @return ShastaResource|null
     * @throws Exception
     */
    public function getFrontFile()
    {
        return $this->hasOne('ddroche\shasta\resources\File', 'front_file_id');
    }

    /**
     * @return ShastaResource|null
     * @throws Exception
     */
    public function getBackFile()
    {
        return $this->hasOne('ddroche\shasta\resources\File', 'back_file_id');
    }

    /**
     * @return ShastaResource|null
     * @throws Exception
     */
    public function getSelfieFile()
    {
        return $this->hasOne('ddroche\shasta\resources\File', 'selfie_file_id');
    }

    /**
     * @return ShastaResource|null
     * @throws Exception
     */
    public function getVerificationFile()
    {
        return $this->hasOne('ddroche\shasta\resources\File', 'verification_file_id');
    }
}
