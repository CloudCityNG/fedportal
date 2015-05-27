<?php
require_once(__DIR__ . '/../../../helpers/pdf-config.php');
require_once(__DIR__ . '/../../../helpers/tcpdf/tcpdf.php');

class TranscriptToPDF extends TCPDF
{
  /**
   * @var array
   */
  private $coursesScoresCellWidths = [
    14,             #sequence
    81,            #course title
    22,             #course code
    19,             #course unit
    25,             #score + grade
    20,             #quality point
  ];

  /**
   * @var float
   */
  private $studentPhotoWidth = 20.00;

  /**
   * @var float
   */
  private $studentPhotoHeight = 19.85;

  private $studentInfoCellHeight = 5;

  /**
   * @var float
   */
  private $studentPhotoWidthXOffset = .6;

  private $tableTextFont = 11;

  /**
   * The student registration number
   *
   * @var string
   */
  private $regNo;

  /**
   * @constructor
   *
   * @param array $studentScoresData
   */
  public function __construct(array $studentScoresData)
  {
    parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $this->_setUpPage();

    $student = $studentScoresData['student'];
    $this->regNo = $student['reg_no'];

    $sessionsSemestersCoursesGrades = $studentScoresData['sessions_semesters_courses_grades'];
    $numSessions = count($sessionsSemestersCoursesGrades);

    $yearsOfStudyArray = ['1st', '2nd', '3rd', '4th', '5th', '6th'];
    $yearOfStudy = 0;

    foreach ($sessionsSemestersCoursesGrades as $session => $sessionData) {
      $level = $sessionData['current_level_dept']['level'];
      $this->_drawStudentInfo($student, $level, $yearsOfStudyArray[$yearOfStudy++]);

      foreach ($sessionData['semesters'] as $semesterNumber => $semesterDataAndCourses) {
        $this->_drawTableHeader($session, $semesterNumber, $level);
        $this->_drawTableBody($semesterDataAndCourses);
      }

      if (--$numSessions) {
        $this->AddPage();
      }
    }

    $this->Output($this->regNo . '.pdf', 'd');
  }

  private function _setUpPage()
  {
    $this->SetHeaderData(
      PDF_HEADER_LOGO,
      PDF_HEADER_LOGO_WIDTH,
      SCHOOL_NAME,
      SCHOOL_ADDRESS . "\n" . SCHOOL_WEBSITE,
      [0, 64, 255],
      [0, 64, 128]
    );

    $this->setHeaderFont(['helvetica', '', 14]);

    $this->setFooterData([0, 64, 0], [0, 64, 128]);

    $this->setFooterFont(['helvetica', '', PDF_FONT_SIZE_DATA]);

    $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $this->SetHeaderMargin(PDF_MARGIN_HEADER);
    $this->SetFooterMargin(PDF_MARGIN_FOOTER);

    $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $this->setFontSubsetting(true);

    $this->SetFont('helvetica', '', 8, '', true);

    $this->AddPage();
  }

  /**
   * @param array $studentInfo
   * @param string $level - the student's level during the academic session e.g ND1
   * @param $yearOfStudyText - how many years the student has spent in the college during the session
   */
  private function _drawStudentInfo(array $studentInfo, $level, $yearOfStudyText)
  {
    $columnWidths = [
      'header' => 35,
      'data' => 60,
    ];

    $this->SetFont('', 'B', 10);

    $durationYearOffset = 13;

    $this->MultiCell(
      array_sum($this->coursesScoresCellWidths),
      15,
      "STATEMENT OF RESULT\nFIRST/SECOND SEMESTER & RESIT",
      0,
      'C',
      0,
      0
    );

    $this->Ln();

    $photo = $studentInfo['photo'];
    $this->Image($photo ? $photo : K_BLANK_IMAGE, '', '', $this->studentPhotoWidth, $this->studentPhotoHeight, '');

    $this->_drawStudentInfoRow('STUDENT NAME', $studentInfo['names'], 0, $columnWidths, 'T');
    $this->Ln();
    $this->_drawStudentInfoRow('REGISTRATION NO', $studentInfo['reg_no'], 1, $columnWidths);
    $this->Ln();
    $this->_drawStudentInfoRow('DEPARTMENT', $studentInfo['dept_name'], 0, $columnWidths);

    $this->SetX($this->GetX() + $durationYearOffset);
    $this->Cell(30, $this->studentInfoCellHeight, 'DURATION OF COURSE:     4', 0);
    $this->Ln();

    $this->_drawStudentInfoRow('YEAR OF ADMISSION', $studentInfo['academic_year'], 1, $columnWidths);

    $this->SetX($this->GetX() + $durationYearOffset);
    $this->Cell(30, $this->studentInfoCellHeight, "YEAR OF STUDY:                {$yearOfStudyText} ({$level})", 0);
    $this->Ln();

    $this->_setStudentInfoXOffset();
    $this->Cell(array_sum($columnWidths), 0, '', 'T');
    $this->Ln(5);
  }

  /**
   * Draw a row of student information
   *
   * @param string $header - the row header for the student information
   * @param string $data - the student information
   *
   * @param string $fill - whether the row will be filled or not
   * this affects only student data as the header is always filled
   *
   * @param array $columnWidths
   *
   * @param string $topBorder - whether to draw top border for student
   * information (headers always have top border) - will not be drawn by default
   */
  private function _drawStudentInfoRow($header, $data, $fill, array $columnWidths, $topBorder = '')
  {

    $this->SetFont('', '', 8);
    $this->SetFillColor(224, 235, 255);
    $this->_setStudentInfoXOffset();

    //draw header
    $this->SetTextColor(0);
    $this->SetDrawColor(128, 0, 0);
    $this->SetLineWidth(0.001);
    $this->Cell($columnWidths['header'], $this->studentInfoCellHeight, $header, 'LT', 0, 'L', 1);


    //draw student information
    $this->SetTextColor(0);
    $this->Cell($columnWidths['data'], $this->studentInfoCellHeight, $data, 'LR' . $topBorder, 0, 'L', $fill);
  }

  private function _setStudentInfoXOffset()
  {
    $this->SetX($this->GetX() + $this->studentPhotoWidth + $this->studentPhotoWidthXOffset);
  }

  /**
   * @param string $session - the academic session code e.g 2014/2015
   * @param string|int $semesterNumber - the semester number, 1 or 2
   * @param string $level - student's level in a particular session e.g ND2
   */
  private function _drawTableHeader($session, $semesterNumber, $level)
  {
    $semesterText = $semesterNumber == 1 ? "FIRST SEMESTER - ({$level}) ({$session})" : "SECOND SEMESTER - ({$level})";

    $this->SetFillColor(200, 219, 255);
    $this->SetTextColor(0);
    $this->SetDrawColor(128, 0, 0);
    $this->SetLineWidth(0.1);
    $this->SetFont('helvetica', 'B', $this->tableTextFont, '', true);

    $this->cell(array_sum($this->coursesScoresCellWidths), '', $semesterText, '', 1, 'C');

    $headers = [
      'S/NO.',
      'COURSE TITLE',
      'COURSE CODE',
      'CREDIT UNIT',
      'GRADE OBTAINED',
      "QUALITY POINT",
    ];

    $numHeaders = count($this->coursesScoresCellWidths);

    for ($index = 0; $index < $numHeaders; $index++) {

      $this->MultiCell(
        $this->coursesScoresCellWidths[$index], //width
        10,                                     //height
        $headers[$index],                       //text
        1,                                      //border
        'C',                                    //align
        1,                                      //fill
        0                                       //next line
      );
    }

    $this->Ln();
  }

  /**
   * Draw table body with student results
   *
   * @param array $semesterDataAndCourses
   */
  private function _drawTableBody(array $semesterDataAndCourses)
  {
    $rowHeightSingle = 5;
    $border = 'LRTB';
    $nextPos = 0;
    $maxLenCharsPerLine = 46;

    $this->SetFillColor(224, 235, 255);
    $this->SetTextColor(0);
    $this->SetFont('');

    $fill = 0;
    $seq = 1;

    foreach ($semesterDataAndCourses['courses'] as $course) {
      $unit = number_format($course['unit'], 1);

      $grade = $course['grade'];
      $scoreGrade = $course['score'] . '  ' . (strlen($grade) === 2 ? $grade : $grade . '   ');

      $title = $course['title'];
      $rowHeightSingleOvershoot = intval(strlen($title) / $maxLenCharsPerLine);
      //$overshootHeightReducer = $rowHeightSingleOvershoot * 1.5;
      $rowHeight = $rowHeightSingle * ($rowHeightSingleOvershoot + 1) - 0;

      $this->MultiCell($this->coursesScoresCellWidths[0], $rowHeight, $seq++, 'LTB', 'R', $fill, $nextPos);
      $this->MultiCell($this->coursesScoresCellWidths[1], $rowHeight, $title, $border, 'L', $fill, $nextPos);
      $this->MultiCell($this->coursesScoresCellWidths[2], $rowHeight, $course['code'], $border, 'L', $fill, $nextPos);
      $this->MultiCell($this->coursesScoresCellWidths[3], $rowHeight, $unit, $border, 'C', $fill, $nextPos);
      $this->MultiCell($this->coursesScoresCellWidths[4], $rowHeight, $scoreGrade, $border, 'R', $fill, $nextPos);
      $this->MultiCell($this->coursesScoresCellWidths[5], $rowHeight, $course['quality_point'], $border, 'C', $fill, $nextPos);

      $this->Ln();
      $fill = !$fill;
    }

    $this->SetFont('', 'B');

    $this->Cell($this->coursesScoresCellWidths[0], 5, '', 0, '', $nextPos, 0);
    $this->Cell($this->coursesScoresCellWidths[1], 5, 'TOTAL', $border, 'C', $nextPos, 0);
    $this->Cell($this->coursesScoresCellWidths[2], 5, '', $border, '', $nextPos, 0);
    $this->Cell($this->coursesScoresCellWidths[3], 5, $semesterDataAndCourses['sum_units'], $border, 'C', $nextPos, $fill);
    $this->Cell($this->coursesScoresCellWidths[4], 5, '', $border, '', $nextPos, 0);
    $this->Cell($this->coursesScoresCellWidths[5], 5, $semesterDataAndCourses['sum_points'], $border, 'C', $nextPos, $fill);

    $this->Ln(7);
    $this->SetX(150);
    $this->Cell(15, 5, "GPA", 0, 'R', $nextPos, 0);
    $this->Cell(10, 5, "=", 0, 'C', $nextPos, 0);
    $this->Cell(10, 5, $semesterDataAndCourses['gpa'], 0, 'C', $nextPos, 0);

    if (isset($semesterDataAndCourses['cgpa'])) {
      $this->Ln();
      $this->SetX(150);
      $this->Cell(15, 5, "CGPA", 0, 'R', $nextPos, 0);
      $this->Cell(10, 5, "=", 0, 'C', $nextPos, 0);
      $this->Cell(10, 5, $semesterDataAndCourses['cgpa'], 0, 'C', $nextPos, 0);
    }

    $this->Ln(10);
  }
}
