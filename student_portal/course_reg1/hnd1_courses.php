<div class="col-md-6 col-lg-6 col-sm-6 hnd1-container">
  <table class="table table-striped table-condensed table-bordered hnd1-table">
    <caption>HND I</caption>

    <thead>
    <tr>
      <th>
        <label class="sr-only" for="hnd1-check-all">HND 1 CHECK ALL</label>
        <input type="checkbox" id="hnd1-check-all"/>
      </th>

      <th>#</th>
      <th>Course Code</th>
      <th>Course Title</th>
      <th>Credit Unit</th>
    </tr>
    </thead>

    <tbody>
    <?php

    $course_seq = 1;

    foreach ($courses_for_semester['HND I'] as $courses) {

      $code = $courses['code'];

      $title = $courses['title'];

      $id = $courses['id'];

      $unit = sprintf('%.2f', $courses['unit']);

      echo "<tr>
              <input type='hidden' name='course_reg[$id]' value='$id' disabled />
              <td><input class='hnd1-check' type='checkbox' name='hnd1-check'/></td>
              <td>$course_seq</td>
              <td>$code</td>
              <td>$title</td>
              <td>$unit</td>
            </tr>";

      $course_seq++;
    }
    ?>
    </tbody>
  </table>
</div>
