<?php


Class A201503191426753823
{
  public function up(PDO $db)
  {
    $query2 = "SELECT id, academic_year_code, semester FROM student_courses WHERE id < 2001 AND id > 1800";
    $stmt2 = $db->query($query2);
    $stmt2_results = $stmt2->fetchAll();
    $stmt2->closeCursor();

    $query3 = "SELECT id FROM session_table
               WHERE session = :academic_year_code";

    $query4 = "SELECT id FROM semester
               WHERE number = :semester
               AND session_id = ({$query3})";

    $query5 = "UPDATE student_courses SET semester_id = ({$query4})
               WHERE id = :id";

    $stmt3 = $db->prepare($query5);

    $academic_year_code = '';
    $semester = '';
    $id = '';

    $stmt3->bindParam('academic_year_code', $academic_year_code);
    $stmt3->bindParam('semester', $semester);
    $stmt3->bindParam('id', $id);

    foreach ($stmt2_results as $row) {
      $academic_year_code = $row['academic_year_code'];
      $semester = $row['semester'];
      $id = $row['id'];
      $stmt3->execute();
    }
  }

  public function down(PDO $db)
  {
  }
}
