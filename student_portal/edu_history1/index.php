<?php
require_once(__DIR__ . '/../../helpers/auth.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../home/set_student_reg_form_completion_session.php');

class EduHistoryController
{

  private static $LOG_NAME = 'EduHistoryController';

  public function get()
  {
    $reg_no = $_SESSION['REG_NO'];
    $link_template = __DIR__ . '/view.php';
    $pageJsPath = path_to_link(__DIR__ . '/js/edu-history.min.js', true);
    require(__DIR__ . '/../home1/container.php');
  }

  private function edu_history_exists($reg_no)
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $query = "SELECT COUNT(*) FROM edu_history WHERE reg_no = ?";

    $log->addInfo("About to check if student $reg_no has record of
                   education history with query: $query.");

    $stmt = $db->prepare($query);

    $stmt->execute([$reg_no]);

    return $stmt->fetchColumn();
  }

  public function post()
  {
    $log = get_logger(self::$LOG_NAME);

    $log->addInfo("About to save education history for student with post data:", $_POST);

    $exams = $this->get_exams();

    $post_secondary = $this->get_post_secondary($_POST['post_secondary']);

    $post_secondary_type = $post_secondary ? PDO::PARAM_STR : PDO::PARAM_NULL;

    $reg_no = trim($_POST['reg_no']);

    $pry_edu = json_encode($_POST['pry_edu']);

    $secondary_edu = json_encode($_POST['secondary_sch']);

    $db = get_db();

    $query = "INSERT INTO edu_history (reg_no, pry_edu, secondary_edu,
                                       o_level_scores, post_secondary)
              VALUES (?, ?, ?, ?, ?)";

    $log->addInfo("About to insert education history into database with query: $query");

    try {
      $stmt = $db->prepare($query);

      $stmt->bindValue(1, $reg_no);
      $stmt->bindValue(2, $pry_edu);
      $stmt->bindValue(3, $secondary_edu);
      $stmt->bindValue(4, $exams);
      $stmt->bindValue(5, $post_secondary, $post_secondary_type);

      $stmt->execute();

      $log->addInfo("Education history successfully inserted into database");

      set_student_reg_form_completion_session1(
        'success',
        'Education history successfully saved!');

    } catch (PDOException $e) {

      logPdoException(
        $e, "Error occurred while saving education history", $log
      );

      set_student_reg_form_completion_session1(
        'error',
        'Error occurred while saving education history!');
    }

    $home = STATIC_ROOT . 'student_portal/home/';

    header("Location: {$home}");
    return;
  }

  private function get_post_secondary(array $post)
  {
    foreach ($post as $key => $val) {
      if (trim($val) == false) {
        return null;
      }
    }

    return json_encode($post);
  }

  private function get_exams()
  {

    $exam1 = $_POST['o_level_1'];

    $s1 = $_POST['o_level_1_score'];

    $scores1 = [];

    for ($i = 1; $i <= 9; $i++) {

      if (isset($s1["subject-$i"])) {

        $subject = $s1["subject-$i"];

        $grade = $s1["grade-$i"];

        if ($subject && $grade) {
          $scores1[] = [$subject, $grade];
        }
      }

    }

    $exam1['scores'] = $scores1;

    $exams[] = $exam1;


    if (isset($_POST['o_level_2'])) {

      $exam2 = $_POST['o_level_2'];

      $s2 = $_POST['o_level_2_score'];

      $scores2 = [];

      for ($i = 1; $i <= 9; $i++) {

        if (isset($s2["subject-$i"])) {

          $subject = $s2["subject-$i"];

          $grade = $s2["grade-$i"];

          if ($subject && $grade) {

            $scores2[] = [$subject, $grade];

          }
        }

      }

      $exam2['scores'] = $scores2;

      $exams[] = $exam2;
    }

    return json_encode($exams);

  }
}

$edu = new EduHistoryController;
if ($_SERVER['REQUEST_METHOD'] === 'GET') $edu->get();
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') $edu->post();
