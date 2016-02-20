<div class="jumbotron registration-statuses">
  <div class="legend h3"> Registration Status</div>

  <?php
  foreach ($studentDashboardHomeContext as $status => $data) {
    echo "<div class='h3 alert {$data['alert_class']}'>
            <a href='{$data['link']}'>{$data['text']}</a>
         </div>";
  }
  ?>
</div>
