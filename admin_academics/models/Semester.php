<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');
require_once(__DIR__ . '/AcademicSession.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Carbon\Carbon;

Class Semester
{

  /**
   * @param array $post - array of columns names as keys and column values as array values
   *                      $post = [
   *                                'number' => number,
   *                                'start_date' => Y-m-d,
   *                                'end_date' => Y-m-d,
   *                                'id' => numeric
   *                                'session_id' => numeric
   *                               ]
   * @return array|null
   */
  public static function update(array $post)
  {
    $query = "UPDATE semester SET
                number = :number,
                start_date = :start_date,
                end_date = :end_date,
                session_id = :session_id
                WHERE id = :id";

    self::logger()->addInfo("About to update semester using query: {$query} and params: ", $post);

    $oldStartDate = Carbon::createFromFormat('d-m-Y', $post['start_date']);
    $oldEndDate = Carbon::createFromFormat('d-m-Y', $post['end_date']);

    $post['start_date'] = $oldStartDate->format('Y-m-d');
    $post['end_date'] = $oldEndDate->format('Y-m-d');

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($post)) {
      $post['start_date'] = $oldStartDate;
      $post['end_date'] = $oldEndDate;
      $post['session'] = AcademicSession::get_session_by_id($post['session_id']);

      self::logger()->addInfo("Semester successfully updated.");

      return $post;
    }

    self::logger()->addWarning('Could not update semester');
    return null;
  }

  /**
   * @return \Monolog\Logger
   */
  private static function logger()
  {
    return get_logger('SemesterModel');
  }

  /**
   * @param array $post
   * @return array|null
   */
  public static function create(array $post)
  {
    $db = get_db();

    $now = Carbon::now();

    $query = "INSERT INTO semester(number, start_date, end_date, created_at, updated_at, session_id)
              VALUES (:number, :start_date, :end_date, '$now', '$now', :session_id)";

    self::logger()->addInfo("About to create a new semester using query: {$query} and params: ", $post);

    $old_start_date = Carbon::createFromFormat('d-m-Y', $post['start_date']);
    $old_end_date = Carbon::createFromFormat('d-m-Y', $post['end_date']);

    $post['start_date'] = $old_start_date->format('Y-m-d');
    $post['end_date'] = $old_end_date->format('Y-m-d');

    $stmt = $db->prepare($query);

    if ($stmt->execute($post)) {
      $post['id'] = $db->lastInsertId();
      $post['created_at'] = $now;
      $post['updated_at'] = $now;
      $post['start_date'] = $old_start_date;
      $post['end_date'] = $old_end_date;
      $post['session'] = AcademicSession::get_session_by_id($post['session_id']);

      self::logger()->addInfo("Semester successfully created as: ", $post);

      return $post;
    }

    self::logger()->addError("Query to create semester failed to execute");

    return null;
  }

  public static function getImmediatePastSemester()
  {
    $query1 = "SELECT * FROM semester WHERE end_date < ? ORDER BY end_date DESC LIMIT 1";

    $param = [Carbon::now()->format('Y-m-d')];

    self::logger()->addInfo(
      "About to get immediate past semester with query {$query1}, and param: ", $param
    );

    $stmt = get_db()->prepare($query1);

    if ($stmt->execute($param)) {
      $semester = $stmt->fetch();

      if ($semester) {
        self::logger()->addInfo(
          "Query executed successfully. Immediate past semester is: ", $semester
        );

        $semester = self::dbDatesToCarbon($semester);

        $semester['session'] = AcademicSession::getCurrentSession();

        return $semester;
      }
    }

    self::logger()->addWarning("Immediate past semester not found.");
    return null;
  }

  /**
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
   * Get the current semester. But in order to get the current semester,
   * current session must have been set and must be valid
   *
   * @param array $currentSession
   * @return array|null
   */
  public static function getCurrentSemester(array $currentSession = null)
  {
    if (!$currentSession) {
      $currentSession = AcademicSession::getCurrentSession();

      if (!$currentSession) {
        self::logger()->addWarning('Current session not set. Current semester will not be available');
        return null;
      }
    }

    $today = date('Y-m-d', time());

    $query = "SELECT * FROM semester
              WHERE :today1 >= start_date
              AND :today2 <= end_date
              AND session_id = :session_id
              ORDER BY start_date LIMIT 1";

    $query_param = [
      'today1' => $today,
      'today2' => $today,
      'session_id' => $currentSession['id']
    ];

    self::logger()->addInfo(
      "About to get current semester with query: {$query} and params: ", $query_param
    );

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($query_param)) {
      $semester = $stmt->fetch();

      if ($semester) {
        self::logger()->addInfo("Query successfully ran. semester is: ", $semester);

        $semester = self::dbDatesToCarbon($semester);

        $semester['session'] = $currentSession;

        return $semester;
      }
    }

    self::logger()->addWarning("Current semester not found!");
    return null;
  }

  /**
   * Get a particular semester given its semester number and session code
   * e.g get 2nd semester in 2014/2015 session.
   *
   * @param string|int $number - semester number, 1 or 2
   * @param string|int $session - semester session e.g 2014/2015
   * @return array|null - return semester data if found or null if no
   * semester will the arguments
   */
  public static function getSemesterByNumberAndSession($number, $session)
  {
    $query1 = "SELECT id FROM session_table WHERE session = ?";

    $query2 = "SELECT * FROM semester
               WHERE number = ?
               AND session_id = ({$query1})";

    $params = [$number, $session];

    self::logger()->addInfo("About to get semester using query: {$query2} and params: ", $params);

    $stmt = get_db()->prepare($query2);

    if ($stmt->execute($params)) {
      $result = $stmt->fetch();

      if ($result) {
        self::logger()->addInfo("Statement executed successfully, result is: ", $result);
        return $result;
      }
    }

    self::logger()->addWarning("Can not get semester.");
    return null;
  }

  /**
   * Validates start and end dates of semester
   *
   * @param array $data - an array that must have two keys:
   * start_date and end_date for semester database date columns
   *
   * @param bool $newSemester - a flag indicating whether the
   * data will be used to create a new semester or update
   * an existing semester.
   *
   * @return array - we return array ['valid' => false, 'messages' => {string}]
   * if @var $data is invalid. Else we return ['valid' => true]
   */
  public static function validateDates(array $data, $newSemester = false)
  {
    $returnedVal['valid'] = false;

    if (!(isset($data['start_date']) && isset($data['end_date']))) {
      $returnedVal['messages'] = ['Start date and end date can not be null'];
      return $returnedVal;
    }

    $start_date = trim($data['start_date']);

    if (!$start_date) {
      $returnedVal['messages'] = ['Start date can not be empty'];
      return $returnedVal;
    }

    $end_date = trim($data['end_date']);

    if (!$end_date) {
      $returnedVal['messages'] = ['End date can not be empty'];
      return $returnedVal;
    }

    try {
      $dtStart = Carbon::createFromFormat('d-m-Y H', $start_date . ' 0');
      $dtEnd = Carbon::createFromFormat('d-m-Y H', $end_date . ' 0');

      if ($dtStart >= $dtEnd) {
        $returnedVal['messages'] = ['End date must be after start date'];
        return $returnedVal;
      }

      if ($newSemester) {
        $latestEndDate = self::getLatestSemesterEndDate();

        if ($latestEndDate && $latestEndDate >= $dtStart) {
          $returnedVal['messages'] = [
            "A new semester may only start after "
            . $latestEndDate->format('d-M-Y')
            . " But you specified " . $dtStart->format('d-M-Y')
          ];

          return $returnedVal;
        }
      }

    } catch (InvalidArgumentException $e) {

      $returnedVal['messages'] = ["Start date or end date has invalid date format. Allowed format is 'DD-MM-YYYY'"];
      return $returnedVal;

    } catch (PDOException $e) {
      $returnedVal['messages'] = ['Start or end date could not be validated due to database error'];
      return $returnedVal;
    }

    return ['valid' => true];
  }

  /**
   * @return null|\Carbon\Carbon
   */
  public static function getLatestSemesterEndDate()
  {
    $query = "SELECT MAX(end_date) FROM semester";

    self::logger()->addInfo("About to get latest semester end date with query: {$query}");

    $stmt = get_db()->query($query);

    if ($stmt) {
      $result = $stmt->fetch(PDO::FETCH_NUM);

      if ($result && $result[0]) {
        $dt = Carbon::createFromFormat('Y-m-d H', $result[0] . ' 0');
        self::logger()->addInfo(
          "query executed successfully. Latest semester end date is {$dt}"
        );
        return $dt;
      }
    }

    self::logger()->addInfo("Latest semester date not found may be no semester set yet.");
    return null;
  }

  /**
   * Validates whether the semester number is 1 or 2. Also enforces other business rules on semester number column
   *
   * @param array $data - the data array that will be passed to the database. Contains a key 'number'
   *
   * @param bool $newSemester - indicates whether data will be used to create new semester or update an existing semester
   * Some business rules e.g existence rule, can not be enforced for update
   *
   * @return array - we return back the data to the caller unmodified.
   */
  public static function validateNumberColumn(array $data, $newSemester = false)
  {

    $returnedVal['valid'] = false;

    if (!isset($data['number'])) {
      $returnedVal['messages'] = ["'Semester number' can not be null"];
      return $returnedVal;
    }

    $number = trim($data['number']);

    if (!$number) {
      $returnedVal['messages'] = ["'Semester number' can not be empty"];
      return $returnedVal;
    }

    if (!preg_match("/^[12]$/", $number)) {
      $returnedVal['messages'] = ['"Semester number" can only take two values: "1" or "2"'];
      return $returnedVal;
    }

    if ($newSemester) {
      if (self::semesterExists($number, $data['session_id'])) {
        $returnedVal['messages'] = [
          'The specified semester exists for the specified session: ' .
          self::renderSemesterNumber($number) . ' semester!'
        ];
        return $returnedVal;
      }
    }

    return ['valid' => true];
  }

  /**
   * @param string|int $number
   * @param string|int $sessionId
   * @return bool|null
   */
  public static function semesterExists($number, $sessionId)
  {
    $query = "SELECT COUNT(*) FROM semester
              WHERE number = ?
              AND session_id = ?";

    $param = [$number, $sessionId];

    self::logger()->addInfo("About to confirm is semester exists with query: {$query} and params: ", $param);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($param)) {
      $returnedVal = $stmt->fetchColumn() ? true : false;

      self::logger()->addInfo("Query ran successfully. Result is: {$returnedVal}");

      return $returnedVal;
    }

    self::logger()->addWarning("Query failed to run or empty result.");

    return null;
  }

  /**
   * Takes a semester number and turns 1 into 1st and 2 into 2nd
   * @param int|string $number - the semester number (whether 1 or 2)
   * @return string
   */
  public static function renderSemesterNumber($number)
  {
    if ($number == 1) {
      return '1st';

    } else if ($number == 2) {
      return '2nd';
    }
    return null;
  }

  /**
   * Either get all semesters in the database or get a limited number of semesters
   * denoted by the param $howMany argument.
   *
   * @param null|string|int $howMany - number of semesters to be retrieved.
   * If 'null', get all semesters in database
   *
   * @return array|null
   */
  public static function getSemesters($howMany = null)
  {
    $query = "SELECT * FROM semester ORDER BY end_date DESC";

    if ($howMany) {
      $query .= " LIMIT {$howMany}";
    }

    self::logger()->addInfo("About to get semesters with query: {$query}");

    $stmt = get_db()->query($query);

    if ($stmt) {
      $semesters = [];

      while ($row = $stmt->fetch()) {
        $data = self::dbDatesToCarbon($row);
        $data['session'] = AcademicSession::get_session_by_id($data['session_id']);
        $semesters[] = $data;
      }

      if (count($semesters)) {
        self::logger()->addInfo("Statement executed successfully. Semesters are: ", $semesters);
        return $semesters;
      }
    }

    self::logger()->addWarning("Semesters could not be retrieved.");
    return null;
  }

  /**
   * @param array $semesterIds - like [mixed, mixed, ....]
   * @return array|null
   */
  public static function getSessionIDsFromSemesterIDs(array $semesterIds)
  {
    $semesterIdsDbArray = toDbArray($semesterIds);
    $query = "SELECT DISTINCT(session_id) FROM semester WHERE id IN {$semesterIdsDbArray}";

    $logMessage = SqlLogger::makeLogMessage('get session IDs from array of semester IDs', $query);

    $stmt = get_db()->query($query);

    if ($stmt) {
      SqlLogger::logStatementSuccess(self::logger(), $logMessage);

      $result = [];

      while ($row = $stmt->fetch()) {
        $result[] = $row['session_id'];
      }

      if (count($result)) {
        SqlLogger::logDataRetrieved(self::logger(), $logMessage, $result);
        return self::dbDatesToCarbon($result);
      }
    }

    SqlLogger::logNoData(self::logger(), $logMessage);
    return null;
  }

  /**
   * Given session id, get the semesters registered with that session
   *
   * @param string|int $sessionId
   * @return array|null
   */
  public static function getSemestersInSession($sessionId)
  {
    $query = "SELECT * FROM semester WHERE session_id = ?";

    $param = [$sessionId];

    self::logger()->addInfo("About to get semesters in session with query: {$query}, and param: ", $param);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($param)) {
      $results = $stmt->fetchAll();

      if (count($results)) {
        $data = [];

        self::logger()->addInfo('Query ran successfully, semesters are', $results);
        foreach ($results as $result) {
          $data[] = self::dbDatesToCarbon($result);
        }

        return $data;
      }
    }

    self::logger()->addWarning('Semesters not found.');
    return null;
  }

  /**
   * Given an array of semester IDs, get the semester data from the database
   * and optionally get session information
   *
   * @param array $semesterIds - an array containing the semester IDs
   * @param bool $withSessions - whether to get session information
   * @return array|null
   */
  public static function getSemesterByIds(array $semesterIds, $withSessions = false)
  {
    $x = '(';

    foreach ($semesterIds as $id) {
      $x .= "'" . $id . "', ";
    }

    $x = trim($x, ', ') . ')';

    $query = "SELECT * FROM semester WHERE id IN $x";

    self::logger()->addInfo('About to get semesters by array of id using query ' . $query);

    $stmt = get_db()->query($query);

    if ($stmt) {
      $results = [];
      if ($withSessions) {
        while ($row = $stmt->fetch()) {
          $row['session'] = AcademicSession::get_session_by_id($row['session_id']);
          $results[] = $row;
        }

      } else {
        $results = $stmt->fetchAll();
      }

      if (count($results)) {

        self::logger()->addInfo('Semesters returned successfully, result is', $results);
        return $results;
      }
    }

    self::logger()->addWarning('Unable to get semesters using array of ids', $semesterIds);
    return null;
  }

  /**
   * Semester validator. When creating a semester, session id is required.
   * But when updating a semester, session id may not be required. Which ever
   * way, this method will validate the session id if it is given.
   *
   * @param array $data
   * @return array
   */
  public static function validateSessionIdColumn(array $data)
  {
    $returnedVal['valid'] = false;

    if (!isset($data['session_id'])) {
      $returnedVal['messages'] = ["Session ID can not be null."];
      return $returnedVal;
    }

    $id = trim($data['session_id']);

    if (!$id) {
      $returnedVal['messages'] = ["Session ID can not be empty."];
      return $returnedVal;
    }

    if (!is_numeric($id)) {
      $returnedVal['messages'] = ["Session ID can only take numeric characters."];
      return $returnedVal;
    }

    if (!AcademicSession::session_exists_by_id($id)) {
      $returnedVal['messages'] = ["Session ID does not exist."];
      return $returnedVal;
    }

    try {

      $session = AcademicSession::get_session_by_id($id);

      $sessionStart = $session['start_date'];
      $semesterStart = Carbon::createFromFormat('d-m-Y', $data['start_date']);
      $semesterStart->hour = 0;
      $semesterStart->minute = 0;
      $semesterStart->second = 0;

      if ($semesterStart < $sessionStart) {
        $returnedVal['messages'] = ['Semester can not start before session.'];
        return $returnedVal;
      }

      $sessionEnd = $session['end_date'];
      $semesterEnd = Carbon::createFromFormat('d-m-Y', $data['end_date']);
      $semesterEnd->hour = 0;
      $semesterEnd->minute = 0;
      $semesterEnd->second = 0;

      if ($semesterEnd > $sessionEnd) {
        $returnedVal['messages'] = ['Semester can not end after session.'];
        return $returnedVal;
      }

    } catch (InvalidArgumentException $e) {
      $returnedVal['messages'] = ['Semester start or end dates invalid'];
      return $returnedVal;

    } catch (PDOException $e) {
      logPdoException($e, 'Database error occurred while validating session for semester', self::logger());
    }

    return ['valid' => true];
  }
}
