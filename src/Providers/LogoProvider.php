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
    protected $searchData;
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
        $this->http_client = new Client(['base_uri' => $this->base_url]);

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
                    'grant_type' => 'password',
                    'username' => $this->username,
                    'password' => $this->password,
                ]
            ]);

            $body = (array)json_decode($this->response->getBody());
            return $body['access_token'];
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
        $this->invoiceData = $this->invoiceDataTransform($data);
        $this->type = 'invoice';
        return $this;
    }

    /**
     * @param array $data
     * @return array
     */
    public function invoiceDataTransform($data = []){

        if(is_array($data) && count($data) > 0) {
            $TData = [
                'FirmNr' => $data['FirmNumber'] ?? 999,
                'Numara' => $data['InvoiceNumber'] ?? "~",
                'FaturaTuru' => $data['InvoiceType'] ?? 9,
                'Isyeri' => $data['Workplace'] ?? 5,
                'Ambar' => $data['Warehouse'] ?? 6,
                'Fatura_Carisi' => []
            ];

            // Info
            $TData['Tarih'] = $data['InvoiceDate'] ?? null;
            $TData['Saat'] = $data['InvoiceTime'] ?? null;
            $TData['DovizTuru'] = $data['Currency'] ?? null;
            $TData['IslemDovizKuru'] = $data['CurrencyValue'] ?? null;
            $TData['FaturaOzelKod'] = $data['InvoiceCode'] ?? null;
            $TData['GenelAciklama'] = $data['GeneralDescription'] ?? null;
            $TData['Not2'] = $data['GeneralDescriptionAdd'] ?? null;
            $TData['VadeGunu'] = $data['PaymentDue'] ?? null;
            $TData['SatisElemani'] = $data['SalesCode'] ?? null;
            $TData['Fatura_Carisi']['Kod'] = $data['AccountCode'] ?? null;
            $TData['Fatura_Carisi']['Unvan'] = $data['CompanyName'] ?? null;
            $TData['Fatura_Carisi']['Adres'] = $data['StreetAddress'] ?? null;
            $TData['Fatura_Carisi']['Il'] = $data['City'] ?? null;
            $TData['Fatura_Carisi']['Ulke'] = $data['CountryId'] ?? null;
            $TData['Fatura_Carisi']['Telefon1'] = $data['PhoneNumber'] ?? null;
            $TData['Fatura_Carisi']['Email'] = $data['Email'] ?? null;
            $TData['Fatura_Carisi']['VergiDairesi'] = $data['TaxOffice'] ?? null;
            $TData['Fatura_Carisi']['TCKimlik_Vergino'] = $data['TaxNumber'] ?? null;

            // Lines
            if (isset($data['Lines']) && count($data['Lines']) > 0) {
                $TData['Satirlar'] = [];
                foreach ($data['Lines'] as $line) {
                    $product = $line['product'] ?? [];
                    array_push($TData['Satirlar'],
                        [
                            'SatirTuru' => $line['RowType'] ?? 1,
                            'Miktar' => $line['quantity'] ?? 1,
                            'Birim' => 'Adet',
                            'Fiyat' => $line['Amount'] ?? null,
                            'KDV' => $line['VatAmount'] ?? null,
                            'SatirAciklamasi' => $line['RowDescription'] ?? null,
                            'UrunBilgisi' => [
                                'FirmNr' => $data['FirmNumber'] ?? 999,
                                'Kod' => $product['code'] ?? null,
                                'UrunAdi' => $product['name'] ?? null,
                                'Birim' => 'Adet',
                            ]
                        ]
                    );
                }
            }
        } else{
            $TData = [];
        }

        return $TData;

    }

    /**
     * @param array $data
     * @param $id
     * @return Mixed
     */
    public function transactions($data = [])
    {
        $this->searchData = $data;
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

            $this->response = $this->http_client->request('POST', $this->base_url . '/' . 'api/CariKayitEkle', [
                'body' => json_encode($this->customerData),
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
            $body = (array)json_decode($this->response->getBody());
            return $this->response($body);
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

            $this->response = $this->http_client->request('POST', $this->base_url . '/' . 'api/SatisFaturasiKayit', [
                'form_params' => $this->invoiceData,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                ]
            ]);

            $body = (array)json_decode($this->response->getBody());

            return $this->response($body);
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

            $this->response = $this->http_client->request('PUT', $this->base_url . '/' . 'api/CariKayitGuncelle', [
                'body' => json_encode($this->customerData),
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
            $body = (array)json_decode($this->response->getBody());
            return $this->response($body);
        }

        return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);

    }


    /**
     * @param $body
     * @return false|string
     */
    public function response($body)
    {
        if (array_key_exists('sonuc', $body)) {
            $error = $body['sonuc'];
        } else if (array_key_exists('Sonuc', $body)) {
            $error = $body['Sonuc'];
        } else {
            $error = false;
        }

        if (!$error) {
            $response = json_encode(['code' => 401, 'message' => 'Error', 'body' => $body]);
        } else {
            $response = json_encode([
                'code' => 200,
                'message' => 'OK',
                'body' => $body,
            ]);
        }

        return $response;
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
     * @return array|false|string
     */
    public function getTransactions()
    {
        if (!empty($this->searchData)) {

            $this->response = $this->http_client->request('GET', $this->base_url . '/' . 'api/CariHesapEkstresi', [
                'body' => json_encode($this->searchData),
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
            $body = (array)json_decode($this->response->getBody());
            return json_encode(['code' => 200, 'message' => 'OK', 'body' => $body]);

        } else {
            return json_encode(['code' => 401, 'message' => 'Error', 'body' => null]);
        }
    }

}
