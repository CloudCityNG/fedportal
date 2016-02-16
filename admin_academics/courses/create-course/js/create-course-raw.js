$(function () {
  "use strict";

  var $checkCourseData = $('.check-original-course-data')
  var $currentCourse = $('#current-course-data')
  var currentCourseData = JSON.parse($currentCourse.val() || '{}')

  $('#course-create-form').formValidation()

  $checkCourseData.blur(function () {
    enableSubmit()
  })

  function enableSubmit() {
    if(!$currentCourse.size()) return

    var disabled = true
    var $submitBtn = $('#submit-btn')

    $checkCourseData.each(function () {
      var $el = $(this)

      if ($el.prop('disabled')) return

      var val = $el.val().trim()
      if (!val) return

      var name = getName($el.prop('name'))
      var originalVal = currentCourseData[name]
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

    $submitBtn.prop('disabled', disabled)
  }

  function getName(propName) {
    var NAME_RE = new RegExp("\\[(\\w+)\\]$")
    return NAME_RE.exec(propName)[1]
  }
})

