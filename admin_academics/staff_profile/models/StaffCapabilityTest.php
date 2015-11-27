<?php
require_once(__DIR__ . '/StaffCapability.php');

function testGetAllCapabilities()
{
  print_r(StaffCapability::getAllCapabilities());
}

testGetAllCapabilities();
