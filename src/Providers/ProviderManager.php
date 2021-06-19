<?php
namespace Medianova\LaravelAccounting\Providers;

use Illuminate\Support\Str;
use Medianova\LaravelAccounting\Interfaces\AccountingInterface;
use Medianova\LaravelAccounting\Exceptions\LaravelAccountingException;
use Medianova\LaravelAccounting\Exceptions\LaravelAccountingProviderException;

class ProviderManager
{
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
        include_once($class_name.".php");
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
                throw new LaravelAccountingException("We could not found Provider  : {$file}");
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
     * Create function
     *
     * @param $data
     * @param string $type
     * @return mixed
     * @throws LaravelAccountingException
     */
    public function create($data, string $type = 'customer')
    {

        if ($type == null) {
            return false;
        }else {

            try {
                return $this->provider->create($data, $type);
            } catch (LaravelAccountingProviderException $e) {
                throw new LaravelAccountingException("Create Error!");
            }
        }
    }

}
