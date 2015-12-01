<?php
require_once(__DIR__ . '/../models/StaffCapability.php');
require_once(__DIR__ . '/../models/StaffCapabilityAssign.php');
require_once(__DIR__ . '/../models/StaffProfile.php');

class CreateStaffProfileController extends StaffProfileController
{
  private static function checkUserNameUniqueness($userName)
  {
    if (StaffProfile::staffExists(['username' => $userName])) {
      return ["username '{$userName}' reserved. Please choose another!"];
    }

    return null;
  }

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

    $uniqueness = self::checkUserNameUniqueness($userName);
    if ($uniqueness) {
      $status['messages'] = $uniqueness;
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
    if (!count($capabilitiesSelected)) return 0;

    StaffCapabilityAssign::deleteCapabilities(['staff_profile_id' => $staffId]);
    $capabilities = [];

    foreach ($capabilitiesSelected as $id => $name) {
      $capabilities[] = [$staffId, $id];
    }

    return StaffCapabilityAssign::create($capabilities);
  }

  private function edit()
  {
    $context = ['posted' => false, 'status' => 'Edit profile failed!'];
    $profile = json_decode($_POST['staff_profile_data'], true);
    $postData = [];

    if (isset($_POST['staff_profile'])) {
      $staffProfile = $_POST['staff_profile'];

      if (isset($staffProfile['username'])) {
        $userName = trim($staffProfile['username']);

        if ($userName) {
          $uniqueness = self::checkUserNameUniqueness($userName);

          if ($uniqueness) {
            $context['messages'] = $uniqueness;
            //:TODO reject with post error
          }

          $profile['username'] = $userName;
          $postData['username'] = $userName;
        }
      }

      if (isset($staffProfile['first_name'])) {
        $firstName = trim($staffProfile['first_name']);

        if ($firstName) {
          $profile['first_name'] = $firstName;
          $postData['first_name'] = $firstName;
        }
      }

      if (isset($staffProfile['last_name'])) {
        $lastName = trim($staffProfile['last_name']);

        if ($lastName) {
          $profile['last_name'] = $lastName;
          $postData['last_name'] = $lastName;
        }
      }

      if (isset($staffProfile['password'])) {
        $password = trim($staffProfile['password']);

        if ($password) {
          $profile['password'] = $password;
          $postData['password'] = $password;
        }
      }
    }

    $profileId = $profile['id'];
    if (count($postData)) StaffProfile::updateProfile($postData, ['id' => $profileId]);

    $capabilitiesSelected = [];
    if (isset($_POST['capabilities-selected'])) {
      $capabilitiesSelected = json_decode(trim($_POST['capabilities-selected']), true);
    }

    if (is_array($capabilitiesSelected) && self::assignCapabilities($profileId, $capabilitiesSelected)) {
      $profile['capabilities'] = $capabilitiesSelected;
    }

    self::setPostSession(true, 'Staff profile successfully updated!', ['created_staff_profile' => $profile], true);
  }

  public function post()
  {
    if (isset($_POST['staff_profile_data'])) {
      $this->edit();
      return;
    }

    $staffProfile = $_POST['staff_profile'];
    $userName = trim($staffProfile['username']);
    $firstName = strtoupper(trim($staffProfile['first_name']));
    $lastName = strtoupper(trim($staffProfile['last_name']));
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

    $context = ['staff_profile' => $staffProfile];
    $valid = self::confirmPost($userName, $firstName, $lastName, $password, $confirmPassword);

    if (!$valid['valid']) {
      $context['messages'] = $valid['messages'];
      self::setPostSession(false, 'Profile creation failed!', $context);
      $this->renderPage($context, $capabilitiesToSelectFrom, $capabilitiesSelected);
      return;
    }

    $staff = StaffProfile::createProfile([
      'username' => $userName,
      'first_name' => $firstName,
      'last_name' => $lastName,
      'password' => $password
    ]);

    if (is_array($capabilitiesSelected) && self::assignCapabilities($staff['id'], $capabilitiesSelected)) {
      $staffProfile['capabilities'] = $capabilitiesSelected;
    }

    self::setPostSession(true, 'Staff profile successfully created!', ['created_staff_profile' => $staffProfile], true);
  }

  /**
   * Get staff ID from the url query parameter
   * @param $query - the query has been exploded into an array so that "create-profile&staff_id=1" is
   *    [create-profile, staff_id=1]
   * @return null|int - returns staff ID if present in URL otherwise null
   */
  private static function getStaffIdFromQuery($query)
  {
    $staffIdQueryRegexp = "/^staff_id=(\d+)$/";
    $staffId = null;

    foreach ($query as $item) {
      if (preg_match($staffIdQueryRegexp, $item, $matches) === 1) {
        $staffId = $matches[1];
        break;
      }
    }

    return $staffId;
  }

  /**
   * Get staff profile from DB and also get capabilities that have been assigned to the staff
   * @param $staffId - the database ID of staff whose profile we seek
   * @param array $capabilitiesToSelectFrom - This is an array of all possible capabilities in the database in the form
   *    [capabilityID => capabilityName]
   * @return array|null - return null if staff profile not found otherwise return an array in the form:
   *    [staffProfile, $capabilitiesToSelectFrom, selectedCapabilities]
   *          $capabilitiesToSelectFrom is the same as the one passed into this method with all capabilities already
   *              assigned to staff removed
   *          selectedCapabilities - if capabilities had been assigned to staff, then this is the array of such
   *            capabilities otherwise this entry will be null
   */
  private static function getStaffProfile($staffId, array $capabilitiesToSelectFrom)
  {
    $staff = StaffProfile::getStaff(['id' => $staffId]);

    if ($staff) {
      $staff = $staff[0];
      $capabilities = StaffCapabilityAssign::getCapabilities(['staff_profile_id' => $staffId]);
      $selectedCapabilities = [];

      if ($capabilities) {
        foreach ($capabilities as $row) {
          $id = $row['staff_capability_id'];
          $selectedCapabilities[$id] = $capabilitiesToSelectFrom[$id];
          unset($capabilitiesToSelectFrom[$id]);
        }
      }

      return [$staff, $capabilitiesToSelectFrom, count($selectedCapabilities) ? $selectedCapabilities : null];
    }

    return null;
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

    if (isset($createStaffProfileContext['query'])) {
      $staffId = self::getStaffIdFromQuery($createStaffProfileContext['query']);

      if ($staffId) {
        $staff = self::getStaffProfile($staffId, $capabilitiesToSelectFrom);
        if ($staff) {
          $createStaffProfileContext['staff_profile'] = $staff[0];
          $capabilitiesToSelectFrom = $staff[1];
          $capabilitiesSelected = $staff[2];
          $createStaffProfileContext['edit'] = true;
        }
      }
    }

    $link_template = __DIR__ . '/view.php';
    $pageJsPath = path_to_link(__DIR__ . '/js/create-profile.min.js', true);
    $pageCssPath = path_to_link(__DIR__ . '/css/create-profile.min.css', true);

    require(__DIR__ . '/../../home/container.php');
  }

  private static function logger()
  {
    return get_logger('CreateStaffProfileController');
  }

  private static function setPostSession($posted, $status, array $context, $redirect = false)
  {
    $_SESSION['CREATE-STAFF-PROFILE-POST-KEY'] = json_encode(
      array_merge(['posted' => $posted, 'status' => $status], $context)
    );

    if ($redirect) header("Location: " . path_to_link(__DIR__ . '/..') . '?create-profile');
  }
}
