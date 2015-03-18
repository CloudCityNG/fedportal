<div class="col-md-6 col-lg-6 col-sm-6 hnd1-container">
  <table class="table table-striped table-condensed table-bordered hnd1-table">
    <caption>HND1</caption>

    <thead>
    <tr>
      <th>#</th>
      <th>Course Code</th>
      <th>Course Title</th>
      <th>Credit Unit</th>

      <th>
        <input type="checkbox" id="hnd1-check-all"/>
      </th>
    </tr>
    </thead>

    <tbody>
    <?php

    $course_seq = 1;

    foreach ($courses_for_semester['HND1'] as $courses) {

      $code = $courses['code'];

      $title = $courses['title'];

      $id = $courses['id'];

      $unit = sprintf('%.2f', $courses['unit']);

      echo "<tr>\n

                                <input type='hidden' name='course_reg[$id]' value='$id' disabled />

                                <td>$course_seq</td>\n

                                <td>$code</td>\n

                                <td>$title</td>\n

                                <td>$unit</td>\n

                                <td>
                                    <input class='hnd1-check' type='checkbox' name='hnd1-check'/>
                                </td>

                                </tr>";

      $course_seq++;
    }
    ?>
    </tbody>
  </table>
</div>
