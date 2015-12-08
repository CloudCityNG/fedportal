<div class="col-md-6 col-lg-6 col-sm-6 ond2-table">
  <table class="table table-striped table-condensed table-bordered ond2-table">
    <caption>ND II</caption>

    <thead>
    <tr>
      <th>#</th>
      <th>Course Code</th>
      <th>Course Title</th>
      <th>Credit Unit</th>

      <th>
        <label class="sr-only" for="ond2-check-all">ND 2 Check All Box</label>
        <input type="checkbox" id="ond2-check-all"/>
      </th>
    </tr>
    </thead>

    <tbody>
    <?php
    $course_seq = 1;

    foreach ($courses_for_semester['ND II'] as $courses) {

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
                                  <input class='ond2-check' type='checkbox' name='ond2-check'/>
                               </td>

                               </tr>";

      $course_seq++;
    }
    ?>
    </tbody>
  </table>
</div>
