<div class="hnd1-container">
  <table class="table table-striped table-condensed table-bordered hnd1-table">
    <caption>HND I</caption>

    <thead>
      <tr>
        <th>
          <label class="sr-only" for="hnd1-check-all">HND 1 CHECK ALL</label>
          <input type="checkbox" id="hnd1-check-all" disabled />
        </th>

        <th>#</th>
        <th>Course Code</th>
        <th>Course Title</th>
        <th>Credit Unit</th>
      </tr>
    </thead>

    <tbody>
      <?php

      $courseSeq = 1;

      foreach ($courses_for_semester['HND I'] as $courses) {
        $unit = sprintf('%.2f', $courses['unit']);

        echo "<tr>
                <input type='hidden' name='course_reg[{$courses['id']}]' value='{$courses['id']}' disabled />
                <td><input class='hnd1-check' type='checkbox' name='hnd1-check' disabled /></td>
                <td>{$courseSeq}</td>
                <td>{$courses['code']}</td>
                <td>{$courses['title']}</td>
                <td>{$unit}</td>
              </tr>";

        $courseSeq++;
      }
      ?>
    </tbody>
  </table>
</div>
