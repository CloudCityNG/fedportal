$(function () {
  "use strict";

  (function navigationController() {
    $('.side-nav>.title').click(function () {
      var sideNav = $(this).parent();

      sideNav
        .siblings()
        .removeClass('expanded')
        .addClass('collapsed')
        .each(function () {
          $(this).children('.links').hide()
            .children('.link').not('.current').removeClass('selected');
        });

      if (sideNav.is('.collapsed')) {
        sideNav
          .removeClass('collapsed')
          .addClass('expanded')
          .children('.links').fadeIn(1000);

      } else {
        sideNav
          .removeClass('expanded')
          .addClass('collapsed')
          .children('.links')
          .fadeOut(100)
          .children('.link').not('.current').removeClass('selected');
      }
    });

    var $allLinks = $('.side-nav>.links>.link');

    $allLinks.click(function () {
      toggleSelection($(this));
    });

    function toggleSelection($link) {
      $allLinks.removeClass('selected');
      $link.addClass('selected');
    }

  })();

  $('.show-date-picker').datepicker({
    format: 'dd-mm-yyyy'
  })
    .on('changeDate', function (evt) {
      var $target = $(evt.target);
      $target.closest('form').formValidation(
        'revalidateField', $target.children('.form-control').prop('name')
      );
    });

});
