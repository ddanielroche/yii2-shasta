Yii2 Shasta Payments Integration
================================
Yii2 Shasta Payments Integration

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist ddroche/yii2-shasta "*"
```

or add

```
"ddroche/yii2-shasta": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by:

```php
'modules' => [
    'shasta' => [
        'class' => 'ddroche\shasta\Module',
        // Develop Enviroment
        'apiEndPoint' => 'https://api-sandbox.payments.shasta.me/v1',
        // Production Enviroment
        'apiEndPoint' => 'https://api.payments.shasta.me/v1',
        // Your Production or Development Enviroment API Key
        'apiKey' => 'Bearer key_...',
    ],
]
```

Class Resources whit functions
-----
```php
Project (GET, SET)
Accounts (All, Create, Read, Update)
Transactions (All, allAccountsTransactions, Read)
Customers (All, Create, Read, Update)
Transfers (All, Create, Read, Update)
CardTokens (Create, Read)
Cards (All, Create, Read, Update)
CardPayins (All, Create, Read, Update, Finish)
CardPayinsRefunds (All, Create, Read, Update)
CardVerifications (All, Create, Read, Update, Finish)
BankAccounts (All, Create, Read, Update)
BankPayinReferences (All, Create, Read, Update)
BankPayins (All, Read, Update)
BankPayout (All, Create, Read, Update)
```
