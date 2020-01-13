<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\enums\Currency;
use ddroche\shasta\objects\AutoBankPayout;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

/**
 * Class Account
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-Account
 *
 * @property string $currency
 * @property string $customer_id
 * @property array $balance
 * @property AutoBankPayout|array $auto_bank_payout
 * @property boolean $allow_negative_balance
 */
class Account extends ShastaResource
{
    /** @var string */
    public $currency;
    /** @var string */
    public $customer_id;
    /** @var array */
    public $balance;
    /** @var AutoBankPayout|array */
    public $auto_bank_payout;
    /** @var boolean */
    public $allow_negative_balance;

    /**
     * Rules established according to the documentation
     *
     * @see https://doc.payments.shasta.me/#tag-Accounts
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            /** @see https://doc.payments.shasta.me/#operation--accounts-post */
            [['currency'], 'required', 'on' => static::SCENARIO_CREATE],
            ['currency', 'in', 'range' => Currency::getConstantsByName(), 'on' => static::SCENARIO_CREATE],
            [['currency'], 'string', 'on' => static::SCENARIO_CREATE],
            [['customer_id'], 'validateCustomer'],
            ['allow_negative_balance', 'boolean'],
            ['allow_negative_balance', 'default', 'value' => false],
            [['auto_bank_payout'], 'validateAutoBankPayout'],
            [['balance'], 'safe', 'on' => static::SCENARIO_LOAD],
        ]);
    }

    /**
     * Validate whit customer present y Shasta
     *
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function validateCustomer()
    {
        if ($this->getCustomer() === null) {
            $this->addError('customer_id', "Invalid customer_id");
            return false;
        }
        return true;
    }

    /**
     * TODO refactor to validator class
     */
    public function validateAutoBankPayout()
    {
        if (is_array($this->auto_bank_payout)) {
            $this->auto_bank_payout = new AutoBankPayout($this->auto_bank_payout);
        }
        if (!$this->auto_bank_payout instanceof AutoBankPayout) {
            $this->addError('auto_bank_payout', 'The attribute must be an instance of AutoBankPayout');
        } elseif (!$this->auto_bank_payout->validate()) {
            foreach ($this->auto_bank_payout->getErrors() as $field => $error) {
                $this->addError('auto_bank_payout.' . $field, $error);
            }
        }
    }

    public static function resource()
    {
        return '/accounts';
    }

    /**
     * TODO refactor to relational class
     *
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function getCustomer()
    {
        if (!isset($this->_releated['customer_id'])) {
            $this->_releated['customer_id'] = Customer::findOne($this->customer_id);
        }

        return $this->_releated['customer_id'];
    }
}
