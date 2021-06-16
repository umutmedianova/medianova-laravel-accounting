<?php

namespace Medianova\LaravelAccounting\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use QuickBooksOnline\API\Core\CoreConstants;
use QuickBooksOnline\API\DataService\DataService;

use Medianova\LaravelAccounting\Interfaces\AccountingInterface;
use Medianova\LaravelAccounting\Exceptions\LaravelAccountingProviderException;

class QuickbooksProvider implements AccountingInterface
{

    public $auth_mode;
    public $client_id;
    public $client_secret;
    public $username;
    public $password;
    public $redirect_url;
    public $scope;
    public $base_url;

    protected $http_client;
    protected $access_token;
    protected $response;

    /**
     * QuickbooksProvider constructor.
     */
    public function __construct()
    {
        $this->auth_mode = config('accounting.quickbooks.auth_mode');
        $this->client_id = config('accounting.quickbooks.client_id');
        $this->client_secret = config('accounting.quickbooks.client_secret');
        $this->username = config('accounting.quickbooks.username');
        $this->password = config('accounting.quickbooks.password');
        $this->redirect_url = config('accounting.quickbooks.redirect_url');
        $this->scope = config('accounting.quickbooks.scope');
        $this->base_url = config('accounting.quickbooks.base_url');


        /*
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->client_id,
            'ClientSecret' => $this->client_secret,
            'RedirectURI' => $this->redirect_url,
            'scope' => $this->scope,
            'baseUrl' => "development"
        ));

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        fwrite(STDERR, print_r($authorizationCodeUrl, TRUE));
        */



        $this->http_client = new Client([
            'base_uri' => $this->base_url,
        ]);

        $access_token = Cache::get('accounting-api-token', null);
       if(empty($access_token)) {
            $this->access_token = $this->login();
        } else {
            $this->access_token = $access_token;
        }

    }


    public function login()
    {
        return Cache::remember('accounting-api-token', 6500, function ()  {

            $encodedClientIDClientSecrets = base64_encode( $this->client_id . ':' . $this->client_secret);
            $authorizationHeader = CoreConstants::OAUTH2_AUTHORIZATION_TYPE . $encodedClientIDClientSecrets;

            $this->response = $this->http_client->request('POST', $this->base_url . '/' . 'oauth2/v1/tokens/bearer', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                    'Authorization' => $authorizationHeader,
                ],
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'username' => $this->username,
                    'password' => $this->password,
                    'redirect_uri' => $this->redirect_url
                ]
            ]);
            return json_decode($this->response->getBody());
        });
    }

    /**
     * @param $data
     * @param string $type
     * @return false
     */
    public function create($data, $type = 'customer')
    {

       // fwrite(STDERR, print_r($this->response, TRUE));

        return false;
    }

}
