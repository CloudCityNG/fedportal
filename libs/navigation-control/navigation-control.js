(function () {
  "use strict";

  function NavigationController() {
    var $element = $(this)
    var $allLinks = $element.find('.link')

    $allLinks.each(function () {
      //var parentSideBarNav = $(this).parents('.side-nav-intermediate').not('.padded')
      //var len = parentSideBarNav.size()
      //
      //if (len) {
      //  console.log(parentSideBarNav)
      //  for (var i = 0; i < len; i++) {
      //    parentSideBarNav.eq(i).addClass('padded').css({
      //      'margin-left': '15px'
      //    })
      //  }
      //}
    })

    $allLinks.click(function () {
      toggleSelection($(this))
    })
    var urlSearch = location.search
    var query = urlSearch.split('&')[0]
    var $currentLink = $('a[href^="' + location.pathname + query + '"]')

    if (query) $currentLink = $currentLink.filter('[href$="' + query + '"]')
    if (!$currentLink.size()) $currentLink = $('a[href="' + location.pathname + urlSearch + '"]')

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
    function emphasizeLinkContainer(sideNav)  {

      sideNav
        .siblings().not('.title').removeClass('expanded')
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
          .children().fadeIn(1000);
      }
    }

    function toggleSelection($link) {
      $allLinks.removeClass('selected');
      $link.addClass('selected');
    }

    return this
  }

  $.fn.kmNavigator = NavigationController
})();
