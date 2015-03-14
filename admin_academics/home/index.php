<?php
require_once(__DIR__ . '/../login/auth.php');

Class AdminAcademicsHome
{
  public function get()
  {
    $currentPage = null;
    include(__DIR__ . '/container.php');
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
