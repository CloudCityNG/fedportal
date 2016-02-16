<?php
require_once(__DIR__ . '/../models/StaffProfile.php');

class ListStaffController extends StaffProfileController
{

  private static function logger()
  {
    return get_logger('ListStaffController');
  }

  public function get()
  {
    $listStaffContext = [
      'staff_list' => StaffProfile::getStaff()
    ];
    $link_template = __DIR__ . '/view.php';

    $pageCssPath = path_to_link(__DIR__ . '/css/publish-results.min.css', true);

    require(__DIR__ . '/../../home/container.php');
  }
}
