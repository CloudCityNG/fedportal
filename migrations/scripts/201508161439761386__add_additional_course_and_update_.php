<?php


Class A201508161439761386
{
  public function up(PDO $db)
  {
    $db->query("INSERT INTO `course_table` (`title`, `code`, `unit`, `department`, `semester`, `class`) VALUES
                ('Clinical Psychology', 'PSY.472', 2, 'dental_therapy', 1, 'HND II')");

    $courseId = $db->lastInsertId();

    $stmtFirstEverSemester = $db->query("SELECT id FROM semester ORDER BY start_date LIMIT 1");
    $semesterId = $stmtFirstEverSemester->fetch()['id'];

    $stmtStudents = $db->query("SELECT personalno FROM freshman_profile WHERE course = 'dental_therapy'");
    $students = $stmtStudents->fetchAll();

    $stmtInsert = $db->prepare("INSERT INTO student_courses (reg_no, course_id, level, created_at,
                                  updated_at, semester_id, publish) VALUES
                                (:regNo, '{$courseId}', 'HND II', NOW(), NOW(), '{$semesterId}', '0')");

    $regNo = '';
    $stmtInsert->bindParam('regNo', $regNo);

    foreach ($students as $student) {
      $regNo = $student['personalno'];
      $stmtInsert->execute();
    }

  }

  public function down(PDO $db)
  {
  }
}
