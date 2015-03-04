<?php

include_once(__DIR__ . '/helpers/auth.php');

include_once(__DIR__ . '/helpers/databases.php');

include_once(__DIR__ . '/helpers/get_courses.php');

include_once(__DIR__ . '/helpers/course_exists.php');

include_once(__DIR__ . '/helpers/get_current_academic_session.php');

include_once(__DIR__ . '/helpers/get_academic_departments.php');

include_once(__DIR__ . '/helpers/get_student_profile_from_reg_no.php');

include_once(__DIR__ . '/helpers/get_photos.php');

include_once(__DIR__ . '/helpers/get_academic_levels.php');

include_once(__DIR__ . '/helpers/app_settings.php');

include_once(__DIR__ . '/helpers/models/StudentProfile.php');

class CourseReg
{
  private static $LOG_NAME = 'Course-registration';
}

$reg_no = $_SESSION['REG_NO'];

if (!StudentProfile::student_exists($reg_no)) {

  include_once(__DIR__ . '/helpers/set_student_reg_form_completion_session.php');

  set_student_reg_form_completion_session(
    'error',
    'You have not selected your department! Please complete bio data.');

  header("Location: student_dashboard.php");
}

$profile = new StudentProfile($reg_no);

$dept_code = $profile->dept_code;

$dept_name = get_academic_departments()[$dept_code];

$db = get_db();

$semester_stmt = $db->query("SELECT number FROM semester;");

$semester = $semester_stmt->fetch(PDO::FETCH_NUM)[0];

$academic_year = get_current_academic_session();

$course_data = course_exists($academic_year, $reg_no, $semester);

$already_registered = !empty($course_data);

$semester_text = $semester . ($semester == 1 ? 'st' : 'nd');

$student = get_student_profile_from_reg_no($reg_no);

$course_reg_post = STATIC_ROOT . 'student_portal/course_reg/course_reg_post';

include(__DIR__ . '/student_portal/course_reg/course_reg_view.php');
