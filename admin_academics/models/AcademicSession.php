<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');
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

  /**
   * the session_table has 4 date columns
   * 1. start_date
   * 2. end_date
   * 3. created_at
   * 4. updated_at
   *
   * Turn the dates from the form in which it was retrieved from database to Carbon objects
   *
   * @param array $data
   * @return array
   */
  private static function dbDatesToCarbon(array $data)
  {
    foreach (['start_date', 'end_date', 'created_at', 'updated_at'] as $column) {
      if (isset($data[$column])) {
        $data[$column] = Carbon::parse($data[$column]);
      }
    }
    return $data;
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

  /**
   * Either get all academic sessions in the database or get a limited number of sessions
   *
   * @param null|string $howMany - number of sessions to be retrieved. If 'null', get all sessions in database
   * @return array|null
   */
  public static function getSessions($howMany = null)
  {
    $query = "SELECT * FROM session_table ORDER BY session DESC";

    if ($howMany) {
      $query .= " LIMIT {$howMany}";
    }

    self::logger()->addInfo("About to get sessions with query: {$query}");

    $stmt = get_db()->query($query);

    if ($stmt) {
      $sessions = [];

      while ($row = $stmt->fetch()) {
        $sessions[] = self::dbDatesToCarbon($row);
      }

      if (count($sessions)) {
        self::logger()->addInfo("Statement executed successfully. Sessions are: ", $sessions);

        return $sessions;
      }
    }

    self::logger()->addWarning("Sessions could not be retrieved.");
    return null;
  }

  public static function updateSession(array $data)
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
   *
   * @private
   */
  private static function userDatesToBbDate(array $data)
  {
    foreach (['start_date', 'end_date'] as $column) {
      if (isset($data[$column])) {
        $data[$column] = Carbon::createFromFormat('d-m-Y', $data[$column])->format('Y-m-d');
      }
    }
    return $data;
  }

  /**
   * Given an ID which represents the ID of a session in the database, get the session informaton
   *
   * @param $id - the database academic session ID
   * @return null|array - array of a row of academic session or null if there is no academic session with given ID
   */
  public static function get_session_by_id($id)
  {
    $query = "SELECT * FROM session_table WHERE id = ?";
    $param = [$id];

    $logMessage = SqlLogger::makeLogMessage('get a session from its ID', $query, $param);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($param)) {
      SqlLogger::logStatementSuccess(self::logger(), $logMessage);

      $result = $stmt->fetch();

      if ($result) {
        SqlLogger::logDataRetrieved(self::logger(), $logMessage, $result);

        return self::dbDatesToCarbon($result);
      }
    }

    SqlLogger::logNoData(self::logger(), $logMessage);
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

  /**
   * Given an array of session IDs, get the session information that correspond to the IDs
   *
   * @param array $sessionIds - the array of session IDs of the form [1, 2, 3, 4]
   * @return array|null
   */
  public static function getSessionsFromIds(array $sessionIds)
  {
    $sessionDbIds = toDbArray($sessionIds);
    $query = "select * from session_table where id in {$sessionDbIds}";

    $logMsg = SqlLogger::makeLogMessage("get session data from array of IDs", $query);

    $stmt = get_db()->query($query);

    if ($stmt) {
      SqlLogger::logStatementSuccess(self::logger(), $logMsg);
      $results = $stmt->fetchAll();

      if (count($results)) {
        SqlLogger::logDataRetrieved(self::logger(), $logMsg, $results);
        return self::dbDatesToCarbon($results);
      }
    }

    SqlLogger::logNoData(self::logger(), $logMsg);
    return null;
  }
}
