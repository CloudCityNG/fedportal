<?php
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/models/StudentProfile.php');
require_once(__DIR__ . '/../../admin_academics/models/StudentCourses.php');

class StudentRegisteredSessions
{
  private static function logger()
  {
    return get_logger('StudentRegisteredSessions');
  }

  private static function logGeneralError(Exception $e, $customMessage = '')
  {
    $customMessage = $customMessage ? "Unknown {$customMessage}: " : '';
    self::logger()->addError($customMessage . $e->getMessage());
  }

  /**
   * Get all sessions and semesters in which a student registered
   * @param $regNo
   * @return array
   */
  public static function getRegisteredSessions($regNo)
  {

    $errorMessage = "error occurred while getting semester IDs for which student '{$regNo}'
    signed up for courses";

    $semesterIds = null;

    try {
      $semesterIds = StudentCourses::getSemesters($regNo);

    } catch (PDOException $e) {
      logPdoException($e, "Database {$errorMessage}", self::logger());

    } catch (Exception $e) {
      self::logGeneralError($e, $errorMessage);
    }

    $errorMessage = "error occurred while retrieving academic sessions for which student '{$regNo}'
                     registered for courses.";
    $sessionsSemestersData = [];

    if ($semesterIds) {
      try {
        $semestersWithSessions = Semester::getSemesterByIds($semesterIds, true);

        if ($semestersWithSessions) {

          foreach ($semestersWithSessions as $semester) {
            $session = $semester['session'];
            $semesterNumber = $semester['number'];

            unset($semester['session']);

            $sessionCode = $session['session'];

            if (!isset($sessionsSemestersData[$sessionCode])) {
              $sessionsSemestersData[$sessionCode] = [
                'current_level_dept' => StudentProfile::getCurrentForSession($regNo, $sessionCode),
                'session' => $session,
                'semesters' => [
                  $semesterNumber => $semester
                ]
              ];

            } else {
              $sessionsSemestersData[$sessionCode]['semesters'][$semesterNumber] = $semester;
            }
          }

          ksort($sessionsSemestersData);

          foreach ($sessionsSemestersData as $sessionCode => $data) {
            $semesters = $data['semesters'];
            ksort($semesters);
            $sessionsSemestersData[$sessionCode]['semesters'] = $semesters;
          }
        }

      } catch (PDOException $e) {
        logPdoException($e, "Database {$errorMessage}", self::logger());

      } catch (Exception $e) {
        self::logGeneralError($e, $errorMessage);
      }
    }

    return $sessionsSemestersData;
  }
}

