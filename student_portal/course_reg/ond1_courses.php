<div class="col-md-6 col-lg-6 col-sm-6 ond1-table">
  <table class="table table-striped table-condensed table-bordered ond1-table">
    <caption>ND I</caption>

    <thead>
    <tr>
      <th>#</th>
      <th>Course Code</th>
      <th>Course Title</th>
      <th>Credit Unit</th>

      <th>
        <label class="sr-only" for="ond1-check-all">ND 1 Check All Box</label>
        <input type="checkbox" id="ond1-check-all"/>
      </th>
    </tr>
    </thead>

    <tbody>
    <?php
    $course_seq = 1;

    foreach ($courses_for_semester['ND I'] as $courses) {

      $code = $courses['code'];

      $title = $courses['title'];

      $unit = sprintf('%.2f', $courses['unit']);

      $id = $courses['id'];

      echo "<tr>\n
                  <input type='hidden' name='course_reg[$id]' value='$id' disabled />

                  <td>$course_seq</td>\n
                  <td>$code</td>\n
                  <td>$title</td>\n
                  <td>$unit</td>\n
                  <td><input class='ond1-check' type='checkbox' name='ond1-check'/></td>
               </tr>";

      $course_seq++;
    }
    ?>
    </tbody>
  </table>
</div>
