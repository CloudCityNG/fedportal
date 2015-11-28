<fieldset>
  <legend>Assign Capabilities</legend>

  <div class="form-group assign-capabilities-group">
    <div class="col-sm-5">
      <label for="select-capabilities" class="control-label">Select Capabilities</label>

      <select id="select-capabilities" class="form-control" multiple <?php echo $disabled; ?>>
        <?php
        $capabilitiesToSelectFromJson = json_encode($capabilitiesToSelectFrom);
        foreach ($capabilitiesToSelectFrom as $capabilityId => $capabilityName) {
          echo "<option value='{$capabilityId}' data-toggle='tooltip'
                    title='{$capabilityName}'>{$capabilityName}</option>";
        }
        ?>
      </select>

      <input type="hidden" id="capabilities-to-select-from" name="capabilities-to-select-from" <?php echo $disabled; ?>
             value='<?php echo $capabilitiesToSelectFromJson; ?>'>
    </div>

    <div class="col-sm-2">
      <?php echo $editIconCapabilities; ?>

      <div class="capability-select-deselect"
           style="display: <?php if ($editProfile) echo 'none'; else echo 'block'; ?>;">

        <div id="select-one-capability" data-toggle="tooltip" title="Select singles"> --></div>

        <div id="select-all-capabilities" data-toggle="tooltip" title="Select all"> -->></div>

        <div id="deselect-one-capability" data-toggle="tooltip" title="Deselect singles"> <--</div>

        <div id="deselect-all-capabilities" data-toggle="tooltip" title="Deselect all"> <<--</div>
      </div>
    </div>

    <div class="col-sm-5">
      <label for="selected-capabilities" class="control-label">Selected Capabilities</label>

      <select id="selected-capabilities" class="form-control" multiple <?php echo $disabled; ?>>
        <?php
        $capabilitiesSelectedJson = null;

        if ($capabilitiesSelected) {
          $capabilitiesSelectedJson = json_encode($capabilitiesSelected);
          foreach ($capabilitiesSelected as $capabilityId => $capabilityName) {
            echo "<option value='{$capabilityId}' data-toggle='tooltip'
                      title='{$capabilityName}'>{$capabilityName}</option>";
          }
        }
        ?>
      </select>

      <input type="hidden" name="capabilities-selected" id="capabilities-selected" <?php echo $disabled; ?>
             value='<?php echo $capabilitiesSelectedJson; ?>'>

      <?php
      if ($editProfile) {
        $arrayKeys = is_array($capabilitiesSelected) ? array_keys($capabilitiesSelected) : null;
        $capabilitiesSelectedOriginalJson = json_encode($arrayKeys);
        echo "<input type='hidden' value='{$capabilitiesSelectedOriginalJson}' id='capabilities-selected-original' />";
      }
      ?>
    </div>
  </div>
</fieldset>
