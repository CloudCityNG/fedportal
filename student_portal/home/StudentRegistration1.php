<?php

/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 04-Feb-15
 * Time: 11:16 PM
 */

require_once(__DIR__ . '/../../helpers/app_settings.php');

require_once(__DIR__ . '/../../helpers/models/Photo.php');

require_once(__DIR__ . '/../../helpers/models/Medicals.php');

require_once(__DIR__ . '/../../helpers/models/Courses.php');

require_once(__DIR__ . '/../../helpers/databases.php');


class StudentRegistration1
{

  public $reg_no;

  public $html_status_classes;

  public $html_status_texts;

  public $form_completion = false;

  public $form_completion_class;

  public $form_completion_message;

  private static $FORM_COMPLETION_SESSION_KEY = 'STUDENT-REG-FORM-REGISTRATION';


  public function __construct($reg_no = null)
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    $this->reg_no = $reg_no ? $reg_no : $_SESSION['REG_NO'];

    $this->init_statuses();

    $this->set_form_completion_error();
  }

  private function get_profile()
  {
    $db = get_db();

    $stmt = $db->prepare("SELECT COUNT(*) FROM freshman_profile WHERE personalno = ?;");

    $stmt->execute([$this->reg_no]);

    return $stmt->fetchColumn();
  }

  private function edu_history_completed()
  {

    $db = get_db();

    $stmt1 = $db->prepare("SELECT Count(*) FROM edu_history WHERE reg_no = ?");

    $stmt1->execute([$this->reg_no]);

    if ($stmt1->fetchColumn()) {
      return true;
    }

    $stmt = $db->prepare("SELECT COUNT(*) FROM freshman_olevels WHERE personalno = ?");

    $stmt->execute([$this->reg_no]);

    return $stmt->fetchColumn();

  }

  private function init_statuses()
  {
    $photo_obj = new Photo;

    $photo = $photo_obj->exists($this->reg_no);

    $medicals = Medicals::exists($this->reg_no);

    $profile = $this->get_profile();

    $edu_history = $this->edu_history_completed();

    $courses = (new Courses())->exists($this->reg_no);

    $this->html_status_classes = [

      'bio_data' => $profile ? 'alert-success text-success' : 'alert-warning',

      'edu_history' => $edu_history ? 'alert-success text-success' : 'alert-warning',

      'photo' => $photo ? 'alert-success text-success' : 'alert-warning',

      'medicals' => $medicals ? 'alert-success text-success' : 'alert-warning',

      'courses' => $courses ? 'alert-success text-success' : 'alert-warning',
    ];

    $this->html_status_texts = [

      'bio_data' => $profile ? 'Completed' : 'Not Started',

      'edu_history' => $edu_history ? 'Completed' : 'Not Started',

      'photo' => $photo ? 'Completed' : 'Not Started',

      'medicals' => $medicals ? 'Completed' : 'Not Started',

      'courses' => $courses ? 'Completed' : 'Not Started',
    ];
  }

  private function set_form_completion_error()
  {
    if (isset($_SESSION[self::$FORM_COMPLETION_SESSION_KEY])) {

      $form_completion = json_decode($_SESSION[self::$FORM_COMPLETION_SESSION_KEY]);

      $this->form_completion = true;

      if (isset($form_completion->error)) {

        $this->form_completion_message = "<strong>Error!</strong> " . $form_completion->error->message;

        $this->form_completion_class = 'alert-danger';

      } elseif (isset($form_completion->success)) {

        $this->form_completion_message = "<strong>Congrats!</strong> " . $form_completion->success->message;

        $this->form_completion_class = 'alert-success';
      }

      unset($_SESSION[self::$FORM_COMPLETION_SESSION_KEY]);
    }
  }

}
