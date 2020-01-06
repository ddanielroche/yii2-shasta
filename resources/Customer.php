<?php

namespace ddroche\shasta\resources;

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
 * @property array $address
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
    /** @var array */
    public $address;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['first_name', 'last_name', 'email_address', 'phone_number'], 'required', 'on' => static::SCENARIO_CREATE],
            [['first_name', 'last_name', 'email_address', 'phone_number', 'nationality', 'employment_status'], 'string'],
            [['address'], 'safe'],
            ['employment_status', 'in', 'range' => ['student', 'employed', 'self_employed', 'searching', 'not_employed']],
            ['email_address', 'email'],
        ]);
    }

    public function getResource()
    {
        return '/customers';
    }
}
