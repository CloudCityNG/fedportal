<?php


class StudentCoursesUtilities
{

  /**
   * Compute semester GPA for the student. Also in the process, computer sum of course units,
   * and points.
   *
   * @param array $coursesData - of the form:
   * [
   *    'courses' => []
   * ]
   *
   * @return array - input array ($coursesData) augmented with gpa, sum of units and points. Of the form;
   * [
   *    'courses' => [],
   *    'sum_units' => number|string,
   *    'gpa' => number|string,
   *    'sum_points' => number|string
   * ]
   *
   */
  public static function addGpaInfo(array $coursesData)
  {
    $sumUnits = 0;
    $sumQualityPoints = 0;

    $courses = [];

    foreach ($coursesData['courses'] as $data) {
      $unit = floatval($data['unit']);
      $point = floatval($data['point']);
      $qualityPoint = $unit * $point;

      $data['quality_point'] = number_format($qualityPoint, 2);
      $courses[] = $data;

      $sumUnits += $unit;
      $sumQualityPoints += $qualityPoint;
    }

    $coursesData['courses'] = $courses;
    $coursesData['sum_units'] = number_format($sumUnits, 2);
    $coursesData['gpa'] = number_format($sumQualityPoints / $sumUnits, 2);
    $coursesData['sum_points'] = number_format($sumQualityPoints, 2);

    return $coursesData;
  }

  /**
   * Render student scores in a table, suitable for display
   *
   * @param string $session - the academic session code e.g 2014/2015
   * @param string|int $semesterNumber - the semester number, 1 or 2
   * @param array $coursesData
   * @param string $level - student's level in a particular session e.g ND2
   * @return string - a table rendered with student's courses, scores and grades as well
   * as session and semester information
   *
   */
  public static function renderCoursesData($session, $semesterNumber, array $coursesData, $level)
  {
    $semesterText = $semesterNumber == 1 ?
      "FIRST SEMESTER - ({$level}) ({$session})" :
      "SECOND SEMESTER - ({$level})  ({$session})";

    $tableStart = "
    <table class='table table-striped table-condense table-bordered student-transcript-table'
      style='border-bottom: 0;border-left: 0;margin-bottom: 5px;'
      >
        <caption
          style='text-align: center;font-weight: bolder;font-size: 16px;font-style: italic;color: #898989;padding: 0;'>
            {$semesterText}
        </caption>

      <thead>
        <tr>
          <th>S/N</th>
          <th>Course<br/>Code</th>
          <th>Course Title</th>
          <th>Credit<br/>Unit</th>
          <th class='student-courses-display-existing-score'>Score</th>
          <th>Grade</th>
          <th>Quality<br/>Point</th>
        </tr>
      </thead>

      <tbody>\n";

    $coursesTableBody = '';
    $count = 1;

    foreach ($coursesData['courses'] as $course) {
      $unit = number_format($course['unit'], 1);

      $coursesTableBody .= "
            <tr>
                <td>{$count}</td>
                <td>{$course['code']}</td>
                <td>{$course['title']}</td>
                <td>{$unit}</td>
                <td>{$course['score']}</td>
                <td>{$course['grade']}</td>
                <td>{$course['quality_point']}</td>
            </tr>\n
           ";

      $count++;
    }

    $cgpaDisplay = '';

    if (isset($coursesData['cgpa'])) {
      $cgpaDisplay = "<br/>CGPA&nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;{$coursesData['cgpa']}";
    }

    $coursesTableBody .= "
                <tr style='font-weight: bolder;'>
                    <td style='border: 0; background-color: #FFF;'></td>
                    <td style='border: 0; background-color: #FFF;'></td>
                    <td>TOTAL</td>
                    <td>{$coursesData['sum_units']}</td>
                    <td></td>
                    <td></td>
                    <td>{$coursesData['sum_points']}</td>
                </tr>
             </tbody>
          </table>";

    if (isset($coursesData['gpa'])) {
      $coursesTableBody .= "
        <div style='margin-bottom: 25px;width: 200px;float: right;'>
            GPA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;{$coursesData['gpa']}
            {$cgpaDisplay}
         </div>";
    }

    return $tableStart . $coursesTableBody;
  }
}
