"use strict";

var $selectCapability = $('#select-capabilities')
var $selectedCapability = $('#selected-capabilities')
var $capabilitiesToSelectFrom = $('#capabilities-to-select-from')
var $capabilitiesSelected = $('#capabilities-selected')

function updateSelections(){
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
}

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
