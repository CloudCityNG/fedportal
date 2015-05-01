<?php

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
?>

<div class="session-semester-info row">
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
        <h2 class="panel-title"> <?php echo $currentSessionInfo['session']?> Session</h2>
      </div>

      <div class="panel-body">
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
