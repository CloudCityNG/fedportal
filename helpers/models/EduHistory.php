<?php

require_once(__DIR__ . '/../databases.php');
require_once(__DIR__ . '/../app_settings.php');
require_once(__DIR__ . '/../SqlLogger.php');

use Carbon\Carbon;

class EduHistory
{

  private static function logger()
  {
    return get_logger('EduHistoryModel');
  }

  /**
   * Get students education history and optionally filter by $filter param
   * @param array|null $filter
   * @return null|array
   */
  public static function get(array $filter = null)
  {
    $query = 'SELECT * FROM edu_history ';
    $existsOnly = false;

    if ($filter) {
      if (isset($filter['__exists'])) {

        if ($filter['__exists']) {
          $existsOnly = true;
          $query = 'SELECT COUNT(*) FROM edu_history ';
        }

        unset($filter['__exists']);
      }

      $dbBindParams = getDbBindParamsFromColArray(array_keys($filter));
      $query .= " WHERE {$dbBindParams}";

    } else $filter = [];

    $logger = new SqlLogger(self::logger(), 'Get student education history:', $query, $filter);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($filter)) {
      $logger->statementSuccess();

      if ($existsOnly) {
        $result = $stmt->fetchColumn();
        $logger->dataRetrieved($result);
        return $result;
      }

      $result = $stmt->fetch();
      $logger->dataRetrieved($result);
      $returned_val = [];
      $returned_val['pry_edu'] = self::getPryEdu($result);
      $returned_val['secondary_edu'] = self::getSecondaryEdu($result);
      $returned_val['o_level_scores'] = self::getOlevelScores($result);
      $returned_val['post_secondary'] = self::getPostSecondaryEdu($result);
      return $returned_val;
    }

    $logger->noData();
    return null;
  }

  private static function getPryEdu($result)
  {
    $edu = json_decode($result['pry_edu']);

    return [
      'name' => $edu->name,

      'address' => $edu->address,

      'start_date' => self::normalize_date($edu->start_date),

      'end_date' => self::normalize_date($edu->end_date),
    ];

  }

  private static function getSecondaryEdu($result)
  {
    $edu = json_decode($result['secondary_edu']);

    return [
      'name' => $edu->name,

      'address' => $edu->address,

      'start_date' => self::normalize_date($edu->start_date),

      'end_date' => self::normalize_date($edu->end_date),
    ];

  }

  private static function getOlevelScores($result)
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

  private static function getPostSecondaryEdu(array $result)
  {
    $post = $result['post_secondary'];

    if (!$post) return null;

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
