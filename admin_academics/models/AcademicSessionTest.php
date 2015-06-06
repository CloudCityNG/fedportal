<?php

require(__DIR__ . '/AcademicSession.php');

function testGetAlternativeCurrentSession()
{
  print_r(AcademicSession::getAlternativeCurrentSession());
}

//testGetAlternativeCurrentSession();

function testGetSessionsFromIds(){
  print_r(AcademicSession::getSessionsFromIds([21]));
}

//testGetSessionsFromIds();
