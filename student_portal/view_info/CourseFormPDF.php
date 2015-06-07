<?php
require_once(__DIR__ . '/../../helpers/pdf-config.php');
require_once(__DIR__ . '/../../helpers/tcpdf/tcpdf.php');

class CourseFormPDF extends TCPDF
{
  /**
   * @var array
   */
  private $coursesCellWidths = [
    14,             #sequence
    20,             #course code
    95,             #course title
    19,             #course unit
    35,             #Lecturer sign
  ];

  /**
   * @var string
   */
  private $regNo;

  private $studentPhotoWidth = 29.00;
  private $studentPhotoHeight = 27.9;

  private $studentInfoCellHeight = 7;

  private $deptName;

  /**
   * @var string
   */
  private $admissionSession;

  /**
   * @var float
   */
  private $studentPhotoWidthXOffset = .6;

  private $tableTextFont = 11;

  public function __construct()
  {
    parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $this->_globalSetUpPage();
  }

  private function _globalSetUpPage()
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
   * @param array $student
   * @param array $data
   */
  public function renderPage(array $student, array $data)
  {
    $this->regNo = $student['reg_no'];
    $this->deptName = $student['dept_name'];
    $this->admissionSession = $student['admission_session'];

    $semesterNumber = $data['semester_number'];
    $sessionData = $data['session_data'];
    $sessionCode = $sessionData['session']['session'];
    $level = $sessionData['current_level_dept']['level'];

    $semesterText = $semesterNumber == 1 ?
      "FIRST SEMESTER - ({$level}) ({$sessionCode})" :
      "SECOND SEMESTER - ({$level}) ({$sessionCode})";

    $this->_drawStudentInfo($student, $semesterText);
    $this->Ln(6);

    $this->_drawTableHeader();
    $this->_drawTableBody($data['courses']);
    $this->Ln(15);

    $this->_drawConfirmations();

    $this->Output($this->regNo . '.pdf', 'd');
  }

  /**
   * @param array $student
   * @param string $semesterText
   */
  private function _drawStudentInfo(array $student, $semesterText)
  {
    $columnWidths = [
      'header' => 35,
      'data' => 70,
    ];

    $this->SetFont('', 'B', 11);

    $this->MultiCell(
      array_sum($this->coursesCellWidths),
      '',
      "COURSE REGISTRATION FORM\n{$semesterText}",
      0,
      'C',
      0,
      0
    );

    $this->Ln(15);

    $photo = $student['photo'];

    $this->Image(
      $photo ? $photo : K_BLANK_IMAGE,
      '',
      '',
      $this->studentPhotoWidth,
      $this->studentPhotoHeight,
      ''
    );

    $this->_drawStudentInfoRow('NAME OF STUDENT', $student['names'], 0, $columnWidths, 'T');
    $this->Ln();

    $this->_drawStudentInfoRow('REGISTRATION NO', $this->regNo, 1, $columnWidths);
    $this->Ln();

    $this->_drawStudentInfoRow('DEPARTMENT', $this->deptName, 0, $columnWidths);
    $this->Ln();

    $this->_drawStudentInfoRow('YEAR OF ADMISSION', $this->admissionSession, 1, $columnWidths);
    $this->Ln();

    $this->_setStudentInfoXOffset();
    $this->Cell(array_sum($columnWidths), 0, '', 'T');
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
   */
  private function _drawTableHeader()
  {
    $this->SetFillColor(200, 219, 255);
    $this->SetTextColor(0);
    $this->SetDrawColor(128, 0, 0);
    $this->SetLineWidth(0.1);
    $this->SetFont('helvetica', 'B', $this->tableTextFont, '', true);

    $headers = [
      'S/NO.',
      'COURSE CODE',
      'COURSE TITLE',
      'CREDIT UNIT',
      'LECTURER SIGN',
    ];

    $numHeaders = count($this->coursesCellWidths);

    for ($index = 0; $index < $numHeaders; $index++) {

      $this->MultiCell(
        $this->coursesCellWidths[$index], //width
        9.9,                                     //height
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
   * Draw table body with student's courses for the semester
   *
   * @param array $courses
   * @internal param array $semesterDataAndCourses
   */
  private function _drawTableBody(array $courses)
  {
    $rowHeightSingle = 10;
    $border = 'LRTB';
    $nextPos = 0;
    $maxLenCharsPerLine = 55;

    $this->SetFillColor(224, 235, 255);
    $this->SetTextColor(0);
    $this->SetFont('');

    $fill = 0;
    $seq = 1;
    $sumUnits = 0;

    foreach ($courses as $course) {
      $unit = floatval($course['unit']);
      $sumUnits += $unit;
      $unit = number_format($unit, 1);

      $title = $course['title'];
      $rowHeightSingleOvershoot = intval(strlen($title) / $maxLenCharsPerLine);
      $rowHeight = $rowHeightSingle * ($rowHeightSingleOvershoot + 1);

      $this->MultiCell($this->coursesCellWidths[0], $rowHeight, $seq++, 'LTB', 'R', $fill, $nextPos);
      $this->MultiCell($this->coursesCellWidths[1], $rowHeight, $course['code'], $border, 'L', $fill, $nextPos);
      $this->MultiCell($this->coursesCellWidths[2], $rowHeight, $title, $border, 'L', $fill, $nextPos);
      $this->MultiCell($this->coursesCellWidths[3], $rowHeight, $unit, $border, 'C', $fill, $nextPos);
      $this->MultiCell($this->coursesCellWidths[4], $rowHeight, '', $border, '', $fill, $nextPos);

      $this->Ln();
      $fill = !$fill;
    }

    $this->SetFont('', 'B');

    $this->Cell($this->coursesCellWidths[0], $rowHeight, '', 0, '', $nextPos, 0);
    $this->Cell($this->coursesCellWidths[1], $rowHeight, 'TOTAL', $border, 'C', $nextPos, 0);
    $this->Cell($this->coursesCellWidths[2], $rowHeight, '', $border, '', $nextPos, 0);
    $this->Cell($this->coursesCellWidths[3], $rowHeight, number_format($sumUnits, 1), $border, 'C', $nextPos, $fill);
  }

  private function _drawConfirmations()
  {
    $signers = [
      "HOD'S CONFIRMATION", 'ACADEMIC ADVISER', 'DEPUTY RECTOR'
    ];

    $fill = 0;

    foreach ($signers as $signer) {
      $this->Cell(50, 10, $signer, 'LTB', 0, 'L', $fill);
      $this->Cell(100, 10, '', 'LRTB', 1, 'L', $fill);
      $fill = !$fill;
    }

  }
}
