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

  public static function get_sessions($how_many = null)
  {
    $query = "SELECT * FROM session_table ORDER BY session DESC";

    if ($how_many) {
      $query .= " LIMIT {$how_many}";
    }

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo("About to get sessions with query: {$query}");

    $db = get_db();

    $stmt = $db->query($query);

    if ($stmt) {
      $sessions = [];

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sessions[] = self::db_dates_to_carbon($row);
      }

      $log->addInfo("Statement executed successfully. Session is: ", $sessions);

      return $sessions;
    }

    $log->addWarning("Statement did not execute.");

    return null;
  }

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

      return self::db_dates_to_carbon($session);

    } else {
      $log->addWarning("Current session not found!");
    }


    return null;
  }

  public static function create_session($data)
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $now = Carbon::now();

    $query = "INSERT INTO session_table (session, start_date, end_date, created_at, updated_at)
              VALUES (:session, :start_date, :end_date, '$now', '$now')";

    $data['start_date'] = Carbon::createFromFormat('d-m-Y', $data['start_date'])->format('Y-m-d');
    $data['end_date'] = Carbon::createFromFormat('d-m-Y', $data['end_date'])->format('Y-m-d');

    $log->addInfo("About to create new session using query: {$query} and param: ", $data);

    $stmt = $db->prepare($query);
    $stmt->execute($data);

    if ($stmt->rowCount()) {
      $log->addInfo("Session successfully created!");

      $data['start_date'] = Carbon::parse($data['start_date']);
      $data['end_date'] = Carbon::parse($data['end_date']);
      $data['created_at'] = $now;
      $data['updated_at'] = $now;

      return $data;
    }

    return null;
  }

  public static function session_exists_by_session($session)
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $query = "SELECT COUNT(*) FROM session_table WHERE session = ?";

    $query_param = [$session];

    $log->addInfo("About to check if session exists with query: {$query} and param: ", $query_param);

    $stmt = $db->prepare($query);

    if ($stmt->execute($query_param)) {
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

      $sessions = [];

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sessions[] = self::db_dates_to_carbon($row);
      }

      return $sessions;
    }

    return null;
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

    $db_data = self::user_dates_to_db_date($data);

    $log->addInfo(
      "About to update academic session with query: {$query} and param: ",
      ['user_data' => $data, 'db_data' => $db_data]
    );

    $stmt = $db->prepare($query);
    $stmt->execute($db_data);

    if ($stmt->rowCount()) {
      $log->addInfo("Session successfully updated!");

      return $data;
    }

    $log->addWarning("Session could not be updated!");

    return null;
  }

  public static function get_session_by_id($id)
  {
    $log = get_logger(self::$LOG_NAME);

    $query = "SELECT * FROM session_table WHERE id = ?";

    $param = [$id];

    $log->addInfo("About to get session by executing query: {$query} and param: ", $param);

    $db = get_db();

    $stmt = $db->prepare($query);

    if ($stmt->execute($param)) {
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      $log->addInfo("Query ran successfully, result is ", $result);

      return self::db_dates_to_carbon($result);
    }

    $log->addError("Query failed to run.");

    return null;
  }

  public static function session_exists_by_id($id)
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $query = "SELECT COUNT(*) FROM session_table WHERE id = ?";

    $query_param = [$id];

    $log->addInfo("About to check if session exists with query: {$query} and param: ", $query_param);

    $stmt = $db->prepare($query);

    if ($stmt->execute($query_param)) {
      $returnedVal = $stmt->fetchColumn() ? true : false;

      $log->addInfo("query successfully ran. Result is: {$returnedVal}");
      return $returnedVal;
    }

    $log->addWarning("Query did not run successfully");

    return null;
  }

  private static function db_dates_to_carbon($data)
  {
    foreach (['start_date', 'end_date', 'created_at', 'updated_at'] as $column) {
      if (isset($data[$column])) {
        $data[$column] = Carbon::parse($data[$column]);
      }
    }
    return $data;
  }

  public static function user_dates_to_db_date(array $data)
  {
    foreach (['start_date', 'end_date'] as $column) {
      if (isset($data[$column])) {
        $data[$column] = Carbon::createFromFormat('d-m-Y', $data[$column])->format('Y-m-d');
      }
    }
    return $data;
  }
}
