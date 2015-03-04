<?php

include_once(__DIR__ . '/helpers/controllers/ConfirmPinController.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $confirm_pin = new ConfirmPinController($_POST);
}
