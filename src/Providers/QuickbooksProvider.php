<?php

namespace Medianova\LaravelAccounting\Providers;

use Illuminate\Support\Facades\Cache;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;

use Medianova\LaravelAccounting\Interfaces\AccountingInterface;
use Medianova\LaravelAccounting\Exceptions\LaravelAccountingException;
use QuickBooksOnline\API\ReportService\ReportName;
use QuickBooksOnline\API\ReportService\ReportService;

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
    protected $customerId;
    protected $customerData;
    protected $invoiceId;
    protected $invoiceData;
    protected $type;
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

        // Options
        $options = array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->client_id,
            'ClientSecret' => $this->client_secret,
            'accessTokenKey' => $this->access_token,
            'refreshTokenKey' => $this->refresh_token,
            'QBORealmID' => $this->real_me_id,
            'RedirectURI' => $this->redirect_url,
            'scope' => $this->scope,
            'baseUrl' => "development"
        );

        // Update Token
        $access_token = Cache::get('accounting-api-token', null);
        if (!empty($access_token)) {
            $this->access_token = $access_token;
            $this->dataService = DataService::Configure($options);
        } else {
            $this->dataService = DataService::Configure($options);
            $this->access_token = $this->login();
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
     * @param array $data
     * @param $id
     * @return Mixed
     */
    public function customer($data = [], $id = null)
    {
        $this->customerId = $id;
        $this->customerData = $data;
        $this->type = 'customer';
        return $this;
    }

    /**
     * @param array $data
     * @param $id
     * @return Mixed
     */
    public function invoice($data = [], $id = null)
    {
        $this->invoiceId = $id;
        $this->invoiceData = $data;
        $this->type = 'invoice';
        return $this;
    }

    /**
     * @param $id
     * @return Mixed
     */
    public function transactions($id = null)
    {
        $this->customerId = $id;
        $this->type = 'transactions';
        return $this;
    }

    /**
     * Create
     *
     * @return Mixed
     */
    public function create()
    {
        switch ($this->type) {
            case 'customer':
                return $this->createCustomer();
            case 'invoice':
                return $this->createInvoice();
            default:
                return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);;
        }
    }

    /**
     * Create Customer
     *
     * @return Mixed
     */
    public function createCustomer()
    {

        if (!empty($this->customerData)) {
            $customerObj = Customer::create($this->customerData);
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
     * @return Mixed
     */
    public function createInvoice()
    {

        if (!empty($this->invoiceData)) {
            $invoiceObj = Invoice::create($this->invoiceData);
            $this->dataService->Add($invoiceObj);
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
     * Update
     *
     * @return Mixed
     * @throws \QuickBooksOnline\API\Exception\IdsException
     */
    public function update()
    {

        if ($this->customerId == null && $this->invoiceId == null) {
            throw new LaravelAccountingException("ID NOT FOUND ERROR!");
        } else {
            switch ($this->type) {
                case 'customer':
                    return $this->updateCustomer();
                case 'invoice':
                    return $this->updateInvoice();
                default:
                    return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);
            }
        }
    }

    /**
     * Update Customer
     *
     * @return false|string
     * @throws \QuickBooksOnline\API\Exception\IdsException
     */
    public function updateCustomer()
    {

        if (!empty($this->customerData)) {

            $entities = $this->dataService->Query("SELECT * FROM Customer where Id='" . $this->customerId . "'");
            if (empty($entities)) {
                return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);
            } else {

                $theCustomer = reset($entities);

                $this->customerData['sparse'] = true; // Sadece güncelleme isteğinde gönderilen alanlar güncellenir.
                $updateCustomer = Customer::update($theCustomer, $this->customerData);
                $this->dataService->Update($updateCustomer);

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
        }

        return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);

    }


    /**
     * Update Invoice
     *
     * @return false|string
     * @throws \QuickBooksOnline\API\Exception\IdsException
     */
    public function updateInvoice()
    {

        if (!empty($this->invoiceData)) {

            $invoice_entities = $this->dataService->Query("SELECT * FROM Invoice where Id='" . $this->invoiceId . "'");
            if (empty($invoice_entities)) {
                return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);
            } else {

                $theInvoice = reset($invoice_entities);

                $this->invoiceData['sparse'] = true; // Sadece güncelleme isteğinde gönderilen alanlar güncellenir.
                $updateInvoice = Customer::update($theInvoice, $this->invoiceData);
                $this->dataService->Update($updateInvoice);

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
        }

        return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);

    }


    /**
     * Get
     *
     * @return Mixed
     */
    public function get()
    {
        switch ($this->type) {
            case 'transactions':
                return $this->getTransactions();
            default:
                return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);;
        }
    }


    /**
     * Get Transactions
     *
     * @return array
     * @throws \QuickBooksOnline\API\Exception\SdkExceptions\InvalidParameterException
     */
    public function getTransactions()
    {
        if (!empty($this->customerId)) {

            $serviceContext = $this->dataService->getServiceContext();

            $reportService = new ReportService($serviceContext);
            if (!$reportService) {
                throw new LaravelAccountingException("Problem while initializing ReportService!");
            }

            $reportService->setStartDate("2015-01-01");
            $reportService->setAccountingMethod("Accrual");
            $customerBalanceReport = $reportService->executeReport(ReportName::CUSTOMERBALANCE);


        } else {
            return [];
        }
    }

}
