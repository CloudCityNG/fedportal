<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 11-Feb-15
 * Time: 5:30 PM
 */

include_once(__DIR__ . '/../databases.php');

include_once(__DIR__ . '/../app_settings.php');

class AcademicYear
{

  private $db;

  private $log;

  public function __construct()
  {
    $this->db = get_db();

    $this->log = get_logger("academic-year");
  }

  public function get_current_year()
  {

    try {
      $this->log->addInfo("About to get current academic year");

      $stmt = $this->db->prepare("SELECT code FROM academic_sessions ORDER BY id DESC LIMIT 1");

      $stmt->execute();

      $year = $stmt->fetch(PDO::FETCH_NUM)[0];

      $this->log->addInfo("Current academic year is $year");

      return $year;

    } catch (PDOException $e) {

      $this->log->addError("An error occurred while getting current academic year");

      $this->log->addError($e->getMessage());

      return null;
    }

  }

  public function get_years($num = null)
  {

    $num = $num ? $num : 1;

    try {
      $this->log->addInfo("About to get current academic year");

      $stmt = $this->db->prepare("SELECT code FROM academic_sessions ORDER BY id DESC LIMIT $num");

      $stmt->execute();

      $years = [];

      while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $years[] = $row[0];
      }

      $this->log->addInfo("$num most recent academic years are: ", $years);

      return $years;

    } catch (PDOException $e) {

      logPdoException($e, "An error occurred while getting current academic year", $this->log);

      return null;
    }

  }

}