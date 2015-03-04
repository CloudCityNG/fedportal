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

    $post['start_date'] = self::transform_date($post['start_date']);
    $post['end_date'] = self::transform_date($post['end_date']);

    $stmt = $db->prepare($query);

    $stmt->execute($post);
    $post['id'] = $db->lastInsertId();
    $post['created_at'] = $now->toDateTimeString();
    $post['updated_at'] = $now->toDateTimeString();

    return $post;
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
}
