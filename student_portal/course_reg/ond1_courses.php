<div class="ond1-table">
  <table class="table table-striped table-condensed table-bordered ond1-table">
    <caption>ND I</caption>

    <thead>
      <tr>
        <th>
          <label class="sr-only" for="ond1-check-all">ND 1 Check All Box</label>
          <input type="checkbox" id="ond1-check-all" disabled/>
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

      foreach ($courses_for_semester['ND I'] as $courses) {
        $unit = sprintf('%.2f', $courses['unit']);

        echo "<tr>
                <input type='hidden' name='course_reg[{$courses['id']}]' value='{$courses['id']}' disabled />
                <td><input class='ond1-check' type='checkbox' name='ond1-check' disabled /></td>
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
