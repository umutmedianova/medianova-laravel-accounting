<?php

namespace Medianova\LaravelAccounting\Providers;

use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Expr\Throw_;

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;

use Medianova\LaravelAccounting\Interfaces\AccountingInterface;
use Medianova\LaravelAccounting\Exceptions\LaravelAccountingException;

class QuickbooksProvider implements AccountingInterface
{

    public $auth_mode;
    public $client_id;
    public $client_secret;
    public $access_token;
    public $refresh_token;
    public $real_me_id;
    public $username;
    public $password;
    public $redirect_url;
    public $scope;
    public $base_url;

    protected $dataService;
    protected $response;

    /**
     * QuickbooksProvider constructor.
     * @throws LaravelAccountingException
     */
    public function __construct()
    {

        // Variables
        $this->auth_mode = config('accounting.quickbooks.auth_mode');
        $this->client_id = config('accounting.quickbooks.client_id');
        $this->client_secret = config('accounting.quickbooks.client_secret');
        $this->access_token = config('accounting.quickbooks.access_token');
        $this->refresh_token = config('accounting.quickbooks.refresh_token');
        $this->real_me_id = config('accounting.quickbooks.real_me_id');
        $this->username = config('accounting.quickbooks.username');
        $this->password = config('accounting.quickbooks.password');
        $this->redirect_url = config('accounting.quickbooks.redirect_url');
        $this->scope = config('accounting.quickbooks.scope');
        $this->base_url = config('accounting.quickbooks.base_url');

        // Service created
        $this->dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->client_id,
            'ClientSecret' => $this->client_secret,
            'accessTokenKey' => $this->access_token,
            'refreshTokenKey' => $this->refresh_token,
            'QBORealmID' => $this->real_me_id,
            'RedirectURI' => $this->redirect_url,
            'scope' => $this->scope,
            'baseUrl' => "development"
        ));

        // Update Token
        $access_token = Cache::get('accounting-api-token', null);
        if (empty($access_token)) {
            $this->access_token = $this->login();
        } else {
            $this->access_token = $access_token;
        }
        if ($this->access_token != null) {
            $this->dataService->updateOAuth2Token($this->access_token);
        } else {
            throw new LaravelAccountingException("Access token must not null");
        }

    }


    /**
     * @return mixed
     */
    public function login()
    {
        return Cache::remember('accounting-api-token', 3500, function () {
            $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
            return $OAuth2LoginHelper->refreshToken();
        });
    }

    /**
     * @param $data
     * @param string $type
     * @return Mixed
     */
    public function create($data, $type = 'customer')
    {
        switch ($type) {
            case 'customer':
                return $this->createCustomer($data);
            case 'invoice':
                return $this->createInvoice($data);
            default:
                return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);;
        }
    }

    /**
     * Create Customer
     *
     * @param array $data
     * @return Mixed
     */
    public function createCustomer(array $data = [])
    {

        if (!empty($data)) {
            $customerObj = Customer::create($data);
            $this->dataService->Add($customerObj);
            $error = $this->dataService->getLastError();
            if ($error) {
                $response = json_encode([
                    'code' => $error->getHttpStatusCode(),
                    'message' => $error->getOAuthHelperError(),
                    'body' => $error->getResponseBody(),
                ]);
            } else {
                $response = json_encode([
                    'code' => 200,
                    'message' => 'OK',
                    'body' => null,
                ]);
            }
            return $response;
        }

        return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);

    }


    /**
     * Create Invoice
     *
     * @param array $data
     * @return Mixed
     */
    public function createInvoice(array $data = [])
    {

        if (!empty($data)) {
            $customerObj = Invoice::create($data);
            $this->dataService->Add($customerObj);
            $error = $this->dataService->getLastError();
            if ($error) {
                $response = json_encode([
                    'code' => $error->getHttpStatusCode(),
                    'message' => $error->getOAuthHelperError(),
                    'body' => $error->getResponseBody(),
                ]);
            } else {
                $response = json_encode([
                    'code' => 200,
                    'message' => 'OK',
                    'body' => null,
                ]);
            }
            return $response;
        }

        return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);


    }

}
