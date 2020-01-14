<?php

namespace ddroche\shasta\objects;

use ddroche\shasta\resources\ShastaResource;
use ddroche\shasta\traits\RelationalTrait;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class Address
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#/definitions/AutoBankPayout
 *
 * @property Value|array $min_balance
 * @property string $bank_account_id
 * @property string $concept
 */
class AutoBankPayout extends Model
{
    use RelationalTrait;

    /** @var Value|array */
    public $min_balance;
    /** @var string */
    public $bank_account_id;
    /** @var string */
    public $concept;

    private $_releated;

    public function rules()
    {
        return [
            [['bank_account_id', 'concept'], 'string'],
            [['min_balance'], 'ddroche\shasta\validators\ObjectValidator', 'targetClass' => Value::class],
            [['bank_account_id'], 'ddroche\shasta\validators\ExistValidator', 'targetRelation' => 'bankAccount'],
        ];
    }

    /**
     * @return ShastaResource|null
     * @throws Exception
     */
    public function getBankAccount()
    {
        return $this->hasOne('ddroche\shasta\resources\BankAccount', 'bank_account_id');
    }
}
