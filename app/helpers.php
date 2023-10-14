<?php

use Haruncpi\LaravelIdGenerator\IdGenerator;

if (! function_exists('getUserType')) {
    function getUserType($user)
    {
        if (!empty($user)) {
            if ($user->customer()->count()) {
                return 'customer';
            } elseif ($user->staff()->count()) {
                return 'staff';
            } elseif ($user->supplier()) {
                return 'supplier';
            } else {
                return 'customer';
            }
        }
    }
}

if (! function_exists('getNextProductCode')) {
    function getNextProductCode(){
        return IdGenerator::generate([
            'table' => 'products',
            'length' => 11,
            'prefix' => 'PRD-',
            'field' => 'code'
        ]);
    }
}

if (! function_exists('getNextInvoiceCode')) {
    function getNextInvoiceCode(){
        return IdGenerator::generate([
            'table' => 'purchases',
            'length' => 11,
            'prefix' => 'INV-',
            'field' => 'invoice_number'
        ]);
    }
}