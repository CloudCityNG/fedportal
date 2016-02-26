<?php
require_once(__DIR__ . '/../login/auth.php');
require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../home/set_student_reg_form_completion_session.php');
require_once(__DIR__ . '/../../helpers/models/StudentProfile.php');
require_once(__DIR__ . '/../../helpers/models/Pin.php');
require_once(__DIR__ . '/../../admin_academics/models/AcademicSession.php');

class FreshmanBioDataController
{

  private static $LOGGER_NAME = 'FreshmanBioDataController';

  private static function logger()
  {
    return get_logger('FreshmanBioDataController');
  }

  public function get()
  {
    $regNo = $_SESSION[STUDENT_PORTAL_AUTH_KEY];
    $student = json_decode($_SESSION[USER_AUTH_SESSION_KEY], true);

    if (!$student) $student = StudentProfile::getStudentProfile($regNo);

    $email = self::getEmail($regNo);
    $link_template = __DIR__ . '/view.php';
    $pageJsPath = path_to_link(__DIR__ . '/js/bio-data.min.js', true);
    require(__DIR__ . '/../home/container.php');
  }

  private static function getEmail($regNo)
  {
    try {
      $result = Pin::get(['number' => $regNo]);

      if ($result) return $result[0]['email'];

    } catch (PDOException $e) {
      logPdoException($e, 'Error while getting student email from pin table', self::logger());

    } catch (Exception $ex) {
      self::logger()->addError('Error while getting student email from pin table', $ex->getTrace());
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
        header('Location: ' . STATIC_ROOT . 'student_portal/home1/');
        return;

      } catch (PDOException $e) {
        logPdoException($e, "Error occurred with inserting bio into database", $log);
        set_student_reg_form_completion_session1('error', 'Bio data can not be saved. Try again or contact admin');
        header('Location: ' . path_to_link(__DIR__));
        return;
      }
    }
  }

  private function handlePhoto($reg)
  {
    $log = get_logger(self::$LOGGER_NAME);

    try {

      $fileName = $this->uploadFileHandler('photo');

      $log->addInfo("File '$fileName' for student '$reg' uploaded successfully");

      $db = get_db();

      $studentId = null;

      $stmtFreshmanId = $db->query("SELECT id FROM freshman_profile WHERE personalno = '{$reg}'");

      if ($stmtFreshmanId) {
        $studentId = $stmtFreshmanId->fetch()['id'];
      }

      $query = "INSERT INTO pics(personalno, nameofpic, created_at, freshman_profile_id)
                VALUES ('$reg', '$fileName', NOW(), '{$studentId}')";

      $log->addInfo("About to insert file name into database with query: $query");

      if ($studentId) {
        $db->query($query);

        $log->addInfo("File '$fileName' successfully inserted into database.");

        return true;

      }
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

$controller = new FreshmanBioDataController;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  $controller->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $controller->post();
}
