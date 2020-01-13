<?php

namespace ddroche\shasta\objects;

use ddroche\shasta\resources\BankAccount;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\httpclient\Exception;

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
            ['min_balance', 'validateBalance'],
            ['bank_account_id', 'validateBanckAccount'],
        ];
    }

    /**
     * TODO refactor to validator class
     */
    public function validateBalance()
    {
        if (is_array($this->min_balance)) {
            $this->min_balance = new Value($this->min_balance);
        }
        if (!$this->min_balance instanceof Value) {
            $this->addError('min_balance', 'The attribute must be an instance of Value');
        } elseif (!$this->min_balance->validate()) {
            foreach ($this->min_balance->getErrors() as $field => $error) {
                $this->addError('min_balance.' . $field, $error);
            }
        }
    }



    /**
     * Validate whit customer present y Shasta
     *
     * TODO refactor to validator class
     *
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function validateBanckAccount()
    {
        if ($this->getBanckAccount() === null) {
            $this->addError('bank_account_id', "Invalid bank_account_id");
            return false;
        }
        return true;
    }

    /**
     * TODO refactor to relational class
     *
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function getBanckAccount()
    {
        if (!isset($this->_releated['bank_account_id'])) {
            $this->_releated['bank_account_id'] = BankAccount::findOne($this->bank_account_id);
        }

        return $this->_releated['bank_account_id'];
    }
}
