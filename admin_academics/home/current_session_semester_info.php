<?php

/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 17-Mar-15
 * Time: 5:35 PM
 */

require_once(__DIR__ . '/../models/AcademicSession.php');
require_once(__DIR__ . '/../models/Semester.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Carbon\Carbon;

class CurrentSessionSemesterInfo
{

  public static function get_current_session()
  {
    $session = AcademicSession::get_current_session();

    $end = $session['end_date'];

    $returnedVal['diff'] = $end->diff(Carbon::now())->days;
    $returnedVal['start'] = $start = $session['start_date']->format('l, F j, Y');
    $returnedVal['end'] = $end->format('l, F j, Y');
    $returnedVal['session'] = $session['session'];

    return $returnedVal;
  }

  public static function get_current_semester()
  {
    $semester = Semester::get_current_semester();

    $end = $semester['end_date'];

    $diff = $end->diff(Carbon::now())->days;

    $returnedVal['diff'] = $diff;
    $returnedVal['start'] = $semester['start_date']->format('l, F j, Y');
    $returnedVal['end'] = $end->format('l, F j, Y');
    $returnedVal['semester'] = Semester::render_semester_number($semester['number']);
    $returnedVal['panel-class'] = $diff <= 30 ? 'panel-danger' : 'panel-default' ;

    return $returnedVal;
  }
}

$currentSemesterInfo = CurrentSessionSemesterInfo::get_current_semester();
$currentSessionInfo = CurrentSessionSemesterInfo::get_current_session();
