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
     'logo'=>[
        'base_url' =>  env('ACCOUNTING_BASE_URL', 'http://localhost'),
        'username' => env('ACCOUNTING_USERNAME', 'XXXXXXXXXXXXX'),
        'password' => env('ACCOUNTING_PASSWORD', 'XXXXXXXXXXXXX'),
    ],
    'quickbooks'=>[
        'access_token' => env('ACCOUNTING_ACCESS_TOKEN', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'),
        'refresh_token' => env('ACCOUNTING_REFRESH_TOKEN', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'),
        'real_me_id' => env('ACCOUNTING_REAL_ME_ID', 'XXXXXXXXXXXXXXXXXXXX'),
        'client_id' => env('ACCOUNTING_CLIENT_ID', 'XXXXXXXXXXXXX'),
        'client_secret' => env('ACCOUNTING_CLIENT_SECRET', 'XXXXXXXXXXXXX'),
        'redirect_url' => env('ACCOUNTING_REDIRECT_URI', 'https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl'),
        'scope' => env('ACCOUNTING_OAUTH_SCOPE', 'com.intuit.quickbooks.accounting, openID, profile, phone, address'),
        'base_url' =>  env('ACCOUNTING_BASE_URL', 'development'),
    ],
];
```

## Usage

```php
<?php

use Medianova\LaravelAccounting\Facades\Accounting;

Accounting::customer([])->create();
Accounting::customer([],0)->update();
Accounting::invoice([])->create(); 

```

## Or use by choosing a provider

### Customer
```php
Accounting::provider('quickbooks')->customer([
  "BillAddr" => [
     "Line1"=>  "123 Main Street",
     "City"=>  "Mountain View",
     "Country"=>  "USA",
     "CountrySubDivisionCode"=>  "CA",
     "PostalCode"=>  "94042"
 ],
 "Notes" =>  "Here are other details.",
 "Title"=>  "Mr",
 "GivenName"=>  "Evil",
 "MiddleName"=>  "1B",
 "FamilyName"=>  "King",
 "Suffix"=>  "Jr",
 "FullyQualifiedName"=>  "Evil King",
 "CompanyName"=>  "King Evial",
 "DisplayName"=>  "Evil King Sr2",
 "PrimaryPhone"=>  [
     "FreeFormNumber"=>  "(555) 555-5555"
 ],
 "PrimaryEmailAddr"=>  [
     "Address" => "evilkingw@myemail.com"
 ]
])->create();
```

### Invoice
```php
Accounting::provider('quickbooks')->invoice([
     "Line" => [
   [
     "Amount" => 100.00,
     "DetailType" => "SalesItemLineDetail",
     "SalesItemLineDetail" => [
       "ItemRef" => [
         "value" => 20,
         "name" => "Hours"
        ]
      ]
      ]
    ],
"CustomerRef"=> [
  "value"=> 59
],
      "BillEmail" => [
            "Address" => "Familiystore@intuit.com"
      ],
      "BillEmailCc" => [
            "Address" => "a@intuit.com"
      ],
      "BillEmailBcc" => [
            "Address" => "v@intuit.com"
      ]
])->create();
```