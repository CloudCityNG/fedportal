<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Carbon\Carbon;

class AcademicSession
{
  /**
   * In case we can not find the current session because the session has passed,
   * we get the session that falls on this year.
   *
   * @return null|array
   */
  public static function getAlternativeCurrentSession()
  {
    $thisYear = Carbon::now()->format('Y');

    $query = "SELECT * FROM session_table
              WHERE session LIKE '%/{$thisYear}%'
              ORDER BY end_date DESC LIMIT 1";

    self::logger()->addInfo("About to get alternative current session with query {$query}.");

    $stmt = get_db()->query($query);

    if ($stmt) {
      $session = $stmt->fetch();

      if ($session) {
        self::logger()->addInfo('Alternative session retrieved successfully, result is ', $session);
        return self::dbDatesToCarbon($session);
      }
    }

    self::logger()->addWarning('Unable to get alternative current session.');
    return null;
  }

  /**
   * @return \Monolog\Logger
   */
  private static function logger()
  {
    return get_logger('AcademicSessionModel');
  }

  private static function dbDatesToCarbon($data)
  {
    foreach (['start_date', 'end_date', 'created_at', 'updated_at'] as $column) {
      if (isset($data[$column])) {
        $data[$column] = Carbon::parse($data[$column]);
      }
    }
    return $data;
  }

  public static function get_sessions($how_many = null)
  {
    $query = "SELECT * FROM session_table ORDER BY session DESC";

    if ($how_many) {
      $query .= " LIMIT {$how_many}";
    }

    self::logger()->addInfo("About to get sessions with query: {$query}");

    $stmt = get_db()->query($query);

    if ($stmt) {
      $sessions = [];

      while ($row = $stmt->fetch()) {
        $sessions[] = self::dbDatesToCarbon($row);
      }

      self::logger()->addInfo("Statement executed successfully. Session is: ", $sessions);

      return $sessions;
    }

    self::logger()->addWarning("Statement did not execute.");
    return null;
  }

  /**
   * @return null|array
   */
  public static function getCurrentSession()
  {
    $today = date('Y-m-d', time());

    $query = "SELECT *
              FROM session_table
              WHERE :today1 >= start_date
              AND :today2 <= end_date
              ORDER BY start_date LIMIT 1";

    $queryParam = [
      'today1' => $today,
      'today2' => $today
    ];

    self::logger()->addInfo(
      "About to get current session with query: {$query} and params: ", $queryParam
    );

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($queryParam)) {

      $session = $stmt->fetch();

      if ($session) {
        self::logger()->addInfo("Query successfully ran. Session is: ", $session);
        return self::dbDatesToCarbon($session);
      }
    }

    self::logger()->addWarning("Current session not found!");
    return null;
  }

  /**
   * @param array $data
   * @return array|null
   */
  public static function createSession(array $data)
  {
    $now = Carbon::now();

    $query = "INSERT INTO session_table (session, start_date, end_date, created_at, updated_at)
              VALUES (:session, :start_date, :end_date, '$now', '$now')";

    $data['start_date'] = Carbon::createFromFormat('d-m-Y', $data['start_date'])->format('Y-m-d');
    $data['end_date'] = Carbon::createFromFormat('d-m-Y', $data['end_date'])->format('Y-m-d');

    self::logger()->addInfo("About to create new session using query: {$query} and param: ", $data);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($data) && $stmt->rowCount()) {
      self::logger()->addInfo("Session successfully created!");

      $data['start_date'] = Carbon::parse($data['start_date']);
      $data['end_date'] = Carbon::parse($data['end_date']);
      $data['created_at'] = $now;
      $data['updated_at'] = $now;

      return $data;
    }

    self::logger()->addWarning('Unable to create session.');
    return null;
  }

  /**
   * Checks whether a session e.g 2014/2015 already exist in the database
   *
   * @param string $session - the session code e.g 2014/2015
   * @return bool|null
   */
  public static function sessionExistsBySession($session)
  {
    $query = "SELECT COUNT(*) FROM session_table WHERE session = ?";

    $query_param = [$session];

    self::logger()->addInfo("About to check if session exists with query: {$query} and param: ", $query_param);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($query_param)) {
      self::logger()->addInfo("query successfully ran");
      return $stmt->fetchColumn() ? true : false;
    }

    self::logger()->addWarning("Query did not run successfully");

    return null;
  }

  public static function datesUnique(array $dates)
  {
    $query = "SELECT COUNT(*) FROM session_table
              WHERE start_date = :start_date
              AND end_date = :end_date";

    self::logger()->addInfo(
      "About to check if session start and end dates exist
       with query: {$query} and params: ",
      $dates
    );

    $stmt = get_db()->prepare($query);

    $stmt->execute();

    return $stmt->fetchColumn() ? false : true;
  }

  public static function getTwoMostRecentSessions()
  {
    $query = "SELECT * FROM session_table ORDER BY session DESC LIMIT 2";

    self::logger()->addInfo("About to get 2 most recent sessions with query {$query}.");

    $stmt = get_db()->query($query);

    if ($stmt && $stmt->rowCount()) {

      $sessions = [];

      while ($row = $stmt->fetch()) {
        $sessions[] = self::dbDatesToCarbon($row);
      }

      self::logger()->addInfo("SQL statement ran successfully. The two most recent sessions are: ", $sessions);

      return $sessions;
    }

    self::logger()->addWarning('Unable to retrieve 2 most recent sessions');
    return null;
  }

  public static function update_session(array $data)
  {

    $query = "UPDATE session_table
              SET start_date = :start_date,
              end_date = :end_date,
              session = :session
              WHERE id = :id";

    $db_data = self::userDatesToBbDate($data);

    self::logger()->addInfo(
      "About to update academic session with query: {$query} and param: ",
      ['user_data' => $data, 'db_data' => $db_data]
    );

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($db_data) && $stmt->rowCount()) {
      self::logger()->addInfo("Session successfully updated!");

      return $data;
    }

    self::logger()->addWarning("Session could not be updated!");
    return null;
  }

  /**
   * @param array $data
   * @return array
   */
  public static function userDatesToBbDate(array $data)
  {
    foreach (['start_date', 'end_date'] as $column) {
      if (isset($data[$column])) {
        $data[$column] = Carbon::createFromFormat('d-m-Y', $data[$column])->format('Y-m-d');
      }
    }
    return $data;
  }

  public static function get_session_by_id($id)
  {
    $query = "SELECT * FROM session_table WHERE id = ?";

    $param = [$id];

    self::logger()->addInfo("About to get session by executing query: {$query} and param: ", $param);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($param)) {
      $result = $stmt->fetch();

      if ($result) {
        self::logger()->addInfo("Query ran successfully, result is ", $result);

        return self::dbDatesToCarbon($result);
      }
    }

    self::logger()->addError("Query failed to run.");
    return null;
  }

  /**
   * @param string|int $id
   * @return bool|null
   */
  public static function session_exists_by_id($id)
  {
    $query = "SELECT COUNT(*) FROM session_table WHERE id = ?";

    $query_param = [$id];

    self::logger()->addInfo("About to check if session exists with query: {$query} and param: ", $query_param);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($query_param)) {
      $returnedVal = $stmt->fetchColumn() ? true : false;

      self::logger()->addInfo("query successfully ran. Result is: {$returnedVal}");
      return $returnedVal;
    }

    self::logger()->addWarning("Query did not run successfully");

    return null;
  }
}
