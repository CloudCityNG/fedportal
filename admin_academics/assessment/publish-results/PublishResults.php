<?php

class PublishResultsController extends AssessmentController
{
  public function renderPage(
    array $postStatus = null,
    array $oldSemesterCourseQueryData = null
  )
  {
    $currentPage = [
      'title' => 'assessment',

      'link' => 'publish-results'
    ];

    $tenMostRecentSemesters = self::getSemestersForJSAutoComplete();

    $link_template = __DIR__ . '/view.php';

    $pageJsPath = path_to_link(__DIR__ . '/js/publish-results.min.js');

    $pageCssPath = path_to_link(__DIR__ . '/css/publish-results.min.css');

    require(__DIR__ . '/../../home/container.php');
  }

  public function post()
  {

  }
}
