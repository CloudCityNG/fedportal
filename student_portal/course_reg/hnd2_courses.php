<div class="col-md-6 col-lg-6 col-sm-6 hnd2-container">
  <table class="table table-striped table-condensed table-bordered hnd2-table">
    <caption>HND2</caption>

    <thead>
    <tr>
      <th>#</th>
      <th>Course Code</th>
      <th>Course Title</th>
      <th>Credit Unit</th>

      <th>
        <label class="sr-only" for="hnd2-check-all"></label>
        <input type="checkbox" id="hnd2-check-all"/>
      </th>
    </tr>
    </thead>

    <tbody>
    <?php
    $course_seq = 1;

    foreach ($courses_for_semester['HND2'] as $courses) {

      $code = $courses['code'];

      $title = $courses['title'];

      $id = $courses['id'];

      $unit = sprintf('%.2f', $courses['unit']);

      echo "<tr>

                               <input type='hidden' name='course_reg[$id]' value='$id' disabled />

                               <td>$course_seq</td>

                               <td>$code</td>

                               <td>$title</td>

                               <td>$unit</td>

                               <td><input class='hnd2-check' type='checkbox' name='hnd2-check'/> </td>

                               </tr>";

      $course_seq++;
    }
    ?>
    </tbody>
  </table>
</div>
