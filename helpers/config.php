<?php

$link = @mysql_connect('localhost', "fedsdimy_dental", "h4Ic9tK43!b^");

if (!$link) {
  die('Failed to connect to server: ' . @mysql_error());
}

$db = @mysql_select_db('fedsdimy_fedstudents');

if (!$db) {
  die("Unable to select database");
}
