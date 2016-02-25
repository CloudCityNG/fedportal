(function () {
  "use strict";

  function NavigationController() {
    var $element = $(this)
    var $allLinks = $element.find('.side-nav .links>.link').click(function () {
      toggleSelection($(this))
    })
    var query = location.search.split('&')[0]
    var $currentLink = $('[href^="' + location.pathname + query + '"]')

    if (query) $currentLink = $currentLink.filter('[href$="' + query + '"]')

    if ($currentLink.size()) {
      $currentLink.addClass('selected')
      emphasizeLinkContainer($currentLink.closest('.side-nav'))
    }


    $('.side-nav .title').click(function () {
      emphasizeLinkContainer($(this).parent())
    });

    /**
     * When a link is clicked or navigated to, or when one of the link containers is clicked, the container is
     * emphasized with CSS e.g font becomes bigger
     *
     * @param {jQuery} sideNav
     */
    function emphasizeLinkContainer(sideNav) {
      sideNav
        .siblings()
        .removeClass('expanded')
        .each(function () {
          $(this).children().not('.title').hide()
            .children('.link').not('.current').removeClass('selected')
        })

      if (sideNav.is('.expanded')) {
        sideNav
          .removeClass('expanded')
          .children().not('.title')
          .fadeOut(100)
          .children('.link').not('.current').removeClass('selected');

      } else {
        sideNav
          .addClass('expanded')
          .children('.links, .side-nav-intermediate').fadeIn(1000);
      }
    }

    function toggleSelection($link) {
      $allLinks.removeClass('selected');
      $link.addClass('selected');
    }

  }

  $.fn.kmNavigator = NavigationController
})();
