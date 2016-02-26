<?php
require_once(__DIR__ . '/../helpers/app_settings.php');
require_once(__DIR__ . '/login/auth.php');

header('Location: ' . STATIC_ROOT . 'student_portal/home/');
