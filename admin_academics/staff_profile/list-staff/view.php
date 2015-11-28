<table class="table table-striped table-condense table-bordered staff-list-table">
  <thead>
    <tr>
      <th>S/N</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Username</th>
      <th></th>
    </tr>
  </thead>

  <tbody>
    <?php
    $count = 1;

    foreach ($listStaffContext['staff_list'] as $staff) {
      $staffLink = '';
      if(UserSession::isCapable('can_edit_staff_profile')){
        $staffLink = path_to_link(__DIR__ . '/..') . "?create-profile&staff_id={$staff['id']}";
      }

      echo "
      <tr>
        <td style='text-align: right'>{$count}</td>
        <td>{$staff['first_name']}</td>
        <td>{$staff['last_name']}</td>
        <td>{$staff['username']}</td>
        <td>
          <a href='{$staffLink}' class='glyphicon glyphicon-pencil'></a>
        </td>
      </tr>
      ";
      $count++;
    }
    ?>
  </tbody>
</table>
