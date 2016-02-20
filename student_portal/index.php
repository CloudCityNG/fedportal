<?php
require_once(__DIR__ . '/../helpers/app_settings.php');
require_once(__DIR__ . '/login/auth.php');

$home = STATIC_ROOT . 'student_portal/home1/';
header("Location: $home");
