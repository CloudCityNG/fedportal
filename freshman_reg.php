<?php
require_once(__DIR__ . '/helpers/auth.php');

include_once(__DIR__ . '/helpers/databases.php');

include_once(__DIR__ . '/helpers/app_settings.php');

include_once(__DIR__ . '/helpers/set_student_reg_form_completion_session.php');

use Carbon\Carbon;

$log = get_logger("");

$db = get_db();

class FreshmanRegController
{

  private static $LOGGER_NAME = 'student-bio-registration';

  private function get_email($reg_no)
  {
    $log = get_logger(self::$LOGGER_NAME);

    $db = get_db();

    $query = "SELECT email FROM pin_table WHERE  number = ?";

    $log->addInfo("About to get email for student \"$reg_no\" with query: $query");

    try {

      $stmt = $db->prepare($query);

      $stmt->execute([$reg_no]);

      $email = $stmt->fetch(PDO::FETCH_NUM)[0];

      $stmt->closeCursor();

      return $email;

    } catch (PDOException $e) {

      logPdoException(
        $e,

        "Error occurred while executing query $query
         while setting view for student registration for bio data",

        $log);
    }

    return '';
  }

  private static function bio_data_exists($reg_no)
  {
    $log = get_logger(self::$LOGGER_NAME);

    $db = get_db();

    $query = "SELECT COUNT(*) FROM freshman_profile WHERE personalno = ?";

    $log->addInfo(
      "About to check if student \"$reg_no\" has created profile with query: $query"
    );

    try {
      $stmt = $db->prepare($query);

      $stmt->execute([$reg_no]);

      $column = $stmt->fetchColumn();

      if ($column) {

        $log->addInfo("Student $reg_no has previously created profile!");

      } else {
        $log->addInfo("Student $reg_no has never a created profile!");
      }
      return $column;

    } catch (PDOException $e) {

      logPdoException(
        $e,
        "Error occurred while trying to query if student \"$reg_no\" previously created profile",
        $log
      );
    }

    return false;
  }

  public function get()
  {

    $reg_no = $_SESSION['REG_NO'];

    if (self::bio_data_exists($reg_no)) {

      set_student_reg_form_completion_session(
        'error', "Your bio data exists in database!");

      header("Location: student_dashboard.php");

      return;
    }

    $email = $this->get_email($reg_no);

    include(__DIR__ . '/freshman_reg_view.php');

    return;
  }

  public function post()
  {

    include_once(__DIR__ . '/helpers/set_student_reg_form_completion_session.php');

    $db = get_db();

    $log = get_logger("student-bio-registration");

    $validator = new \Sirius\Validation\Validator();

    $validator->add('student_bio[personalno]', 'required', null, 'Matriculation number is required.');

    $validator->add('student_bio[first_name]', 'required', null, 'First name is required');

    $validator->add('student_bio[surname]', 'required', null, 'Surname is required');

    $validator->add('student_bio[sex]', 'required', null, 'Sex is required');

    $log->addInfo("About to insert bio data for: ", $_POST['student_bio']);

    if ($validator->validate($_POST)) {

      $query = "INSERT INTO freshman_profile
                 (first_name, personalno, surname, other_names, previousname,
                  sex, dateofbirth, email, phone, permanentaddress, nationality,
                  state, lga, course, parentname, contactperson, activities,
                  currentsession, created_at, updated_at)

              VALUES (:first_name, :personalno, :surname, :other_names, :previousname,
                      :sex, :dateofbirth, :email, :phone, :permanentaddress, :nationality,
                      :state, :lga, :course, :parentname, :contactperson, :activities,
                      :currentsession, NOW(), NOW())";

      $log->addInfo("Data is valid, will be inserted into database with sql : $query");

      try {
        $stmt = $db->prepare($query);

        foreach ($_POST['student_bio'] as $param => $val) {

          if ($param === 'dateofbirth') {
            list($day, $mon, $year) = explode('-', $val);

            $val = Carbon::createFromDate($year, $mon, $day)->toDateString();
          }

          $stmt->bindValue(':' . $param, $val);

        }

        $stmt->execute();

        $log->addInfo("Bio data successfully created.");

        $this->handle_photo1($_POST['student_bio']['personalno']);

        set_student_reg_form_completion_session('success', 'Bio data saved.');

      } catch (PDOException $e) {

        logPdoException($e, "Error occurred with inserting bio into database", $log);

        set_student_reg_form_completion_session('error', 'Bio data can not be saved. Try again or contact admin');
      }

    }

    header("Location: student_dashboard.php");

  }

  private function handle_photo1($reg)
  {
    $log = get_logger(self::$LOGGER_NAME);

    try {

      $file_name = $this->upload_file_handler('photo');

      $log->addInfo("File '$file_name' for student '$reg' uploaded successfully");

      $db = get_db();

      $query = "INSERT INTO pics(personalno, nameofpic) VALUES ('$reg', '$file_name')";

      $log->addInfo("About to insert file name into database with query: $query");

      $db->query($query);

      $log->addInfo("File '$file_name' successfully inserted into database.");

      return true;

    } catch (Exception $e) {

      $log->addError("Either File upload failed with error: ");
      $log->addError(
        "Or we are unable to save file name in database with error: " . $e->getMessage()
      );
    }

    return false;
  }

  private function upload_file_handler($field_name, $uploadsDirectory = 'photo_files/')
  {
    $log = get_logger(self::$LOGGER_NAME);

    $file_errors = $_FILES[$field_name]['error'];

    if ($file_errors) {

      $log->addError("Uploaded file has error: $file_errors");

      return false;
    }

    if (!@is_uploaded_file($_FILES[$field_name]['tmp_name'])) {

      $log->addError("Upload is not a valid file.");

      return false;
    }

    if (!@getimagesize($_FILES[$field_name]['tmp_name'])) {

      $log->addError("Uploaded file is not an image file.");

      return false;
    }

    $uploadFilename = $_FILES[$field_name]['name'];

    $replaced_image_name = str_replace(['.', ' '], '', time()) . $uploadFilename;

    if (!@move_uploaded_file($_FILES[$field_name]['tmp_name'], $uploadsDirectory . $replaced_image_name)) {

      $log->addError("Uploaded file could not be saved on disk as $replaced_image_name.");

      return false;
    }

    return $replaced_image_name;
  }
}

$controller = new FreshmanRegController;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  $controller->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $controller->post();
}
