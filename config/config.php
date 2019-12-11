<?php

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['shasta'],
    'controllerNamespace' => 'app\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
    ],
    'modules' => [
        'shasta' => [
            'class' => 'ddroche\shasta\Module',
            'apiEndPoint' => 'https://api-sandbox.payments.shasta.me/v1',
            'apiKey' => 'Bearer key_...',
        ],
    ]
];
