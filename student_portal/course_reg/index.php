<?php

require_once(__DIR__ . '/../login/auth.php');

include_once(__DIR__ . '/../../helpers/databases.php');

include_once(__DIR__ . '/../../helpers/get_courses.php');

include_once(__DIR__ . '/../../helpers/course_exists.php');

include_once(__DIR__ . '/../../admin_academics/models/AcademicSession.php');
include_once(__DIR__ . '/../../admin_academics/models/Semester.php');

include_once(__DIR__ . '/../../helpers/get_academic_departments.php');

include_once(__DIR__ . '/../../helpers/get_student_profile_from_reg_no.php');

include_once(__DIR__ . '/../../helpers/get_photos.php');

include_once(__DIR__ . '/../../helpers/get_academic_levels.php');

include_once(__DIR__ . '/../../helpers/app_settings.php');

include_once(__DIR__ . '/../../helpers/models/StudentProfile.php');

class CourseReg
{
  private static $LOG_NAME = 'Course-registration';
}

$reg_no = $_SESSION['REG_NO'];

if (!StudentProfile::student_exists($reg_no)) {

  require_once(__DIR__ . '/../home/set_student_reg_form_completion_session.php');

  set_student_reg_form_completion_session1(
    'error',
    'You have not selected your department! Please complete bio data.');

  $home = STATIC_ROOT . 'student_portal/home/';
  header("Location: {$home}");
}

$profile = new StudentProfile($reg_no);

$dept_code = $profile->dept_code;

$dept_name = get_academic_departments()[$dept_code];

$semester = Semester::get_current_semester()['number'];

$semester_text = Semester::render_semester_number($semester);

$academic_year = AcademicSession::get_current_session()['session'];

$course_data = course_exists($academic_year, $reg_no, $semester);

$already_registered = !empty($course_data);

$student = get_student_profile_from_reg_no($reg_no);

$course_reg_post = STATIC_ROOT . 'student_portal/course_reg/course_reg_post';

include(__DIR__ . '/view.php');
