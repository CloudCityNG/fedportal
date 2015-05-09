$(function () {
  "use strict";

  var $form = $('form');
  //$form.validator({delay: 0});

  $('.form-control').each(function () {
    $(this).closest('.form-group').append(
      $('<div class="help-block with-errors"></div>')
    );
  });

  var subjects = [
        "PHYSICS",
        "CHEMISTRY",
        "ENGLISH LANGUAGE",
        "MATHEMATICS",
        "FURTHER MATHEMATICS",
        "BIOLOGY",
        "AGRICULTURAL SCIENCE",
        "COMMERCE",
        "ECONOMICS",
        "ISLAMIC RELIGIOUS KNOWLEDGE",
        "IGBO LANGUAGE",
        "FRENCH",
        "YORUBA LANGUAGE",
        "PHYSICAL EDUCATION",
        "ACCOUNT",
        "GEOGRAPHY",
        "LITERATURE IN ENGLISH",
        "YORUBA LITERATURE",
        "HAUSA",
        "CHRISTIAN RELIGIOUS KNOWLEDGE",
        "HISTORY",
        "ARABIC",
        "IGBO LITERATURE",
        "HEALTH EDUCATION",
        "HOME MANAGEMENT",
        "GOVERNMENT"
      ],
      grades = [
        "A1",
        "B2",
        "B3",
        "C4",
        "C5",
        "C6",
        "D7",
        "E8",
        "F9"
      ];

  (function subjectGradeAutoComplete() {

    $('[name^=o_level_][name*=subject]').autocomplete({
      source: subjects
    });

    $('[name^=o_level_][name*=grade]').autocomplete({
      source: grades
    });
  })();

  (function oLevelSitting2ShowHide() {
    var $oLevel2 = $('.o-level-exams-scores-2');

    $oLevel2
      .hide()
      .find('input').each(function () {
        $(this).prop('disabled', true);
      });

    $('.add-another-o-level').click(function () {

      var $el = $(this),
          addHtml = '<span class="add-link text-info"> Add another O level (if 2 sittings)</span>',
          deleteHtml = '<span class="delete-icon text-warning">  Remove 2nd sitting</span>';

      if ($oLevel2.is(':visible')) {

        $el.html(addHtml);

        $oLevel2
          .hide()
          .find('input').each(function () {

            $(this).prop('disabled', true);

          });

      } else {

        $el.html(deleteHtml);

        $oLevel2.show().find('input').each(function () {

          $(this).prop('disabled', false);

        });
      }
    });
  })();

  function oLevelSubjectGradeValidate() {
    var $oLevelsContainer = $('.o-levels-field-set'),
        $error = $('<div class="has-subject-grade-error help-block"></div>'),
        containerError = 'eVarsity.subject.grade.error',
        subjectGradeContainer = '.subject-grade',
        $containers = $(subjectGradeContainer),
        globalErrorMsgClass = 'global-subjects-grades-error ',
        formSubmitPrevented = 'easyVarsity.form.submit.prevented';

    function toggleDisplayError($el, $counterPart, $container) {

      var className1 = makeClassFromName($el.prop('name')),
          className2 = makeClassFromName($counterPart.prop('name')),
          $displayedError = $('.' + className1 + '.' + className2);

      if ($container.hasClass('has-error')) {

        if (!$displayedError.size()) {
          $container.after(
            $error
              .clone()
              .addClass(className1)
              .addClass(className2)
              .text('Invalid subject or grade! You may only pick from drop down.')
          );
        }

      }

      else {
        $displayedError.remove();
      }

    }

    $oLevelsContainer.on({
      'blur input': function () {
        var $el = $(this),
            $container = $el.closest(subjectGradeContainer),
            counterPartFlag = getCounterFlag($el),
            $counterPart = getCounterPart($el, counterPartFlag);

        removeGlobalError();

        if ($.trim($el.val()) === '' && $.trim($counterPart.val()) === '') {

          $container.removeClass('has-error').removeClass('has-success');

          $container.data(containerError, false);

        }

        else if (valueIsValid($el) && valueIsValid($counterPart)) {

          $container.removeClass('has-error').addClass('has-success');

          $container.data(containerError, false);
        }

        else {
          $container.removeClass('has-success').addClass('has-error');
          $container.data(containerError, true);
        }

        toggleDisplayError($el, $counterPart, $container);

      },

      'autocompleteselect': function (evt) {
        var $target = $(evt.currentTarget),
            $counter = getCounterPart($target, getCounterFlag($target)),
            $container = $target.closest(subjectGradeContainer);

        if (!valueIsValid($counter)) {
          $container.removeClass('has-success').addClass('has-error');

          $container.data(containerError, true);

        }

        else {
          $container.removeClass('has-error').addClass('has-success');

          $container.data(containerError, false);
        }

        toggleDisplayError($target, $counter, $container);

      }

    }, '.subject-grade input');


    $form.on('submit.eVarsity', function (evt) {
      toggleDisableBtn();

      if ($form.data(formSubmitPrevented)) {
        evt.preventDefault();

        var $slicedContainers = $containers.slice(0, 5);

        $slicedContainers.not('.has-success').addClass('has-error');

        if (!$('.' + globalErrorMsgClass).size()) {

          var $globalError =
                $error
                  .clone()
                  .addClass(globalErrorMsgClass)
                  .text('You did not enter at least 5 subjects or some inputs are invalid!');

          var $last = $slicedContainers.last(),
              $nextSubjectGradeError = $last.next('.has-subject-grade-error').not('.' + globalErrorMsgClass);

          ($nextSubjectGradeError.size() ? $nextSubjectGradeError : $last).after($globalError);
        }
      }

    });

    function toggleDisableBtn() {
      var preventSubmit = $('.subject-grade.has-success:visible').size() < 5 ||
                          $containers.filter('.has-error').size()> 0;

      $form.data(formSubmitPrevented, preventSubmit);
    }

    function getCounterPart($el, flag) {
      return $el
        .closest(subjectGradeContainer)
        .find('input[name*=' + flag + ']');
    }

    function valueIsValid($el) {
      var name = $el.prop('name');
      return (name.indexOf('subject') !== -1 ? subjects : grades ).indexOf($.trim($el.val())) !== -1;
    }

    /*
     given name of an input element, return a string
     that can be used as class name in a DOM element
     */
    function makeClassFromName(name) {
      return name.replace('[', '').replace(']', '');
    }

    function removeGlobalError() {
      if ($form.data(formSubmitPrevented)) {
        $('.' + globalErrorMsgClass).remove();

        $form.data(formSubmitPrevented, false);

        $containers
          .slice(0, 5)
          .each(function () {
            var $el = $(this);

            if (!$el.data(containerError)) {
              $el.removeClass('has-error');
            }
          });
      }
    }

    function getCounterFlag($el) {
      return $el.prop('name').indexOf('subject') !== -1 ? 'grade' : 'subject';
    }

    toggleDisableBtn();

  }

  //oLevelSubjectGradeValidate();

});
