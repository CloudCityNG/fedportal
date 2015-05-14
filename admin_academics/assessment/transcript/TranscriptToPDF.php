<?php

require(__DIR__ . '/transcript-to-pdf-config.php');
require_once(__DIR__ . '/../../../helpers/tcpdf/tcpdf.php');

class TranscriptToPDF
{
  /**
   * @constructor
   *
   * @param array $studentScoresData
   */
  function __construct(array $studentScoresData)
  {
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetHeaderData(
      PDF_HEADER_LOGO,
      PDF_HEADER_LOGO_WIDTH,
      PDF_HEADER_TITLE,
      PDF_HEADER_STRING,
      [0, 64, 255],
      [0, 64, 128]
    );

    $pdf->setFooterData([0, 64, 0], [0, 64, 128]);

    $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->setFontSubsetting(true);

    $pdf->SetFont('helvetica', '', 14, '', true);

    $pdf->AddPage();

    $student = $studentScoresData['student'];
    $regNo = $student['reg_no'];

    $studentInfo = self::renderStudentInfo($student);
    $result = self::buildResult($studentScoresData['courses']);

    $pdfBody = "
      {$studentInfo}

      <hr/>

      <table class='table table-striped table-condense table-bordered student-transcript-table'>
            <thead>
                <tr>
                  <th>S/N</th>
                  <th>Code</th>
                  <th>Title</th>
                  <th class='student-courses-display-existing-score'>Score</th>
                  <th>Grade</th>
                </tr>
            </thead>

            <tbody>{$result}</tbody>
      </table>";


    $pdf->writeHTMLCell(0, 0, '', '', $pdfBody, 0, 1, 0, true, '', true);
    $pdf->Output("{$regNo}.pdf", 'd');
  }

  /**
   * Take student information and use it to write some html.
   * The html will be written to pdf
   *
   * @param array $student
   * @return string
   */
  private static function renderStudentInfo(array $student)
  {
    return "
        <div class='media-body'>
          <table class='table table-condense table-bordered'>
              <tbody>
                  <tr>
                      <th>NAMES</th> <td>{$student['names']}</td>
                  </tr>

                  <tr>
                      <th>REGISTRATION NO</th> <td>{$student['reg_no']}</td>
                  </tr>

                  <tr>
                      <th>DEPARTMENT</th> <td>{$student['dept_name']}</td>
                  </tr>

                  <tr>
                      <th>LEVEL</th> <td>{$student['level']}</td>
                  </tr>

                  <tr>
                      <th>YEAR OF ADMISSION</th> <td>{$student['academic_year']}</td>
                  </tr>
              </tbody>
          </table>
      </div>
    ";
  }

  /**
   *Turn the student information and courses scores into
   * html @html_tag "tbody" string that will be inserted into an html table
   * This table will then be in turn written to the pdf
   *
   * @param array $courses
   *
   * @return string - return string of table rows where each row respresents
   * a course and the scores and grades obtained
   */
  private static function buildResult(array $courses)
  {
    $resultBody = '';

    $count = 1;

    foreach ($courses as $course) {
      $resultBody .= "
            <tr>
                <td>{$count}</td>
                <td>{$course['code']}</td>
                <td>{$course['title']}</td>
                <td>{$course['score']}</td>
                <td>{$course['grade']}</td>
            </tr>
           ";
      $count++;
    }

    return $resultBody;
  }
}
