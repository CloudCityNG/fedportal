<div class="hnd2-container">
  <table class="table table-striped table-condensed table-bordered hnd2-table">
    <caption>HND II</caption>

    <thead>
      <tr>
        <th>#</th>
        <th>Course Code</th>
        <th>Course Title</th>
        <th>Credit Unit</th>

        <th>
          <label class="sr-only" for="hnd2-check-all">HND 2 CHECK ALL</label>
          <input type="checkbox" id="hnd2-check-all" disabled/>
        </th>
      </tr>
    </thead>

    <tbody>
      <?php
      $courseSeq = 1;

      foreach ($courses_for_semester['HND II'] as $courses) {
        $unit = sprintf('%.2f', $courses['unit']);

        echo "<tr>
              <input type='hidden' name='course_reg[{$courses['id']}]' value='{$courses['id']}' disabled />
              <td>{$courseSeq}</td>
              <td>{$courses['code']}</td>
              <td>{$courses['title']}</td>
              <td>{$unit}</td>
              <td><input class='hnd2-check' type='checkbox' name='hnd2-check' disabled /> </td>
            </tr>";

        $courseSeq++;
      }
      ?>
    </tbody>
  </table>
</div>
