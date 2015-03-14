<?php
/**
 * Created by maneptha on 27-Feb-15.
 */

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Carbon\Carbon;

class AcademicSession
{
  private static $LOG_NAME = 'academic-session';

  public static function get_current_session()
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $today = date('Y-m-d', time());

    $query = "SELECT *
              FROM session_table
              WHERE :today1 >= start_date
              AND :today2 <= end_date
              ORDER BY start_date LIMIT 1";

    $query_param = [
      'today1' => $today,
      'today2' => $today
    ];

    $log->addInfo("About to get current session with query: {$query} and params: ", $query_param);

    $stmt = $db->prepare($query);

    $stmt->execute($query_param);

    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($session) {
      $log->addInfo("Query successfully ran. Session is: ", $session);

      $session['start_date'] = self::transform_date($session['start_date']);
      $session['end_date'] = self::transform_date($session['end_date']);

      return $session;

    } else {
      $log->addWarning("Current session not found!");
    }


    return [];
  }

  public static function create_session($data)
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $now = Carbon::now();

    $query = "INSERT INTO session_table (session, start_date, end_date, created_at, updated_at)
              VALUES (:session, :start_date, :end_date, '$now', '$now')";

    $data['start_date'] = self::transform_date($data['start_date']);
    $data['end_date'] = self::transform_date($data['end_date']);

    $log->addInfo("About to create new session using query: {$query} and param: ", $data);

    $stmt = $db->prepare($query);
    $stmt->execute($data);

    if ($stmt->rowCount()) {
      $log->addInfo("Session successfully created!");

      $data['start_date'] = self::transform_date($data['start_date']);
      $data['end_date'] = self::transform_date($data['end_date']);
      $data['created_at'] = $now->toDateTimeString();

      return $data;
    }

    return [];
  }

  public static function session_exists($session)
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $query = "SELECT COUNT(*) FROM session_table WHERE session = ?";

    $query_param = [$session];

    $log->addInfo("About to check if session exists with query: {$query} and param: ", $query_param);

    $stmt = $db->prepare($query);

    $stmt->execute($query_param);

    if ($stmt) {
      $log->addInfo("query successfully ran");
      return $stmt->fetchColumn() ? true : false;
    }

    $log->addWarning("Query did not run successfully");

    return null;
  }

  public static function dates_unique($dates)
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $query = "SELECT COUNT(*) FROM session_table
              WHERE start_date = :start_date
              AND end_date = :end_date";

    $log->addInfo(
      "About to check if session start and end dates exist
       with query: {$query} and params: ",
      $dates
    );

    $stmt = $db->prepare($query);

    $stmt->execute();

    return $stmt->fetchColumn() ? false : true;
  }

  public static function get_two_most_recent_sessions()
  {
    $log = get_logger(self::$LOG_NAME);

    $db = get_db();

    $query = "SELECT * FROM session_table ORDER BY session DESC LIMIT 2";

    $log->addInfo("About to get 2 most recent sessions with query {$query}.");

    $stmt = $db->query($query);

    if ($stmt && $stmt->rowCount()) {
      $log->addInfo("SQL statement ran successfully.");
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return [];
  }

  public static function update_session($data)
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $query = "UPDATE session_table
              SET start_date = :start_date,
              end_date = :end_date,
              session = :session
              WHERE id = :id";

    $data['start_date'] = self::transform_date($data['start_date']);
    $data['end_date'] = self::transform_date($data['end_date']);

    $log->addInfo(
      "About to update academic session with query: {$query} and param: ", $data
    );

    $stmt = $db->prepare($query);
    $stmt->execute($data);

    if ($stmt->rowCount()) {
      $log->addInfo("Session successfully updated!");

      return $data;
    }

    return null;
  }

  private static function transform_date($val)
  {
    return implode('-', array_reverse(explode('-', $val)));
  }
}
