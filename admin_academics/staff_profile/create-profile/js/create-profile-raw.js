$(function () {
  "use strict";

  var $capabilitiesToSelectFrom = $('#capabilities-to-select-from')
  var $capabilitiesSelected = $('#capabilities-selected')
  var $selectCapability = $('#select-capabilities')
  var $selectedCapability = $('#selected-capabilities')
  var $staffProfile = $('#staff-profile-data')
  var staffProfileData = JSON.parse($staffProfile.val() || '{}')
  var $checkProfileData = $('.check-original-profile-data')
  var selectedOriginal = JSON.parse($('#capabilities-selected-original').val() || 'null');

  (function capabilitySelectionFn() {
    $('#select-one-capability').click(function () {
      var $selected = $selectCapability.find('option:selected')
      $selectedCapability.append($selected.clone())
      $selected.remove()
      updateSelections()
    })

    $('#deselect-one-capability').click(function () {
      var $selected = $selectedCapability.find('option:selected')
      $selectCapability.append($selected.clone())
      $selected.remove()
      updateSelections()
    })

    $('#select-all-capabilities').click(function () {
      var $selected = $selectCapability.find('option')
      $selectedCapability.append($selected.clone())
      $selected.remove()
      updateSelections()
    })

    $('#deselect-all-capabilities').click(function () {
      var $selected = $selectedCapability.find('option')
      $selectCapability.append($selected.clone())
      $selected.remove()
      updateSelections()
    })
  })();

  $('#staff-profile-create-form').formValidation()

  $('.toggle-form-control-edit').click(function () {
    var $el = $(this);

    if ($el.is('.glyphicon-pencil')) {
      $el.hide().next().show().closest('.input-group').children('.form-control').prop('disabled', false);

    } else {
      var $formControl = $el.closest('.input-group').children('.form-control').first().prop('disabled', true)
      var propName = getName($formControl.prop('name'))

      if (!_.contains(['password', 'confirm_password'], propName)) $formControl.val(staffProfileData[propName])
      else $formControl.val('')

      var $formGrp = $formControl.parents('.form-group').removeClass('has-success').removeClass('has-error')
      $formGrp.children('.form-control-feedback').hide()
      $formGrp.children('.help-block').hide()
      $el.hide().prev().show()
      enableSubmit()
    }
  });

  (function toggleCapabilitiesEdit() {
    var $capabilitiesArrow = $('.capability-select-deselect');
    var $allOptions = $('.assign-capabilities-group option').clone()

    $('.toggle-capabilities-edit').click(function () {
      var $el = $(this);

      if ($el.is('.glyphicon-pencil')) {
        $el.hide().next().show()
        $capabilitiesArrow.show()
        $selectCapability.prop('disabled', false)
        $selectedCapability.prop('disabled', false)
        $capabilitiesToSelectFrom.prop('disabled', false)
        $capabilitiesSelected.prop('disabled', false)

      } else {
        $el.hide().prev().show()
        $capabilitiesArrow.hide()
        $selectCapability.prop('disabled', true)
        $selectedCapability.prop('disabled', true)
        $capabilitiesToSelectFrom.prop('disabled', true)
        $capabilitiesSelected.prop('disabled', true)
        $selectCapability.find('option').remove()
        $selectedCapability.find('option').remove()

        if (!selectedOriginal) $selectCapability.append($allOptions)
        else {
          $allOptions.each(function () {
            var $el = $(this).attr('selected', false)
            if (_.contains(selectedOriginal, Number($el.val())))$selectedCapability.append($el)
            else $selectCapability.append($el)
          })
        }

        updateSelections()
      }
    })
  })();

  $checkProfileData.blur(function () {
    enableSubmit()
  })

  function enableSubmit() {
    if(!$staffProfile.size()) return;

    var disabled = true
    var $submitBtn = $('#submit-btn')

    $checkProfileData.each(function () {
      var $el = $(this)

      if ($el.prop('disabled')) return

      var val = $el.val().trim()
      if (!val) return

      var name = getName($el.prop('name'))
      var originalVal = staffProfileData[name]
      var $toggler = $el.closest('.input-group').find('.toggle-form-control-edit:visible')

      if (name === 'username') {
        if (val !== originalVal) disabled = false
        else $toggler.click()
      }

      if ((name === 'first_name' || name === 'last_name')) {
        var upperVal = val.toUpperCase()
        if (upperVal !== originalVal.toUpperCase()) {
          disabled = false
          $el.val(upperVal)

        } else $toggler.click()
      }
    })

    var original = selectedOriginal || []
    var options = $selectedCapability.find('option')

    if (options.size() !== original.length) disabled = false
    else {
      options.each(function () {
        if (!_.contains(original, Number($(this).val()))) disabled = false
      })
    }

    $submitBtn.prop('disabled', disabled)
  }

  function getName(propName) {
    var NAME_RE = new RegExp("\\[(\\w+)\\]$")
    return NAME_RE.exec(propName)[1]
  }

  function updateSelections() {
    var capabilitiesToSelectFrom = {}
    var capabilitiesSelected = {}

    $selectedCapability.find('option').each(function () {
      var $el = $(this)
      capabilitiesSelected[$el.val()] = $el.text()
    })
    $capabilitiesSelected.val(JSON.stringify(capabilitiesSelected))

    $selectCapability.find('option').each(function () {
      var $el = $(this)
      capabilitiesToSelectFrom[$el.val()] = $el.text()
    })
    $capabilitiesToSelectFrom.val(JSON.stringify(capabilitiesToSelectFrom))
    enableSubmit()
  }
})
