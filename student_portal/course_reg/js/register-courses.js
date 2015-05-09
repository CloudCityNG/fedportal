$(function () {
  "use strict";

  $('.courses-list').hide();
  $('.courses-list.show').show();

  $('li > a[href*=nd]').click(function () {
    var $link = $(this),
        $input = $link.closest('.courses-list').find('[name^=student-level-]');
    $input.val($link.text());
  });

  $.fn.checkOneAll = function (checkOneSelector, checkAllSelector) {
    $(this).on({
      'change input': function () {
        $(checkOneSelector).not('.no-check').prop('checked', $(this).prop('checked')).trigger('change');
      }
    }, checkAllSelector);

    $(this).on({
      'change input': function () {
        var $checkAll, $checkOne;

        $checkOne = $(checkOneSelector);

        $checkAll = $(checkAllSelector);

        if ($(this).prop('checked') === false) {
          $checkAll.prop('checked', false);

        } else {
          $checkAll.prop('checked', $checkOne.filter(':checked').size() === $checkOne.size());

        }
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

  }, 'input[type=checkbox]');

  (function printerFriendly() {
    var $header = $('header'),
        $sectionLayout = $('section.layout'),
        $backToMain = $('.back-to-main'),
        $printerFriendly = $('.printer-friendly'),
        formerPadding;

    $printerFriendly.click(function () {
      $(this).parent().hide();

      formerPadding = $sectionLayout.css('padding');

      $header.hide();
      $sectionLayout.css('padding', 0);

      $backToMain.show();
    });

    $backToMain.click(function () {
      $(this).hide();

      $printerFriendly.parent().show();

      $header.show();
      $sectionLayout.css('padding', formerPadding);
    });

  })();

});
