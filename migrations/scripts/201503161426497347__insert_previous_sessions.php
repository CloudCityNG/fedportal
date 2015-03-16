<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use Carbon\Carbon;

Class A201503161426497347
{
  public function up(PDO $db = null)
  {
    $session = '';
    $start_date = '';
    $end_date = '';
    $created_at = '';
    $updated_at = '';

    $query = "INSERT INTO session_table(session, start_date, end_date, created_at, updated_at)
              VALUES (:session, :start_date, :end_date, :created_at, :updated_at)";

    $stmt = $db->prepare($query);

    $stmt->bindParam('session', $session);
    $stmt->bindParam('start_date', $start_date);
    $stmt->bindParam('end_date', $end_date);
    $stmt->bindParam('created_at', $created_at);
    $stmt->bindParam('updated_at', $updated_at);

    $start_time = Carbon::createFromDate(Carbon::now()->year - 20, 12, 5);

    for ($year = 1; $year < 21; $year++) {
      $end_time = $start_time->copy()->addYear()->subDays(6);

      $start_date = $start_time->format('Y-m-d');
      $end_date = $end_time->format('Y-m-d');
      $session = $start_time->format('Y') . '/' . $end_time->format('Y');
      $created_at = $start_time->toDateTimeString();
      $updated_at = $created_at;

      $stmt->execute();

      $start_time = $start_time->addYear();
    }
  }

  public function down(PDO $db)
  {
  }
}
