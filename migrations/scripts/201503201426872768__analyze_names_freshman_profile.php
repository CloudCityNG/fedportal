<?php


Class A201503201426872768
{
  public function up(PDO $db)
  {
    $db = get_db();

    $query1 = "SELECT id, surname FROM freshman_profile WHERE first_name IS NULL";

    $stmt1 = $db->query($query1);

    $query2 = "UPDATE freshman_profile
                SET surname = :surname,
                first_name = :first_name,
                other_names = :other_names
               WHERE id = :id";

    $stmt2 = $db->prepare($query2);

    $id = '';
    $surname = '';
    $first_name = '';

    $stmt2->bindParam('id', $id);
    $stmt2->bindParam('surname', $surname);
    $stmt2->bindParam('first_name', $first_name);

    while ($row = $stmt1->fetch()) {
      $names = [];
      $id = $row['id'];

      foreach (explode(' ', $row['surname']) as $the_name) {
        $the_name = trim($the_name);
        if ($the_name) {
          $names[] = $the_name;
        }
      }

      $surname = strtoupper(trim($names[0]));
      $first_name = strtoupper(trim($names[1]));

      $other_names = strtoupper(trim(implode(' ', array_slice($names, 2))));

      $stmt2->bindParam('other_names', $other_names, $other_names ? PDO::PARAM_STR : PDO::PARAM_NULL);

      $stmt2->execute();
    }
  }

  public function down(PDO $db)
  {
  }
}
