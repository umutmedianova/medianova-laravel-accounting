<?php

namespace Medianova\LaravelAccounting\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

use Medianova\LaravelAccounting\Interfaces\AccountingInterface;
use Medianova\LaravelAccounting\Exceptions\LaravelAccountingException;

class LogoProvider implements AccountingInterface
{

    public $access_token;
    public $company_id;
    public $username;
    public $password;
    public $base_url;

    protected $dataService;
    protected $customerId;
    protected $customerData;
    protected $invoiceId;
    protected $invoiceData;
    protected $type;
    protected $response;

    protected $http_client;

    /**
     * LogoProvider constructor.
     * @throws LaravelAccountingException
     */
    public function __construct()
    {

        // Variables
        $this->base_url = config('accounting.logo.base_url');
        $this->username = config('accounting.logo.username');
        $this->password = config('accounting.logo.password');
        $this->company_id = config('accounting.logo.company_id');

        // Client
        $this->http_client = new Client([
            'base_uri' => $this->base_url,
        ]);

        // Update Token
        $access_token = Cache::get('accounting-logo-api-token', null);
        if (empty($access_token)) {
            $this->access_token = $this->login();
        } else {
            $this->access_token = $access_token;
        }

    }

    /**
     * @return mixed
     */
    public function login()
    {
        return Cache::remember('accounting-logo-api-token', 3500, function () {
            $this->response = $this->http_client->request('POST', $this->base_url . '/' . 'token', [
                'form_params' => [
                    'username' => $this->username,
                    'password' => $this->password,
                ]
            ]);
            return json_decode($this->response->getBody());
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
            /**
             *
             *
             *  LOGO CARI KAYIT
             *
             *
             */
            $error = false;
            if ($error) {
                $response = json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);
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
            /**
             *
             *
             *  LOGO FATURA KAYIT
             *
             *
             */
            $error = false;
            if ($error) {
                $response = json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);
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
                    return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);;
            }
        }
    }

    /**
     * Update Customer
     *
     * @return false|string
     */
    public function updateCustomer()
    {

        if (!empty($this->customerData)) {

            /**
             *
             * CARI GÜNCELLE
             *
             *
             */
        }

        return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);

    }


    /**
     * Update Invoice
     *
     * @return false|string
     */
    public function updateInvoice()
    {

        if (!empty($this->invoiceData)) {

            /**
             *
             * FATURA GÜNCELLE
             *
             *
             */
        }
        return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);

    }

}
