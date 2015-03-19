<?php


Class A201503191426753814
{
  public function up(PDO $db)
  {
    $query1 = "ALTER TABLE student_courses
              ADD CONSTRAINT `fk_student_courses_course_id` FOREIGN KEY (`course_id`)
              REFERENCES course_table(`id`),

              ADD COLUMN `semester_id` INT NULL";

    $db->query($query1);

    $query2 = "SELECT id, academic_year_code, semester FROM student_courses";
    $stmt = $db->query($query2);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $query3 = "SELECT id FROM session_table
                 WHERE session = '{$row['academic_year_code']}'";

      $query4 = "SELECT id FROM semester
                 WHERE number = '{$row['semester']}'
                 AND session_id = ({$query3})";

      $query5 = "UPDATE student_courses SET semester_id = ({$query4})
                 WHERE id = {$row['id']}";

      $db->query($query5);
    }
  }

  public function down(PDO $db)
  {
  }
}
