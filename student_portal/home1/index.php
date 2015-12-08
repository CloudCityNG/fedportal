<?php
require_once(__DIR__ . '/../login/auth.php');
require_once(__DIR__ . '/../../helpers/databases.php');

Class StudentDashboardHome
{
  public function get()
  {
    $currentPage = null;
    require_once(__DIR__ . '/container.php');
  }

  public function post()
  {
  }
}

$home = new StudentDashboardHome();
if ($_SERVER['REQUEST_METHOD'] === 'GET') $home->get();
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') $home->post();
