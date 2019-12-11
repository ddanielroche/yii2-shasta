<?php


namespace app\controllers;

use ddroche\shasta\resources\Project;
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
        $project = new Project();

        return $project->getProject();
    }
}