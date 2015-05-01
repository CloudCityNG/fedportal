<?php

include_once(__DIR__ . '/StudentBilling.php');

function test_get_owing_with_existing_reg_no()
{

  $bill = new StudentBilling();

  $owing = $bill->get_owing('abcde');

  echo $owing;

}

function test_get_owing_with_non_existing_reg_no()
{

  $bill = new StudentBilling();

  $owing = $bill->get_owing('abcde1');

  echo $owing;

}

function test_get_debtors()
{
  $bill = new StudentBilling();

  foreach ($bill->get_debtors() as $student) {
    print_r($student);

    echo "\n\n";
  }

}

//test_get_owing_with_existing_reg_no();

//test_get_owing_with_non_existing_reg_no();

test_get_debtors();
