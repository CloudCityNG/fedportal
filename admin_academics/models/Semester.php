<?php
/**
 * Created by maneptha on 26-Feb-15.
 */

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/AcademicSession.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Carbon\Carbon;

Class Semester
{
  private static $LOG_NAME = 'semester-model';

  public static function create($post)
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $now = Carbon::now();

    $query = "INSERT INTO semester(number, start_date, end_date, created_at, updated_at, session_id)
              VALUES (:number, :start_date, :end_date, '$now', '$now', :session_id)";

    $log->addInfo("About to create a new semester using query: {$query} and params: ", $post);

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

      $log->addInfo("Semester successfully created as: ", $post);

      return $post;
    }

    $log->addError("Query to create semester failed to execute");

    return null;
  }

  public static function get_current_semester()
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $today = date('Y-m-d', time());

    $query = "SELECT id, number, start_date, end_date
              FROM semester
              WHERE :today1 >= start_date
              AND :today2 <= end_date
              ORDER BY start_date LIMIT 1";

    $query_param = [
      'today1' => $today,
      'today2' => $today
    ];

    $log->addInfo("About to get current semester with query: {$query} and params: ", $query_param);

    $stmt = $db->prepare($query);

    $stmt->execute($query_param);

    $semester = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($semester) {
      $log->addInfo("Query successfully ran. semester is: ", $semester);

      $semester['start_date'] = self::transform_date($semester['start_date']);
      $semester['end_date'] = self::transform_date($semester['end_date']);

      $semester['session'] = AcademicSession::get_current_session();

      return $semester;

    } else {
      $log->addWarning("Current semester not found!");
    }


    return null;
  }

  private static function transform_date($val)
  {
    return implode('-', array_reverse(explode('-', $val)));
  }

  public static function validate_dates($data)
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
      $dt_start = Carbon::createFromFormat('d-m-Y', $start_date);
      $dt_end = Carbon::createFromFormat('d-m-Y', $end_date);

      if ($dt_start >= $dt_end) {
        $returnedVal['messages'] = ['End date must be after start date'];
        return $returnedVal;
      }

      $latest_end_date = self::get_latest_semester_end_date();

      if ($latest_end_date && $latest_end_date > $dt_start) {
        $returnedVal['messages'] = [
          "A new semester may only start after "
          . $latest_end_date->format('d-M-Y')
          . " But you specified " . $dt_start->format('d-M-Y')
        ];

        return $returnedVal;
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

  public static function validate_number_column($data)
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

    if (self::semester_exists($number, $data['session_id'])) {
      $returnedVal['messages'] = [
        'The specified semester exists for the specified session: ' .
        self::render_semester_number($number) . ' semester!'
      ];
      return $returnedVal;
    }

    return ['valid' => true];
  }

  public static function render_semester_number($number)
  {
    return $number == 1 ? '1st' : '2nd';
  }

  public static function semester_exists($number, $session_id)
  {
    $query = "SELECT COUNT(*) FROM semester
              WHERE number = ?
              AND session_id = ?";

    $param = [$number, $session_id];

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo("About to confirm is semester exists with query: {$query} and params: ", $param);

    $db = get_db();

    $stmt = $db->prepare($query);

    if ($stmt->execute($param)) {
      $returnedVal = $stmt->fetchColumn() ? true : false;

      $log->addInfo("Query ran successfully. Result is: {$returnedVal}");

      return $returnedVal;
    }

    $log->addWarning("Query failed to run.");

    return null;
  }

  public static function validate_session_id_column($data)
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

    return ['valid' => true];
  }

  public static function get_latest_semester_end_date()
  {
    $query = "SELECT MAX(end_date) FROM semester";

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo("About to get latest semester end date with query: {$query}");

    $db = get_db();

    $stmt = $db->query($query);

    if ($stmt) {
      $log->addInfo("query executed successfully.");

      $result = $stmt->fetch(PDO::FETCH_NUM);

      if ($result && $result[0]) {
        $dt = Carbon::createFromFormat('Y-m-d', $result[0]);
        $log->addInfo("Latest semester end date is {$dt}");
        return $dt;
      }

      $log->addInfo("Latest semester date not found may be no semester set yet.");
    }

    return null;
  }
}
