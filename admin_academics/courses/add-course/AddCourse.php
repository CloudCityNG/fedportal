<?php

class AddCourseController
{
  public function renderPage(array $addCourseContext = [])
  {

    $link_template = __DIR__ . '/add-course-partial.php';

    $pageJsPath = path_to_link(__DIR__ . '/js/add-course.min.js', true);

    $pageCssPath = path_to_link(__DIR__ . '/css/add-course.min.css', true);

    require(__DIR__ . '/../../home/container.php');
  }

  public function post()
  {

  }
}
