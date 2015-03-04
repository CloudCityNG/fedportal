<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 12-Feb-15
 * Time: 10:28 AM
 */

include_once(__DIR__ . '/StudentPayment.php');

function reset_db($table, $row_id)
{
  include_once(__DIR__ . '/../databases.php');

  $db = get_db();

  $db->query("delete from $table where id = '$row_id'");
}

function test_save_payment()
{
  $pay_details = [

    'reg_no' => '123456',

    'academic_year' => '2014/2015',

    'level' => 'OND1',

    'dept_code' => 'random_dept_code',

    'amount' => 55555,

    'remark' => 'fee paid',

    'receipt_no' => '25323223'
  ];

  $sp = new StudentPayment();

  $inserted_details = $sp->save_payment($pay_details);

  echo print_r($inserted_details, true);

  reset_db('student_payment', $inserted_details['id']);
}

//test_save_payment();

function test_get_billing_history()
{
  $bills = new StudentPayment();

  print_r($bills->get_billing_history('abcde'));
}

//test_get_billing_history();
