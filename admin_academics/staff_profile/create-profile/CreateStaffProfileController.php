<?php
require_once(__DIR__ . '/../models/StaffCapability.php');
require_once(__DIR__ . '/../models/StaffCapabilityAssign.php');
require_once(__DIR__ . '/../models/StaffProfile.php');

class CreateStaffProfileController extends StaffProfileController
{
  /**Checks whether post data is valid
   *
   * @param $userName
   * @param $firstName
   * @param $lastName
   * @param $password
   * @param $confirmPassword
   * @return array - 'valid' key tells whether post data is valid. 'messages' key is the information that will be sent
   *   back to client to help user debug post failure
   */
  private static function confirmPost($userName, $firstName, $lastName, $password, $confirmPassword)
  {
    $status = ['valid' => false];

    if (!($userName && $firstName && $lastName && $password && $confirmPassword)) {
      $status['messages'] = ['username, first name, last name, password and confirm password can not be empty.'];
      return $status;
    }

    if ($password !== $confirmPassword) {
      $status['messages'] = ['Password and confirm password are not the same.'];
      return $status;
    }

    if (StaffProfile::staffExists(['username' => $userName])) {
      $status['messages'] = ["username '{$userName}' reserved. Please choose another!"];
      return $status;
    }

    return ['valid' => true];
  }

  /**
   * Assign capabilities to staff
   * @param $staffId - Db ID of staff to whom we want to assign capabilities
   * @param array $capabilitiesSelected - a mapping of capability IDs to their names
   * @return int - 1 means capabilities were successfully assigned and 0 means failure
   */
  private static function assignCapabilities($staffId, array $capabilitiesSelected)
  {
    if(!count($capabilitiesSelected)) return 0;

    $capabilities = [];

    foreach ($capabilitiesSelected as $id => $name) {
      $capabilities[] = [$staffId, $id];
    }

    return StaffCapabilityAssign::create($capabilities);
  }

  public function post()
  {
    $staffProfile = $_POST['staff_profile'];
    $userName = trim($staffProfile['username']);
    $firstName = trim($staffProfile['first_name']);
    $lastName = trim($staffProfile['last_name']);
    $password = trim($staffProfile['password']);
    $confirmPassword = trim($staffProfile['confirm_password']);

    $capabilitiesToSelectFrom = [];
    if (isset($_POST['capabilities-to-select-from'])) {
      $capabilitiesToSelectFrom = json_decode(trim($_POST['capabilities-to-select-from']), true);
    }

    $capabilitiesSelected = [];
    if (isset($_POST['capabilities-selected'])) {
      $capabilitiesSelected = json_decode(trim($_POST['capabilities-selected']), true);
    }
    $context = ['staff_profile' => $staffProfile, 'posted' => false, 'status' => 'Post failed!'];
    $valid = self::confirmPost($userName, $firstName, $lastName, $password, $confirmPassword);

    if (!$valid['valid']) {
      $context['messages'] = $valid['messages'];
      $this->renderPage($context, $capabilitiesToSelectFrom, $capabilitiesSelected);
      return;
    }

    $staff = StaffProfile::createProfile([
      'username' => $userName,
      'first_name' => $firstName,
      'last_name' => $lastName,
      'password' => $password
    ]);

    if (self::assignCapabilities($staff['id'], $capabilitiesSelected)) {
      $staffProfile['capabilities'] = $capabilitiesSelected;
    }

    $this->renderPage([
      'created_staff_profile' => $staffProfile,
      'posted' => true,
      'status' => 'Staff profile successfully created!'
    ]);
  }

  /**
   * @param array $createStaffProfileContext
   * @param array $capabilitiesToSelectFrom
   * @param array $capabilitiesSelected
   */
  public function renderPage(array $createStaffProfileContext = [],
                             array $capabilitiesToSelectFrom = null,
                             array $capabilitiesSelected = null)
  {

    if (!is_array($capabilitiesToSelectFrom)) {
      $capabilitiesToSelectFrom = [];

      foreach (StaffCapability::getAllCapabilities() as $capability) {
        $capabilitiesToSelectFrom[$capability['id']] = $capability['name'];
      }
    }

    $link_template = __DIR__ . '/view.php';
    $pageJsPath = path_to_link(__DIR__ . '/js/create-profile.min.js', true);
    $pageCssPath = path_to_link(__DIR__ . '/css/create-profile.min.css', true);

    require(__DIR__ . '/../../home/container.php');
  }

  private static function logger()
  {
    return get_logger('AssessmentGradeStudentController');
  }
}
