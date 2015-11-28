<?php
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../login/auth.php');
require(__DIR__ . '/create-profile/CreateStaffProfileController.php');
require(__DIR__ . '/list-staff/ListStaffController.php');

AdminAcademicsAuth::checkCapability('can_view_staff_profile');

class StaffProfileController
{

  /**
   * When a generic error is caught in a code block, log that error
   *
   * @param Exception $e
   * @param \Monolog\Logger $logger
   * @param string $message
   */
  protected static function logGeneralError(Exception $e, Monolog\Logger $logger, $message = '')
  {
    $logger->addInfo('Unknown Error: ' . $message);
    $logger->addInfo('Unknown Error: ' . $e->getMessage());
  }
}

$query = explode('&', $_SERVER['QUERY_STRING']);

switch ($query[0]) {

  case 'list-staff': {
    AdminAcademicsAuth::checkCapability('can_list_staff_profile');
    $staffList = new ListStaffController();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $staffList->get();

    }
    break;
  }

  case 'create-profile': {
    AdminAcademicsAuth::checkCapability('can_create_staff_profile');
    $capabilityAssigner = new CreateStaffProfileController();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $capabilityAssigner->renderPage(['query' => $query]);

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $capabilityAssigner->post();
    }
    break;
  }

  default:
    $home = path_to_link(__DIR__ . '/../home');
    header("Location: {$home}");
}
