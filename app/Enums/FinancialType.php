<?php

namespace App\Enums;

use ReflectionClass;

class FinancialType
{
  const RENT = 'Rent';
  const SALARY = 'Salary';
  const COMMISSIONS = 'Commissions';

  const PAYMENT_FROM_CLIENT = 'Payment from Client';
  const PAID_FOR_CLIENT = 'Paid for Client';
  const PAYMENT_TO_SUPPLIER = 'Payment to Supplier';
  const PAYMENT_FOR_SHIPPING = 'Payment to Shipping';
  const OTHER = 'Other';


  public static function getConstants()
  {
    $reflectionClass = new ReflectionClass(__CLASS__);
    return $reflectionClass->getConstants();
  }
}
