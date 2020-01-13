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
            'apiKey' => 'Bearer key_3qg9ebgnpp5cerbuzpp1_66d8203a9205cdd449e02968d28c163c1d9fdd3eb4eb5fefc3255d1576e3a370',
        ],
    ],
    'modules' => [
        'shasta' => [
            'class' => 'ddroche\shasta\Module',
        ],
    ]
];
