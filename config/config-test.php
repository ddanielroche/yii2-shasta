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
        'shasta' => [
            'class' => 'ddroche\shasta\Shasta',
            'apiBaseUrl' => 'https://api-sandbox.payments.shasta.me/v1',
            'apiKey' => getenv('SHASTA_API_KEY'),
        ],
    ],
];
