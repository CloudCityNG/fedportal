(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({"C:\\wamp\\www\\fedportal\\admin_academics\\assessment\\grade-student\\js\\grade-student-raw.js":[function(require,module,exports){
/*jshint camelcase:false*/

"use strict";

(function studentCourseQueryFrom() {
  var tenMostRecentSemesters = JSON.parse($('#tenMostRecentSemesters-container').text());

  $('#semester').autocomplete(
    require('./../../../utilities/js/admin-academics-utilities.js').sessionSemesterAutoComplete(
      tenMostRecentSemesters, 'label'
    )
  );

  $('#student-course-query-form').formValidation(
    {
      fields: {
        'student-course-query[semester_id]': {
          excluded  : false,
          validators: {
            notEmpty: {message: 'You may only pick from the drop down list'}
          }
        }
      }
    }
  );
})();

(function studentCourseScoreForm() {
  var $courseScores = $('.course-score').each(function() {
    var $el = $(this);

    if (/^\d{1,3}(?:\.\d{0,2})?$/.test($el.val().trim())) {
      $el.prop('disabled', true).siblings('.course-score-edit-trigger').show();
    }
  });

  $('.course-score-edit-trigger').click(function() {
    $(this).hide().siblings('.course-score').prop('disabled', false);
  });

  var scoreGradeMapping = JSON.parse($('#scoreGradeMapping-container').text());

  var $form = $('#student-course-score-form').formValidation(
    {
      row: {
        selector: 'td'
      }
    }
  );

  $form.on('success.field.fv', '.course-score', function(e, data) {
    updateRowWithLetterGrade(data.element);
  });

  $form.on('success.form.fv', function(evt) {
    var scoreInputted = false;
    $courseScores.not(':disabled').each(function() {
      if ($(this).val().trim()) scoreInputted = true;
    });

    if (!scoreInputted) {
      window.alert('No score was inputted or updated. You may not submit form!');
      evt.preventDefault();
    }
  });

  $('#student-course-score-form-reset-btn').click(function() {
    $form.data('formValidation').resetForm();

    $courseScores.each(function() {
      var $el = $(this);
      var existingVal = $el.data('existing-score');
      $el.val(existingVal);

      if (existingVal) {
        updateRowWithLetterGrade($el);
        $el.prop('disabled', true).siblings('.course-score-edit-trigger').show();
      }
    });
  });

  /**
   *
   * @param {jQuery} $el
   */
  function updateRowWithLetterGrade($el) {
    var val = $el.val().trim();

    if (val) {
      var scoreGrade = scoreToLetterGrade(val);

      if (scoreGrade) {
        var
          score = scoreGrade[0],
          grade = scoreGrade[1];

        $el.val(score);
        $el.parent().next().text(grade);
      }
    }
  }

  /**
   *
   * @param {String|number} score - student's score in course. Must be a number or numeric string
   * @returns {Array|null}
   */
  function scoreToLetterGrade(score) {
    score = Number(score);

    if (isNaN(score)) {
      return null;
    }

    var scoreGrade = _.find(scoreGradeMapping, function(minMax) {
      var
        min = minMax[0],
        max = minMax[1];

      return min <= score && score <= max;
    });

    score = score.toFixed(2);
    return scoreGrade ? [score, scoreGrade[2]] : [score, 'F'];
  }
})();

},{"./../../../utilities/js/admin-academics-utilities.js":"C:\\wamp\\www\\fedportal\\admin_academics\\utilities\\js\\admin-academics-utilities.js"}],"C:\\wamp\\www\\fedportal\\admin_academics\\utilities\\js\\admin-academics-utilities.js":[function(require,module,exports){
"use strict";

/**
 *
 * @param {Array} source
 * @param {String} fieldToDisplay - the field from the source that will be set as value
 * of form control been auto-completed
 *
 * @returns {{minLength: number, source: Array, select: Function}}
 */
function sessionSemesterAutoComplete(source, fieldToDisplay) {
  return {
    minLength: 1,

    source: source,

    select: function(evt, ui) {
      var
        $el      = $(this),
        $related = $($el.data('related-input-id'));

      $related.val(ui.item.id);

      if (evt.originalEvent.which === 1) {
        window.setTimeout(function() {
                            $el.val(ui.item[fieldToDisplay]);
                          }
        );
      }

      window.setTimeout(function() {
                          $el.closest('form').formValidation('revalidateField', $el);
                          $el.closest('form').formValidation('revalidateField', $related);
                        }
      );

      return false;
    }
  };
}

module.exports = {
  sessionSemesterAutoComplete: sessionSemesterAutoComplete
};

},{}]},{},["C:\\wamp\\www\\fedportal\\admin_academics\\assessment\\grade-student\\js\\grade-student-raw.js","C:\\wamp\\www\\fedportal\\admin_academics\\utilities\\js\\admin-academics-utilities.js"]);
