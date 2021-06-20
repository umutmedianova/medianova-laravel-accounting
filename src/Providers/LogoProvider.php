<?php

namespace Medianova\LaravelAccounting\Providers;

use Illuminate\Support\Facades\Cache;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;

use Medianova\LaravelAccounting\Interfaces\AccountingInterface;
use Medianova\LaravelAccounting\Exceptions\LaravelAccountingException;

class LogoProvider implements AccountingInterface
{

    public $access_token;
    public $refresh_token;
    public $real_me_id;
    public $username;
    public $password;
    public $redirect_url;
    public $base_url;

    protected $dataService;
    protected $customerId;
    protected $customerData;
    protected $invoiceId;
    protected $invoiceData;
    protected $type;
    protected $response;

    /**
     * LogoProvider constructor.
     * @throws LaravelAccountingException
     */
    public function __construct()
    {

        // Variables
        $this->access_token = config('accounting.quickbooks.access_token');
        $this->refresh_token = config('accounting.quickbooks.refresh_token');
        $this->real_me_id = config('accounting.quickbooks.real_me_id');
        $this->username = config('accounting.quickbooks.username');
        $this->password = config('accounting.quickbooks.password');
        $this->redirect_url = config('accounting.quickbooks.redirect_url');
        $this->base_url = config('accounting.quickbooks.base_url');

        /**
         *
         *
         * LOGO CONNECT !
         * $this->login();
         *
         *
         */

    }


    /**
     * @return mixed
     */
    public function login()
    {
        return Cache::remember('accounting-api-token', 3500, function () {

            return false;
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
                default:
                    return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);;
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

            /**
             *
             * CARI GÜNCELLE
             *
             *
             */
        }

        return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);

    }

}
