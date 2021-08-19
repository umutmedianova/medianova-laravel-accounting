<?php

return [
    'provider'=>env('ACCOUNTING_PROVIDER', 'logo'),
    'logo'=>[
        'base_url' =>  env('ACCOUNTING_LOGO_BASE_URL', 'http://89.145.186.214:8090'),
        'username' => env('ACCOUNTING_LOGO_USERNAME', 'medianova'),
        'password' => env('ACCOUNTING_LOGO_PASSWORD', 'MediANOva2021*!'),
        'company_id' => env('ACCOUNTING_LOGO_COMPANY_ID', 999),
    ],
    'quickbooks'=>[
        'access_token' => env('ACCOUNTING_ACCESS_TOKEN', 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..mKBMa-igPxLZLf3KNTAwKA.LkLZls53i970rfIFgM9sZkbX54oIq3umceHQchXKvSThcmAvH45Ulk-bzZoELPFypkM6o0kUxBsgG81_D9XaF7NeOAEMbjw8bYLSzo7lnPp4ry3dcYzIKXPVbr0eE-nH3tldB32aN65XIJ_TC3GV-cRNff5OgAwLiLQtQXrahg_mTX1ob0zHWp9YMv7MyxeSqmfzxP3zGp0Y9nIHwhPGFlEYuTGdJWN1IHQsu-NukD4Sz7kCSwRvyLuhIgpggOxBdoZSsxTm75RfkfMCRsbJ9JtfDngx_xFqrpK4ciFV05MpwRMqVjkf8KgwNmGsJKh9Sys-O0gHHWQ1PpLTEUiD500lNp9Hfryq4zvyRklR29B4DqSiLJ-iPzFWcNGnpgNqHyVbaeIS6rfQiq2VuBJanAOE6ULRDoyAVmVNYqTGrB7nH3mbgHCPHlC7TR8wlHxDL7fdxyCVBuBXacz0l8SNsyQfl3ifU4dhVIVuPI5OrWitFfmwhqWp5KuYMqBrVX3q_s7EzQ8FTvFMN-z_8LEDunzboXyHQoiP0BB1u0StPI4MXVDfrTHaSOm3Th7UmjzGDmyTxHBvW_K8cGMH0isPhUtXJ24IgwEOts461VqJv0fQyyvC4A7xfHSEc8FkgueJB9RJ1dmSKzQ1NUPzkzj2yGaTIVHpSu85rBodud9g4QRaymVoIn-vwZr866zi0-7XYyXN8acNd5ncCX-GGYM6AAb9RkXhUah6P_tP8UCLy1lQOfH8-u0ZsuCU_GcwAMm1aMS1u1zscbk34chnnCPWMnPEbDQblGeJB_qEYJQwQjzsX4Oq_dLT-XAoTu4snSASwR2tjNV-YTUVinbesr4qnUVNvSQNDtdzmKTu7zm8BKz4jozBH1CxXFiNfpsK5rmf.7sFr4Uq3S02avVHaKyZpkw'),
        'refresh_token' => env('ACCOUNTING_REFRESH_TOKEN', 'AB11632994147rEu0u0WpjHwn3Now2o3wQ8dL7YgSnNr7ocgaG'),
        'real_me_id' => env('ACCOUNTING_REAL_ME_ID', '4620816365172318890'),
        'client_id' => env('ACCOUNTING_CLIENT_ID', 'ABRu5jyHJOc69mc4OiEwFmAELFBX48Dou05XRfKHI8O1oMekhX'),
        'client_secret' => env('ACCOUNTING_CLIENT_SECRET', 'PnC9t7vYfhElZNCxdoyfREeK6yQ9hERmF2usy5im'),
        'redirect_url' => env('ACCOUNTING_REDIRECT_URI', 'https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl'),
        'scope' => env('ACCOUNTING_OAUTH_SCOPE', 'com.intuit.quickbooks.accounting, com.intuit.quickbooks.payment, openID, profile, phone, address'),
        'token_endpoint_url' =>  env('ACCOUNTING_TOKEN_ENDPOINT_URL', 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer'),
        'base_url' =>  env('ACCOUNTING_BASE_URL', 'development'),
    ],
];
