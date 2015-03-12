<?php

require_once(__DIR__ . '/login/auth.php');

require_once(__DIR__ . '/../helpers/app_settings.php');

$home = STATIC_ROOT . 'student_portal/home/';

header("Location: $home");
