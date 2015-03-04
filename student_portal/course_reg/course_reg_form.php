<form class="courses-tables-form form-horizontal" method="post"
      action="<?php echo $course_reg_post; ?>" data-toggle="validator"
  <?php echo $already_registered ? 'style="display: none;"' : ''; ?>
  >

  <header class="panel-heading no-b">
    <h3 class="text-center">
      Course <b>Sign Up Form:
        <?php echo "$dept_name ($semester" . ($semester == 1 ? 'st' : 'nd') . " Semester)"; ?>
      </b>
    </h3>
  </header>

  <input type="hidden" name="reg_no" value="<?php echo $reg_no; ?>"/>
  <input type="hidden" name="semester" value="<?php echo $semester; ?>"/>
  <input type="hidden" name="academic_year" value="<?php echo $academic_year; ?>"/>
  <input type="hidden" name="dept" value="<?php echo $dept_code; ?>"/>

  <div class="row level">
    <div class="col-sm-10">
      <div class="form-group">
        <label class="control-label col-sm-6" for="level">Level </label>

        <div class="col-sm-4">
          <select class="form-control" name="level" id="level" required=""
                  data-native-error="Please select your level!">
            <option value="">---</option>

            <?php
            foreach (get_academic_levels() as $level_id => $level_code) {
              echo "<option value='$level_code'>$level_code</option>";
            }
            ?>
          </select>
        </div>

        <div class="with-errors help-block"></div>

      </div>
    </div>
  </div>

  <?php $courses_for_semester = get_courses($semester, $dept_code) ?>

  <div class="row courses-tables-ond">
    <div class="col-md-6 col-lg-6 col-sm-6 ond1-table">
      <table class="table table-striped table-condensed table-bordered ond1-table">
        <caption>ND1</caption>

        <thead>
          <tr>
            <th>#</th>
            <th>Course Code</th>
            <th>Course Title</th>
            <th>Credit Unit</th>

            <th>
              <label class="sr-only" for="ond1-check-all">OND 1 Check All Box</label>
              <input type="checkbox" id="ond1-check-all"/>
            </th>
          </tr>
        </thead>

        <tbody>
          <?php
          $course_seq = 1;

          foreach ($courses_for_semester['OND1'] as $courses) {

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

                               <td>
                                  <input class='ond1-check' type='checkbox' name='ond1-check'/>
                               </td>

                               </tr>";

            $course_seq++;
          }
          ?>
        </tbody>
      </table>
    </div>

    <div class="col-md-6 col-lg-6 col-sm-6 ond2-table">
      <table class="table table-striped table-condensed table-bordered ond2-table">
        <caption>ND2</caption>

        <thead>
          <tr>
            <th>#</th>
            <th>Course Code</th>
            <th>Course Title</th>
            <th>Credit Unit</th>

            <th>
              <input type="checkbox" id="ond2-check-all"/>
            </th>
          </tr>
        </thead>

        <tbody>
          <?php
          $course_seq = 1;

          foreach ($courses_for_semester['OND2'] as $courses) {

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
  </div>

  <div class="row courses-tables-hnd">
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
  </div>

  <div class="text-center">
    <input class="btn btn-lg btn-primary" type="submit" value="Sign Up For Courses"/>
  </div>
</form>