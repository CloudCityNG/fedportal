<?php


Class A201503191426753813
{
  public function up(PDO $db)
  {
    $query1 = "ALTER TABLE student_courses
               ADD CONSTRAINT `fk_student_courses_course_id` FOREIGN KEY (`course_id`)
               REFERENCES course_table(`id`),

               ADD COLUMN `semester_id` INT NULL";

    $stmt1 = $db->query($query1);
    $stmt1->closeCursor();
  }

  public function down(PDO $db)
  {
  }
}
