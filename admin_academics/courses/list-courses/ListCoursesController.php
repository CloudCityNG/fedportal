<?php

require_once(__DIR__ . '/../../models/Courses.php');
require_once(__DIR__ . '/../../models/AcademicDepartment.php');

class ListCoursesController extends CourseController
{
  private static function logger()
  {
    return get_logger('PublishResultsController');
  }

  public function get()
  {


    $listCoursesContext = [
      'course_list' => Courses1::getCourses(),
      'departments' => AcademicDepartment::getDepartmentCodeNameMap()
    ];
    $link_template = __DIR__ . '/view.php';

    $pageCssPath = path_to_link(__DIR__ . '/css/list-courses.min.css', true);

    require(__DIR__ . '/../../home/container.php');
  }
}
