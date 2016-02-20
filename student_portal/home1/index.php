<?php
require_once(__DIR__ . '/../login/auth.php');
require_once(__DIR__ . '/../../helpers/models/EduHistory.php');
require_once(__DIR__ . '/../../helpers/models/StudentProfile.php');
require_once(__DIR__ . '/../../helpers/models/Medicals.php');
require_once(__DIR__ . '/../../admin_academics/models/Semester.php');
require_once(__DIR__ . '/../../admin_academics/models/StudentCourses.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');

Class StudentDashboardHome
{
  public function get()
  {
    if (session_status() == PHP_SESSION_NONE) session_start();
    $regNo = $_SESSION['REG_NO'];

    $studentDashboardHomeContext = [
      'bio_data' => self::getBioDataStatus($regNo),
      'edu_history' => self::getEduHistoryStatus($regNo),
      'medicals' => self::getMedicalStatus($regNo),
      'course_reg' => self::getCourseStatus($regNo)
    ];

    $link_template = __DIR__ . '/view.php';
    require_once(__DIR__ . '/container.php');
  }

  private static function getMedicalStatus($regNo)
  {
    $medical = Medicals::get(['reg_no' => $regNo, '__exists' => true]);

    return [
      'alert_class' => $medical ? 'alert-success' : 'alert-warning',
      'text' => 'Medical record ' . ($medical ? '[completed]' : '[not started]'),
      'link' => path_to_link(__DIR__ . '/../medicals1')
    ];
  }

  private static function getEduHistoryStatus($regNo)
  {
    $edu = EduHistory::get(['reg_no' => $regNo, '__exists' => true]);

    return [
      'alert_class' => $edu ? 'alert-success' : 'alert-warning',
      'text' => 'Education History ' . ($edu ? '[completed]' : '[not started]'),
      'link' => path_to_link(__DIR__ . '/../edu_history1')
    ];
  }

  private static function getBioDataStatus($regNo)
  {
    $bio = StudentProfile::exists($regNo);

    return [
      'alert_class' => $bio ? 'alert-success' : 'alert-warning',
      'text' => 'Bio data ' . ($bio ? '[completed]' : '[not started]'),
      'link' => path_to_link(__DIR__ . '/../bio_data1')
    ];
  }

  private static function getCourseStatus($regNo)
  {
    $semester = Semester::getCurrentSemester();
    $semesterText = Semester::renderSemesterNumber($semester['number']) . ' semester';
    $courses = StudentCourses::student_signed_up_for_semester([
      'reg_no' => $regNo, 'semester_id' => $semester['id']
    ]);

    return [
      'alert_class' => $courses ? 'alert-success' : 'alert-warning',
      'text' => "   Course registration ({$semesterText}) " . ($courses ? '[completed]' : '[not started]'),
      'link' => path_to_link(__DIR__ . '/../course_reg1')
    ];
  }

  public function post()
  {
  }
}

$home = new StudentDashboardHome();
if ($_SERVER['REQUEST_METHOD'] === 'GET') $home->get();
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') $home->post();
