<?php


Class A201503201426839462
{
  public function up(PDO $db)
  {
    $academic_year = '2014/2015';

    $query1 = "SELECT DISTINCT reg_no, level FROM `student_courses`
               WHERE `reg_no` NOT IN (SELECT DISTINCT student_currents.reg_no FROM student_currents
                                  WHERE student_courses.reg_no = student_currents.reg_no
                                  AND `academic_year` = '{$academic_year}')";

    $stmt1 = $db->query($query1);

    $query2 = "SELECT course AS dept_code, description AS dept_name
           FROM freshman_profile JOIN academic_departments ON (course = code)
           WHERE personalno = :reg_no";

    $reg_no = '';

    $stmt2 = $db->prepare($query2);
    $stmt2->bindParam('reg_no', $reg_no);

    $query3 = "INSERT INTO student_currents (reg_no, academic_year, level, dept_code, dept_name)
               VALUES (:reg_no, :academic_year, :level, :dept_code, :dept_name)";

    $level = '';
    $dept_code = '';
    $dept_name = '';

    $stmt3 = $db->prepare($query3);
    $stmt3->bindParam('reg_no', $reg_no);
    $stmt3->bindParam('academic_year', $academic_year);
    $stmt3->bindParam('level', $level);
    $stmt3->bindParam('dept_code', $dept_code);
    $stmt3->bindParam('dept_name', $dept_name);

    foreach ($stmt1->fetchAll() as $row) {
      $reg_no = $row['reg_no'];
      $stmt2->execute();

      $dept = $stmt2->fetch();

      $level = $row['level'];
      $dept_code = $dept['dept_code'];
      $dept_name = $dept['dept_name'];

      $stmt3->execute();
    }

  }

  public function down(PDO $db)
  {
  }
}
