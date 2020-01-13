<?php

namespace ddroche\shasta\tests;

use Codeception\Specify;
use Codeception\Test\Unit;
use ddroche\shasta\resources\Address;
use ddroche\shasta\resources\Customer;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

class CustomerTest extends Unit
{
    use Specify;

    /**
     * @var UnitTester
     */
    protected $tester;

    /**
     * @var Customer
     * @specify
     */
    private $customer;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests

    /**
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function testValidationAndNotInsert()
    {
        $this->customer = new Customer();
        $this->customer->scenario = Customer::SCENARIO_CREATE;

        $this->specify("first_name, last_name, email_address and phone_number cannot be blank", function() {
            $this->customer->first_name = null;
            $this->customer->last_name = null;
            $this->customer->email_address = null;
            $this->customer->phone_number = null;
            $this->assertFalse($this->customer->save());
            $this->assertEquals(4, count($this->customer->getErrors()));
        });

        $this->customer->first_name = 'Javier';
        $this->customer->last_name = 'Hernandez';
        $this->customer->email_address = 'javi@example.com';
        $this->customer->phone_number = '123456789';

        $this->specify("employment_status and nationality is invalid", function() {
            $this->customer->employment_status = 'status';
            $this->customer->nationality = 'nationality';
            $this->assertFalse($this->customer->save());
            $this->assertEquals(2, count($this->customer->getErrors()));
        });

        $this->specify("address is invalid", function() {
            $this->customer->address = null;
            $this->assertTrue($this->customer->validate(['address']));
            $this->customer->address = 'address';
            $this->assertFalse($this->customer->validate(['address']));
            $this->customer->address = ['country' => 'dsdsd'];
            $this->assertFalse($this->customer->validate(['address']));
            $this->customer->address = new Address(['country' => 'dsdsd']);
            $this->assertFalse($this->customer->validate(['address']));
            $this->assertFalse($this->customer->save());
            $this->assertEquals(1, count($this->customer->getErrors()));
        });
    }

    /**
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function testValidationAndInsert()
    {
        $address = new Address();
        $address->line_1 = 'Avenida Omejos, 5';
        $address->line_2 = 'Atico 2a';
        $address->postal_code = '08291';
        $address->city = "L'Hospitalet de Llobregat";
        $address->region = 'Barcelona';
        $address->country = 'ES';

        $attributes = [
            'first_name' => 'Javier',
            'last_name' => 'Hernandez',
            'email_address' => 'javi@example.com',
            'phone_number' => '123456789',
            'employment_status' => 'student',
            'nationality' => 'ES',
        ];

        $this->customer = new Customer();
        $this->customer->scenario = Customer::SCENARIO_CREATE;
        $this->customer->setAttributes($attributes);
        $this->customer->address = $address;
        $this->assertTrue($this->customer->save());
        $this->assertNotNull($this->customer->id);
        $this->assertNotNull($this->customer->created_at);
        $this->assertNotNull($this->customer->project_id);

        $customer = Customer::findOne($this->customer->id);
        $this->assertArrayContains($attributes, $customer);
    }

    /**
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function testFindAllAndUpdateOne()
    {
        $customers = Customer::findAll();
        $this->assertGreaterThan(1, count($customers));
        $this->customer = $customers[1];

        $attributes = [
            'first_name' => 'Javier1',
            'last_name' => 'Hernandez1',
            'email_address' => 'javi1@example.com',
            'phone_number' => '123456781',
            'employment_status' => 'employed',
            'nationality' => 'FR',
        ];

        $this->customer->setAttributes($attributes);
        $this->assertTrue($this->customer->save());

        $customer = Customer::findOne($this->customer->id);
        $this->assertArrayContains($attributes, $customer);
    }

    public function assertArrayContains($needle, $haystack)
    {
        foreach ($needle as $key => $value) {
            $this->assertEquals($value, $haystack[$key]);
        }
    }
}