$(function () {
  "use strict";

  (function navigationController() {
    var $allLinks = $('.side-nav>.links>.link').click(function () {
      toggleSelection($(this));
    })

    var $currentLink = $('[href="' + location.pathname + location.search +'"]')

    if($currentLink.size()) {
      $currentLink.addClass('selected')
      emphasizeLinkContainer($currentLink.closest('.side-nav'))
    }


    $('.side-nav>.title').click(function () {
      var sideNav = $(this).parent();
      emphasizeLinkContainer(sideNav)
    });

    /**
     * When a link is clicked or navigated to, or when one of the link containers is clicked, the container is
     * emphasized with CSS e.g font becomes bigger
     *
     * @param {jQuery} sideNav
     */
    function emphasizeLinkContainer(sideNav){
      sideNav
        .siblings()
        .removeClass('expanded')
        .each(function () {
                $(this).children('.links').hide()
                  .children('.link').not('.current').removeClass('selected');
              });

      if (sideNav.is('.expanded')) {
        sideNav
          .removeClass('expanded')
          .children('.links')
          .fadeOut(100)
          .children('.link').not('.current').removeClass('selected');

      } else {
        sideNav
          .addClass('expanded')
          .children('.links').fadeIn(1000);
      }
    }

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
