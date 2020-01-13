<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\enums\EmploymentStatus;
use ddroche\shasta\objects\Address;
use tigrov\intldata\Country;

/**
 * Class Customer
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-Customer
 *
 * @property string $first_name
 * @property string $last_name
 * @property string $email_address
 * @property string $phone_number
 * @property string $nationality
 * @property string $employment_status
 * @property array|Address $address
 */
class Customer extends ShastaResource
{
    /** @var string */
    public $first_name;
    /** @var string */
    public $last_name;
    /** @var string */
    public $email_address;
    /** @var string */
    public $phone_number;
    /** @var string */
    public $nationality;
    /** @var string */
    public $employment_status;
    /** @var array|Address */
    public $address;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['first_name', 'last_name', 'email_address', 'phone_number'], 'required', 'on' => static::SCENARIO_CREATE],
            [['first_name', 'last_name', 'email_address', 'phone_number', 'nationality', 'employment_status'], 'string'],
            ['email_address', 'email'],
            ['nationality', 'in', 'range' => Country::CODES],
            ['employment_status', 'in', 'range' => EmploymentStatus::getConstantsByName()],
            ['address', 'validateAddress'],
        ]);
    }

    public function validateAddress()
    {
        if (is_array($this->address)) {
            $this->address = new Address($this->address);
        }
        if (!$this->address instanceof Address) {
            $this->addError('address', 'The attribute must be an instance of Address');
        } elseif (!$this->address->validate()) {
            foreach ($this->address->getErrors() as $field => $error) {
                $this->addError('address.' . $field, $error);
            }
        }
    }

    public static function resource()
    {
        return '/customers';
    }
}
