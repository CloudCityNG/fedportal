<?php

require(__DIR__ . '/AcademicSession.php');

function testGetAlternativeCurrentSession()
{
  print_r(AcademicSession::getAlternativeCurrentSession());
}

testGetAlternativeCurrentSession();
