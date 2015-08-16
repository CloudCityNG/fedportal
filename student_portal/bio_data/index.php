<?php
require_once(__DIR__ . '/../login/auth.php');
require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../home/set_student_reg_form_completion_session.php');
require_once(__DIR__ . '/../../helpers/models/StudentProfile.php');
require_once(__DIR__ . '/../../admin_academics/models/AcademicSession.php');

class FreshmanRegController1
{

  private static $LOGGER_NAME = 'student-bio-registration';

  public function get()
  {

    $regNo = $_SESSION['REG_NO'];

    if (StudentProfile::student_exists($regNo)) {

      set_student_reg_form_completion_session1(
        'error', "Your bio data exists in database!");

      $home = STATIC_ROOT . 'student_portal/home/';
      header("Location: {$home}");

      return;
    }

    $email = $this->get_email($regNo);

    include(__DIR__ . '/view.php');

    return;
  }

  private function get_email($regNo)
  {
    $log = get_logger(self::$LOGGER_NAME);

    $query = "SELECT email FROM pin_table WHERE  number = ?";

    $log->addInfo("About to get email for student \"$regNo\" with query: $query");

    try {

      $stmt = get_db()->prepare($query);

      $stmt->execute([$regNo]);

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

  public function post()
  {

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

      $shouldBeUppercase = [
        'permanentaddress',
        'contactperson',
        'first_name',
        'surname',
        'other_names',
        'previousname',
        'activities',
        'parentname',
        'lga',
        'nationality',
      ];

      try {
        $stmt = get_db()->prepare($query);

        foreach ($_POST['student_bio'] as $param => $val) {

          if (in_array($param, $shouldBeUppercase)) $val = strtoupper($val);

          $stmt->bindValue($param, $val);

        }

        $stmt->execute();

        $log->addInfo("Bio data successfully created.");

        $this->handlePhoto($_POST['student_bio']['personalno']);

        set_student_reg_form_completion_session1('success', 'Bio data saved.');

      } catch (PDOException $e) {

        logPdoException($e, "Error occurred with inserting bio into database", $log);

        set_student_reg_form_completion_session1('error', 'Bio data can not be saved. Try again or contact admin');
      }

    }

    $home = STATIC_ROOT . 'student_portal/home/';
    header("Location: {$home}");

  }

  private function handlePhoto($reg)
  {
    $log = get_logger(self::$LOGGER_NAME);

    try {

      $fileName = $this->uploadFileHandler('photo');

      $log->addInfo("File '$fileName' for student '$reg' uploaded successfully");

      $query = "INSERT INTO pics(personalno, nameofpic) VALUES ('$reg', '$fileName')";

      $log->addInfo("About to insert file name into database with query: $query");

      get_db()->query($query);

      $log->addInfo("File '$fileName' successfully inserted into database.");

      return true;

    } catch (Exception $e) {

      $log->addError(
        "Either File upload failed with error: " .
        "Or we are unable to save file name in database with error: " .
        $e->getMessage()
      );
    }

    return false;
  }

  private function uploadFileHandler($fieldName)
  {
    $log = get_logger(self::$LOGGER_NAME);

    $fileErrors = $_FILES[$fieldName]['error'];

    if ($fileErrors) {

      $log->addError("Uploaded file has error: $fileErrors");

      return false;
    }

    if (!@is_uploaded_file($_FILES[$fieldName]['tmp_name'])) {

      $log->addError("Upload is not a valid file.");

      return false;
    }

    if (!@getimagesize($_FILES[$fieldName]['tmp_name'])) {

      $log->addError("Uploaded file is not an image file.");

      return false;
    }

    $uploadFilename = basename($_FILES[$fieldName]['name']);

    $replacedImageName = str_replace(['.', ' '], '', time()) . $uploadFilename;

    $destination = get_photo_dir() . $replacedImageName;

    if (!@move_uploaded_file($_FILES[$fieldName]['tmp_name'], $destination)) {

      $log->addError("Uploaded file could not be saved on disk as {$destination}.");

      return false;
    }

    return $replacedImageName;
  }
}

$controller = new FreshmanRegController1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  $controller->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $controller->post();
}
