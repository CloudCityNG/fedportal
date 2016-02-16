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



/***/ }
/******/ ]);