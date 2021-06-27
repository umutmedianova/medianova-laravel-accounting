<?php

namespace Medianova\LaravelAccounting\Providers;

use Illuminate\Support\Str;
use Medianova\LaravelAccounting\Interfaces\AccountingInterface;
use Medianova\LaravelAccounting\Exceptions\LaravelAccountingException;
use Medianova\LaravelAccounting\Exceptions\LaravelAccountingProviderException;

class ProviderManager
{

    /**
     * @var
     */
    protected $id;

    /**
     * @var
     */
    protected $type;

    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    protected $provider;

    /**
     * ProviderManager constructor.
     * @throws LaravelAccountingException
     */
    public function __construct()
    {
        $this->provider(config('accounting.provider'));
    }

    /**
     * @param $class_name
     */
    public function __autoload($class_name)
    {
        include_once($class_name . ".php");
    }

    /**
     * Load Accounting Provider
     *
     * @param String $provider
     * @return Mixed
     */
    public function provider($provider)
    {
        if ($provider == null) {
            return false;
        } else {

            $class_name = ucfirst($provider) . 'Provider';

            $file = dirname(__FILE__) . '/' . $class_name . ".php";
            if (!file_exists($file)) {
                throw new LaravelAccountingProviderException("We could not found Provider  : {$file}");
            }
            $provider = resolve("Medianova\\LaravelAccounting\\Providers\\" . $class_name);
            $this->provider = $provider;

            if (!$this->provider instanceof AccountingInterface) {
                throw new LaravelAccountingProviderException("Provider must implement on LaravelAccountingInterface");
            }
            return $this;
        }
    }

    /**
     * Customer function
     *
     * @param $data
     * @param $id
     * @return mixed
     * @throws LaravelAccountingException
     */
    public function customer($data = null, $id = null)
    {

        if ($data == null) {
            return false;
        } else {

            try {
                return $this->provider->customer($data, $id);
            } catch (LaravelAccountingProviderException $e) {
                throw new LaravelAccountingProviderException("Customer Error!");
            }
        }
    }

    /**
     * Invoice function
     *
     * @param $data
     * @param $id
     * @return mixed
     * @throws LaravelAccountingException
     */
    public function invoice($data = null, $id = null)
    {

        if ($data == null) {
            return false;
        } else {

            try {
                $this->data = $data;
                return $this->provider->invoice($data, $id);
            } catch (LaravelAccountingProviderException $e) {
                throw new LaravelAccountingProviderException("Invoice Error!");
            }
        }
    }

    /**
     * Transaction function
     *
     * @param $data
     * @param $id
     * @return mixed
     * @throws LaravelAccountingException
     */
    public function transactions($data = null)
    {

        if ($data == null) {
            return false;
        } else {

            try {
                $this->data = $data;
                return $this->provider->transactions($data);
            } catch (LaravelAccountingProviderException $e) {
                throw new LaravelAccountingProviderException("Transactions Error!");
            }
        }
    }

}
