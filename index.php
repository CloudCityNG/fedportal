<?php
require_once(__DIR__ . '/helpers/app_settings.php');

$student_portal_home = STATIC_ROOT . 'student_portal/';

header("Location: {$student_portal_home}");