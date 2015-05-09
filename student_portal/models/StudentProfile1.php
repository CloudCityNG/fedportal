<?php
require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');

class StudentProfile1
{

  /**
   * @param string $reg
   * @return array|null
   */
  public static function getStudentByRegNo($reg)
  {
    $query = "SELECT * FROM freshman_profile WHERE  personalno = ?";

    $param = [$reg];

    self::logger()->addInfo("About to get student profile with query: {$query} and params: ", $param);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($param)) {
      $student = $stmt->fetch();

      if ($student) {
        self::logger()->addInfo("Student was found, result is ", $student);
        return $student;
      }
    }

    self::logger()->addWarning('Unable to find student.');
    return null;
  }

  private static function logger()
  {
    return get_logger('StudentProfileModel');
  }
}
