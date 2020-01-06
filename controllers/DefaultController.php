<?php


namespace app\controllers;

use ddroche\shasta\resources\Account;
use ddroche\shasta\resources\Card;
use ddroche\shasta\resources\Address;
use ddroche\shasta\resources\CardInfo;
use ddroche\shasta\resources\Customer;
use ddroche\shasta\Shasta;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\httpclient\Exception;

/**
 * Class ShastaController
 * @package ddroche\shasta\controllers
 */
class DefaultController extends Controller
{
    /**
     * @see https://doc.payments.shasta.me/
     *
     * 1- Create Project
     * 2- Create Customer in Project
     * 3- Create Bank Account for Customer in Project
     * 4- Create Account for Customer in Project
     * 5- Create Bank Payin References
     * 6- Create Bank Payins
     * 7- Create Transfer
     *
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function actionIndex()
    {
        $address = new Address();
        $address->line_1 = 'Avenida Omejos, 5';
        $address->line_2 = 'Atico 2a';
        $address->postal_code = '08291';
        $address->city = "L'Hospitalet de Llobregat";
        $address->region = 'Barcelona';
        $address->country = 'ES';

        $customer = new Customer();
        $customer->first_name = 'Pepe';
        $customer->last_name = 'Perez Perez';
        $customer->email_address = 'pepe@example.com';
        $customer->phone_number = '12';
        $customer->nationality = 'ES';
        $customer->employment_status = 'student';
        $customer->address = $address;

        /** @var Shasta $shasta */
        $shasta = \Yii::$app->get('shasta');
        if ($shasta->create($customer)) {
            print_r($customer->attributes);
            $customer = new Customer(['id' => $customer->id]);
            $customer->last_name = 'Perez2';
            
            if ($shasta->update($customer)) {
                print_r($customer->attributes);
            } else {
                print_r($customer->getErrors());
            }
            $customer = new Customer(['id' => $customer->id]);
            $shasta->read($customer);
            print_r($customer->attributes);
        } else {
            print_r($customer->getErrors());
        }

        $account = new Account();
        $account->currency = 'EUR';

        if ($shasta->create($account)) {
            print_r($account->attributes);
            $account->customer_id = $customer->id;
            $account->allow_negative_balance = true;
            $account->auto_bank_payout = [
                'min_balance' => [
                    'currency' => 'EUR',
                    'amount' => '10.10'
                ],
                'concept' => 'Test'
            ];
            if ($shasta->update($account)) {
                print_r($account->attributes);
            } else {
                print_r($account->getErrors());
            }
        } else {
            print_r($account->getErrors());
        }
    }
}