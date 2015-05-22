<?php

require_once(__DIR__ . '/../models/AcademicSession.php');
require_once(__DIR__ . '/../models/Semester.php');
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../Utilities.php');

use Carbon\Carbon;

class CurrentSessionSemesterInfo
{

  public static function getCurrentSession()
  {
    $diff = 'unknown';
    $startDate = 'unknown';
    $endDate = 'unknown';

    $session = AcademicSession::getCurrentSession();

    if ($session) {
      $end = $session['end_date'];
      $diff = $end->diff(Carbon::now())->days;

      $startDate = $session['start_date']->format('l, F j, Y');
      $endDate = $end->format('l, F j, Y');
    }

    $returnedVal['diff'] = $diff;
    $returnedVal['start'] = $startDate;
    $returnedVal['end'] = $endDate;
    $returnedVal['session'] = $session ? $session['session'] : null;

    return $returnedVal;
  }

  public static function getCurrentSemester()
  {
    $semester = Semester::getCurrentSemester();

    $diff = 'unknown';
    $startDate = 'unknown';
    $endDate = 'unknown';

    if ($semester) {
      $end = $semester['end_date'];

      $diff = $end->diff(Carbon::now())->days;

      $startDate = $semester['start_date']->format('l, F j, Y');
      $endDate = $end->format('l, F j, Y');
    }

    $semesterText = Semester::renderSemesterNumber($semester['number']);

    $returnedVal['diff'] = $diff;
    $returnedVal['start'] = $startDate;
    $returnedVal['end'] = $endDate;
    $returnedVal['semester'] = $semesterText ? $semesterText : 'Unknown';
    $returnedVal['panel-class'] = $diff <= 30 ? 'panel-danger' : 'panel-default';

    return $returnedVal;
  }
}

$currentSemesterInfo = CurrentSessionSemesterInfo::getCurrentSemester();
$currentSessionInfo = CurrentSessionSemesterInfo::getCurrentSession();
?>

<div class="session-semester-info row">
  <div class="alternative">
    <?php if (!$currentSessionInfo['session']) {
      echo 'Academic session has ended but new session not set.';
    }
    ?>
  </div>

  <div class="col-sm-6">
    <div class="panel <?php echo $currentSemesterInfo['panel-class'] ?>">
      <div class="panel-heading">
        <h3 class="panel-title"> <?php echo $currentSemesterInfo['semester'] ?> Semester</h3>
      </div>

      <div class="panel-body">
        <div><strong>Started:&nbsp;&nbsp;&nbsp;</strong> <?php echo $currentSemesterInfo['start'] ?> </div>
        <div><strong>Ends:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong> <?php echo $currentSemesterInfo['end'] ?>
        </div>
      </div>

      <div class="panel-footer">
        Ends in: <span class="h3"><?php echo $currentSemesterInfo['diff'] ?> Days</span>
      </div>
    </div>
  </div>

  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h2 class="panel-title">
          <?php echo $currentSessionInfo['session'] ? $currentSessionInfo['session'] : 'Unknown' ?> Session
        </h2>
      </div>

      <div class="panel-body">
        <div class="alternative">
          <?php if (!$currentSessionInfo['session']) {
            echo 'Academic session has ended but new session not set.';
          }
          ?>
        </div>

        <div><strong>Started:&nbsp;&nbsp;&nbsp;</strong>
          <?php echo $currentSessionInfo['start'] ?>
        </div>

        <div>
          <strong>Ends:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong> <?php echo $currentSessionInfo['end'] ?>
        </div>
      </div>

      <div class="panel-footer">
        Ends in: <span class="h3"><?php echo $currentSessionInfo['diff'] ?> Days</span>
      </div>
    </div>
  </div>
</div>
