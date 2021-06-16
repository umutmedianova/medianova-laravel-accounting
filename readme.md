# Medianova Laravel Accounting

### Support Libraries

- Quickbooks 
- LOGO 

### Installation

You can install the package via composer:

```bash
composer require medianova/laravel-accounting
```

configuration in `config/accounting.php`

```php
return [
    'provider'=>env('ACCOUNTING_PROVIDER', 'quickbooks'),
    'quickbooks'=>[
        'client_id' => env('ACCOUNTING_CLIENT_ID', 'CLIENT_ID'),
        'client_secret' => env('ACCOUNTING_CLIENT_SECRET', 'CLIENT_SECRET'),
        'username' => env('ACCOUNTING_USERNAME', 'USERNAME'),
        'password' => env('ACCOUNTING_PASSWORD', 'PASSWORD'),
        'redirect_url' => env('ACCOUNTING_REDIRECT_URI', 'https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl'),
        'scope' => env('ACCOUNTING_OAUTH_SCOPE', 'com.intuit.quickbooks.accounting'),
        'base_url' =>  env('ACCOUNTING_BASE_URL', 'https://appcenter.intuit.com/connect'),
    ],
];
```

## Usage

```php
<?php

use Medianova\LaravelAccounting\Facades\Accounting;

Accounting::create([], 'customer');
```

## Or use by choosing a provider

```php
Accounting::provider('quickbooks')->create([], 'customer');
```