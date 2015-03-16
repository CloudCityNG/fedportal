<?php
require_once(__DIR__ . '/../login/auth.php');

require_once(__DIR__ . '/../../helpers/databases.php');

require_once(__DIR__ . '/../../helpers/app_settings.php');

require_once(__DIR__ . '/../home/set_student_reg_form_completion_session.php');

include_once(__DIR__ . '/../../helpers/get_academic_sessions.php');

require_once(__DIR__ . '/../../helpers/models/StudentProfile.php');

require_once(__DIR__ . '/../../admin_academics/models/AcademicSession.php');

use Carbon\Carbon;

class FreshmanRegController1
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

  public function get()
  {

    $reg_no = $_SESSION['REG_NO'];

    if (StudentProfile::student_exists($reg_no)) {

      set_student_reg_form_completion_session1(
        'error', "Your bio data exists in database!");

      $home = STATIC_ROOT . 'student_portal/home/';
      header("Location: {$home}");

      return;
    }

    $email = $this->get_email($reg_no);

    $academic_sessions = get_academic_sessions();

    include(__DIR__ . '/view.php');

    return;
  }

  public function post()
  {

    $db = get_db();

    $log = get_logger(self::$LOGGER_NAME);

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

        $this->handle_photo($_POST['student_bio']['personalno']);

        set_student_reg_form_completion_session1('success', 'Bio data saved.');

      } catch (PDOException $e) {

        logPdoException($e, "Error occurred with inserting bio into database", $log);

        set_student_reg_form_completion_session1('error', 'Bio data can not be saved. Try again or contact admin');
      }

    }

    $home = STATIC_ROOT . 'student_portal/home/';
    header("Location: {$home}");

  }

  private function handle_photo($reg)
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

  private function upload_file_handler($field_name)
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

    $uploadFilename = basename($_FILES[$field_name]['name']);

    $replaced_image_name = str_replace(['.', ' '], '', time()) . $uploadFilename;

    $destination = get_photo_dir() . $replaced_image_name;

    if (!@move_uploaded_file($_FILES[$field_name]['tmp_name'], $destination)) {

      $log->addError("Uploaded file could not be saved on disk as {$destination}.");

      return false;
    }

    return $replaced_image_name ;
  }
}

$controller = new FreshmanRegController1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  $controller->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $controller->post();
}
