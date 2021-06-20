<?php
namespace Medianova\LaravelAccounting\Interfaces;

interface AccountingInterface
{
    public function customer($data);
    public function invoice($data);
    public function create();
    public function update();
}
