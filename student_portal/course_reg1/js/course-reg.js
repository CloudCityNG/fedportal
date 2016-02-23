$(function () {
  "use strict";

  var checkBoxSelector = 'input[type=checkbox]'
  var $checkboxes = $(checkBoxSelector)
  var $submitBtn = $('#course-form-submit')
  var ndCourseTable = $('.courses-tables-ond')
  var hndCourseTable = $('.courses-tables-hnd')

  $('#courses-tables-form').submit(function (evt) {
    if ($submitBtn.prop('disabled') || ($checkboxes.not('[id$=-check-all]').filter(':checked').size() < 3)) {
      window.alert('Please select enough courses!')
      evt.preventDefault()
    }
  })

  $('#level').change(function () {
    var val = $(this).val()

    if (val) {
      $checkboxes.prop('disabled', false)
      $submitBtn.prop('disabled', false).show()

      if (val.indexOf('N') === 0) {
        ndCourseTable.show()
        hndCourseTable.hide()

      } else {
        ndCourseTable.hide()
        hndCourseTable.show()
      }

    } else {
      $checkboxes.prop('disabled', true).prop('checked', false)
      $submitBtn.prop('disabled', true).hide()
      ndCourseTable.hide()
      hndCourseTable.hide()
    }
  })

  $.fn.checkOneAll = function (checkOneSelector, checkAllSelector) {
    $(this).on({
      'change input': function () {
        $(checkOneSelector).not('.no-check').prop('checked', $(this).prop('checked')).trigger('change')
      }
    }, checkAllSelector);

    $(this).on({
      'change input': function () {
        var $checkOne = $(checkOneSelector)
        var $checkAll = $(checkAllSelector)
        var $el = $(this)
        var parentTr = $el.closest('tr')

        if (!$el.prop('checked')) {
          $checkAll.prop('checked', false)
          parentTr.removeClass('selected')
        }
        else $checkAll.prop('checked', $checkOne.filter(':checked').size() === $checkOne.size())

        if ($el.prop('checked')) parentTr.addClass('selected')
      }
    }, checkOneSelector);
    return this;
  };

  $('table.ond1-table').checkOneAll('.ond1-check', '#ond1-check-all');
  $('table.ond2-table').checkOneAll('.ond2-check', '#ond2-check-all');
  $('table.hnd1-table').checkOneAll('.hnd1-check', '#hnd1-check-all');
  $('table.hnd2-table').checkOneAll('.hnd2-check', '#hnd2-check-all');

  $('tbody').on({

    'change input': function () {
      var $el = $(this);
      $el.closest('tr').find('input[type=hidden]').prop('disabled', !$el.prop('checked'));
    }

  }, checkBoxSelector);

});
