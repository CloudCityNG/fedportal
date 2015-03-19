<?php


Class A201503191426763763
{
  public function up(PDO $db)
  {
    $query = "ALTER TABLE student_courses
              DROP INDEX `reg_no_academic_year_code_semester_course_id`,
              ADD UNIQUE INDEX `student_courses_reg_no_course_id_semester_id` (reg_no, course_id, semester_id)";

    $db->query($query);
  }

  public function down(PDO $db)
  {
  }
}
