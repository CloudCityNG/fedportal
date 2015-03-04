<?php
/**
 * Created by maneptha on 20-Feb-15.
 */

require_once(__DIR__ . '/../databases.php');

require_once(__DIR__ . '/../app_settings.php');

use Carbon\Carbon;

class EduHistory
{

  private static $LOG_NAME = 'EduHistory';

  public static function get($reg_no)
  {
    $log = get_logger(self::$LOG_NAME);

    $db = get_db();

    $log->addInfo("About to get education history for student '$reg_no'");

    $query = "SELECT * FROM edu_history WHERE reg_no = ?";

    $returned_val = [];

    try {
      $stmt = $db->prepare($query);

      $stmt->execute([$reg_no]);

      if ($stmt->rowCount()) {

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $returned_val['pry_edu'] = self::get_pry_edu($result);

        $returned_val['secondary_edu'] = self::get_secondary_edu($result);

        $returned_val['o_level_scores'] = self::get_o_level_scores($result);

        $returned_val['post_secondary'] = self::get_post_secondary($result);

        $log->addInfo(
          "Education history successfully retrieved from database as: ",
          $returned_val
        );

      } else {
        $log->addWarning("Education history not found in database.");
      }

    } catch (PDOException $e) {
      logPdoException(
        $e,
        "Error occurred while retrieving education history",
        $log
      );
    }

    return $returned_val;

  }

  private static function get_pry_edu($result)
  {

    $edu = json_decode($result['pry_edu']);

    return [
      'name' => $edu->name,

      'address' => $edu->address,

      'start_date' => self::normalize_date($edu->start_date),

      'end_date' => self::normalize_date($edu->end_date),
    ];

  }

  private static function get_secondary_edu($result)
  {
    $edu = json_decode($result['secondary_edu']);

    return [
      'name' => $edu->name,

      'address' => $edu->address,

      'start_date' => self::normalize_date($edu->start_date),

      'end_date' => self::normalize_date($edu->end_date),
    ];

  }

  private static function get_o_level_scores($result)
  {

    $o_levels = json_decode($result['o_level_scores']);

    $container = [];

    foreach ($o_levels as $level) {
      $container[] = [
        'name' => $level->name,

        'year' => $level->year,

        'candidate_number' => $level->candidate_number,

        'scores' => $level->scores,
      ];
    }

    return $container;

  }

  private static function normalize_date($val)
  {

    $date_regex = "/\d{1,2}-\d{1,2}-\d{4}/";

    if (preg_match($date_regex, $val)) {
      return $val;

    } else {
      return "01-01-$val";
    }
  }

  private static function get_post_secondary(array $result)
  {
    $post = $result['post_secondary'];

    if (!$post) {
      return null;
    }

    return [
      'name' => $post->name,

      'address' => $post->address,

      'start_date' => self::normalize_date($post->start_date),

      'end_date' => self::normalize_date($post->end_date),

      'course_of_study' => $post->course_of_study,

      'qualification' => $post->qualification
    ];
  }
}
