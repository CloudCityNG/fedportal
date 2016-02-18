/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

	$(function () {
	  "use strict";

	  var $checkCourseData = $('.check-original-course-data')
	  var $currentCourse = $('#current-course-data')
	  var currentCourseData = JSON.parse($currentCourse.val() || '{}')

	  $('#course-create-form').formValidation()

	  $checkCourseData.blur(function () {
	    enableSubmit()
	  })

	  $('.toggle-form-control-edit').click(function () {
	    var $el = $(this);

	    if ($el.is('.glyphicon-pencil')) {
	      $el.hide().next().show().closest('.form-group').find('.form-control').prop('disabled', false);

	    } else {
	      var $formGrp = $el.closest('.form-group')
	      var $formControl = $formGrp.find('.form-control').first().prop('disabled', true)
	      var originalVal = currentCourseData[getName($formControl.prop('name'))]
	      $formControl.val(originalVal)
	      $formGrp.removeClass('has-success').removeClass('has-error')
	      $formGrp.children('.form-control-feedback').hide()
	      $formGrp.children('.help-block').hide()
	      $el.hide().prev().show()

	      enableSubmit()
	    }
	  })

	  function enableSubmit() {
	    if (!$currentCourse.size()) return

	    var disabled = true
	    var $submitBtn = $('#submit-btn')

	    $checkCourseData.each(function () {
	      var $el = $(this)
	      var val

	      if ($el.prop('disabled')) return

	      var name = getName($el.prop('name'))
	      var $toggler = $el.closest('.form-group').find('.toggle-form-control-edit:visible')
	      val = $el.val().trim()

	      if (!val) return

	      var originalVal = '' + currentCourseData[name]

	      if (val.toUpperCase() !== originalVal.trim().toUpperCase()) disabled = false
	      else $toggler.click()
	    })

	    $submitBtn.prop('disabled', disabled)
	  }

	  /**
	   * In PHP forms, using the pattern name="collection_variable[name]" is common for form control names.
	   * This enables us
	   * to collect all form controls on the server with just the key 'collection_variable'. This function returns
	   * just the 'name' portion of this pattern.
	   *
	   * @param {string} propName - the name property of a form element
	   * @returns {string}
	   */
	  function getName(propName) {
	    var NAME_RE = new RegExp("\\[(\\w+)\\]$")
	    return NAME_RE.exec(propName)[1]
	  }
	})



/***/ }
/******/ ]);