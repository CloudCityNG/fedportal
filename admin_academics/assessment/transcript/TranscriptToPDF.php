<?php

require(__DIR__ . '/transcript-to-pdf-config.php');
require_once(__DIR__ . '/../../../helpers/tcpdf/tcpdf.php');

class TranscriptToPDF extends TCPDF
{
  /**
   * @constructor
   *
   * @param array $studentScoresData
   */
  public function __construct(array $studentScoresData)
  {
    parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $this->setUpPage();

    $this->cellWidths = [
      10,             #sequence
      22,             #course code
      108,             #course title
      10,             #course unit
      16,             #score
      15,             #grade
    ];

    $student = $studentScoresData['student'];
    $coursesScores = $studentScoresData['courses'];
    $regNo = $student['reg_no'];

    $this->drawTableHeader();

    $this->drawTableBody($coursesScores);

    $this->Output("{$regNo}.pdf", 'd');
  }

  private function setUpPage()
  {
    $this->SetHeaderData(
      PDF_HEADER_LOGO,
      PDF_HEADER_LOGO_WIDTH,
      'Transcript of Academic Records',
      "Federal College of Dental Technology And Therapy Enugu\nwww.fedsdtten.com",
      [0, 64, 255],
      [0, 64, 128]
    );

    $this->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);

    $this->setFooterData([0, 64, 0], [0, 64, 128]);

    $this->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

    $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $this->SetHeaderMargin(PDF_MARGIN_HEADER);
    $this->SetFooterMargin(PDF_MARGIN_FOOTER);

    $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $this->setFontSubsetting(true);

    $this->SetFont('helvetica', '', 11, '', true);

    $this->AddPage();
  }

  private function drawTableHeader()
  {
    $this->SetFillColor(200, 219, 255);
    $this->SetTextColor(0);
    $this->SetDrawColor(128, 0, 0);
    $this->SetLineWidth(0.1);

    $this->SetFont('helvetica', 'B', 9, '', true);

    $headers = [
      'S/N',
      'Code',
      'Title',
      'Unit',
      'Score',
      'Grade',
    ];

    $numHeaders = count($this->cellWidths);

    for ($index = 0; $index < $numHeaders; $index++) {
      $this->Cell(
        $this->cellWidths[$index],
        5,
        $headers[$index],
        1, 0, 'C', 1
      );
    }

    $this->Ln();
  }

  /**
   * Draw table body with student results
   *
   * @param array $coursesScores
   */
  private function drawTableBody(array $coursesScores)
  {
    $this->SetFillColor(224, 235, 255);
    $this->SetTextColor(0);
    $this->SetFont('');
    $fill = 0;
    $seq = 1;

    foreach ($coursesScores as $course) {
      $this->Cell($this->cellWidths[0], 5, $seq++, 'LR', 0, 'R', $fill);
      $this->Cell($this->cellWidths[1], 5, $course['code'], 'LR', 0, 'L', $fill);
      $this->Cell($this->cellWidths[2], 5, $course['title'], 'LR', 0, 'L', $fill);
      $this->Cell($this->cellWidths[3], 5, $course['unit'], 'LR', 0, 'C', $fill);
      $this->Cell($this->cellWidths[4], 5, $course['score'], 'LR', 0, 'R', $fill);
      $this->Cell($this->cellWidths[5], 5, $course['grade'], 'LR', 0, 'C', $fill);

      $this->Ln();
      $fill = !$fill;
    }
    $this->Cell(array_sum($this->cellWidths), 0, '', 'T');

  }
}
