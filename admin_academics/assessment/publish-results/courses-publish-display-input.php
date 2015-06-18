<hr/>

<div class="courses-publish-form-container">

  <form id="courses-publish-form"
        class="form-horizontal courses-publish-form"
        method="post"
        role="form"
        data-fv-trigger="blur"
        data-fv-framework="bootstrap"
        data-fv-message="This value is not valid"
        data-fv-icon-valid="glyphicon glyphicon-ok"
        data-fv-icon-invalid="glyphicon glyphicon-remove"
        data-fv-icon-validating="glyphicon glyphicon-refresh">

    <input type="hidden" name="semester_id" value="<?php echo $coursesToClient['semester_id']; ?>"/>

    <fieldset>
      <legend>
        Courses and scores to publish: <span style="color: #8F9FCE;">"<?php echo $coursesToClient['semester'] ?>"</span>
        <br/>
        Check/uncheck to publish/unpublish a score.
      </legend>

      <table class="table table-striped table-condense table-bordered courses-publish-form-table">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Code</th>
            <th>Title</th>
            <th>Already Published?</th>
            <th>Publish</th>
          </tr>
        </thead>

        <tbody>
          <?php
          $count = 1;

          foreach ($coursesToClient['courses'] as $course) {
            $id = $course['id'];
            $published = $course['publish'];
            $publishedIconClass = $published ? 'ok' : 'remove';
            $publishedClass = $published ? 'published' : '';
            $publishedChecked = $published ? 'checked' : '';
            $publishedClickable = $published ? "onclick='return false' onkeydown='return false'" : '';

            $editTrigger = $published ?
              "<span class='glyphicon glyphicon-edit publish-edit-trigger  publish-trigger'></span>" : '';

            echo "
            <tr>
                <td>{$count}</td>
                <td>{$course['code']}</td>
                <td>{$course['title']}</td>

                <td align='center' class='already-published'>
                  <span class='glyphicon glyphicon-{$publishedIconClass}'></span>
                </td>

                <td>
                  <input type='checkbox' name='course_id[{$id}]' {$publishedChecked}
                    {$publishedClickable} class='{$publishedClass}'/>

                  {$editTrigger}
                  <span style='display: none;' class='glyphicon glyphicon-eye-open publish-view-only publish-trigger'></span>
                </td>
            </tr>
           ";
            $count++;
          }

          $coursesToClientDataJSONEncoded = json_encode($coursesToClient);
          echo "<input type='hidden' value='{$coursesToClientDataJSONEncoded}' name='courses-data' />";
          ?>
        </tbody>
      </table>
    </fieldset>

    <div class="form-group">
      <div class="col-sm-5 col-sm-offset-4">
        <div class="btn-group">
          <button class="btn btn-success" type="submit" name="courses-publish-form-submit">Submit</button>
          <button class="btn btn-default" type="button" id="courses-publish-form-form-reset-btn">Reset</button>
        </div>
      </div>
    </div>
  </form>
</div>
