<?php
require_once(__DIR__ . '/../login/auth.php');

require_once(__DIR__ . '/../../helpers/databases.php');

Class AdminAcademicsHome
{
  public function get()
  {
    $today = date('l, F j, Y', time());

    include(__DIR__ . '/home_view.php');
  }

  public function post()
  {
  }
}

$home = new AdminAcademicsHome();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $home->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $home->post();
}
