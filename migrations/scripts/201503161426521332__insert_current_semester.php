<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use Carbon\Carbon;

Class A201503161426521332
{
  public function up(PDO $db)
  {
    $current_session = $db->query(
      "SELECT id, start_date FROM session_table
       WHERE session = (SELECT MAX(session) FROM session_table)"
    );

    $current_session1 = $current_session->fetch(PDO::FETCH_ASSOC);
    $current_session->closeCursor();

    $session_id = $current_session1['id'];
    $start_date = $current_session1['start_date'];

    $created_at = Carbon::createFromFormat('Y-m-d', $start_date);

    $end_date = $created_at->copy()->addDays(120)->format('Y-m-d');

    $stmt = $db->prepare(
      "INSERT INTO semester(number, start_date, end_date, created_at, updated_at, session_id)
       VALUES (:number, :start_date, :end_date, :created_at, :updated_at, :session_id)"
    );

    $stmt->execute([
      'number' => 1,
      'start_date' => $start_date,
      'end_date' => $end_date,
      'created_at' => $created_at->toDateTimeString(),
      'updated_at' => $created_at->toDateTimeString(),
      'session_id' => $session_id,
    ]);

    $stmt->closeCursor();
  }

  public function down(PDO $db)
  {
  }
}
