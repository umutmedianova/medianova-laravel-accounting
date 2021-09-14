<?php

return [
    'provider'=>env('ACCOUNTING_PROVIDER', 'logo'),
    'logo'=>[
        'base_url' =>  env('ACCOUNTING_LOGO_BASE_URL', '***'),
        'username' => env('ACCOUNTING_LOGO_USERNAME', '***'),
        'password' => env('ACCOUNTING_LOGO_PASSWORD', '***'),
        'company_id' => env('ACCOUNTING_LOGO_COMPANY_ID', 999),
    ],
    'quickbooks'=>[
        'access_token' => env('ACCOUNTING_ACCESS_TOKEN', 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..hXu0lyrqUtpvwBwSjknsbA.E_Qh4vnNfDXuQ_2eE-HwYgm31dpY1x5YAtPrg6H0nkkxNuTeP4mclyD1PVfiZYeLSSLe1cA1pJzzQaB6TiD7nHoTng6V0skchyXG4ATiWG6iP2dR890bYiacFcCDVfr4KtaQnq6c74Wq-f8chOk4-H1zyCUZ0Grg957iVupi9NthuAjJpEUUMK1G5gZrcevRbKgO31blMK7lMcT_4aHLv8i9zV-wvBoW4gXli2-TDOvvjbdQMBLZtPEPOfS8xWCunwOtb10QF0SBm4jJ_brx_NaMKtOr3B2AXKuJFsfcdq2pd_6Ut2poayTqBKs5qvPAR6WNucOIj-G4yIXRqUawsjMOS9ZVgNiEZ0Xto80_NYI4ad7yIyXDWLn9_aQf70g9uAdcTBKjm6-pYxNAgS6tKcI-1DHKeub6tUqToFQWjm3hss2QwzUIQYO0Vp3XCVNF7elA5y5rKyu80ia-u_fvIoM2J3mZtV5YqOEh2Tv7QQnFLDIyySTt_BR2MJIx31_bw4uXUeCBUBEbUbgE-exjD2a-xW21oLF-G3svtOgdyMh4R70Abq9PfEmYWUnpLZv3DD0SbWZJjLv5zxPA0CyGIyCIkaM_DyZGZIDP1IqKe6kuaaRp2cS0UEZxOkqhUov0Siu8rmdNK_fUl73kzJowmE-opCLeaX7OkNob-b4OId3RwYzWleU4EUlCPA3QWsyXCed7XgGSnV5C-EBelJW2xL7tB7G82LFndh9KbDci6Dl5p3SgQGs6cfx-MPNLuOTEpo4tx_UVWeuf8fejSIi3IFni9rq4ZOht8Ipi3vOfnX0AzQrTEsN6oxnz3IzbxAd627bhkB-WuEOtpP24i0YDth6tAX-cRhugJyMeHQwXAJs.zHeOGg8GQ_MA1ANnxAo-pA'),
        'refresh_token' => env('ACCOUNTING_REFRESH_TOKEN', 'AB116396723906xQ9slfsmI7LMLeVxs90toYDwKNf8PpQrrDCU'),
        'real_me_id' => env('ACCOUNTING_REAL_ME_ID', '4620816365172318890'),
        'client_id' => env('ACCOUNTING_CLIENT_ID', 'ABRu5jyHJOc69mc4OiEwFmAELFBX48Dou05XRfKHI8O1oMekhX'),
        'client_secret' => env('ACCOUNTING_CLIENT_SECRET', 'PnC9t7vYfhElZNCxdoyfREeK6yQ9hERmF2usy5im'),
        'redirect_url' => env('ACCOUNTING_REDIRECT_URI', 'https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl'),
        'scope' => env('ACCOUNTING_OAUTH_SCOPE', 'com.intuit.quickbooks.accounting, com.intuit.quickbooks.payment, openID, profile, phone, address'),
        'token_endpoint_url' =>  env('ACCOUNTING_TOKEN_ENDPOINT_URL', 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer'),
        'base_url' =>  env('ACCOUNTING_BASE_URL', 'development'),
    ],
];
