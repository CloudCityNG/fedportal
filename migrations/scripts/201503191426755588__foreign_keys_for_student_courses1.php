<?php


Class A201503191426755588
{
  public function up(PDO $db)
  {
    $query = "ALTER TABLE student_courses
              DROP COLUMN `academic_year_code`,
              DROP COLUMN `semester`,
              CHANGE `semester_id` `semester_id` INT NOT NULL,

              ADD CONSTRAINT `fk_student_courses_semester_id` FOREIGN KEY (`semester_id`)
              REFERENCES `semester`(`id`)";

    $db->query($query);
  }

  public function down(PDO $db)
  {
  }
}
