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
/***/ function(module, exports, __webpack_require__) {

	$(function () {
	  "use strict";
	  __webpack_require__(1)

	  $('#staff-profile-create-form').formValidation()
	})


/***/ },
/* 1 */
/***/ function(module, exports) {

	"use strict";

	var $selectCapability = $('#select-capabilities')
	var $selectedCapability = $('#selected-capabilities')
	var $capabilitiesToSelectFrom = $('#capabilities-to-select-from')
	var $capabilitiesSelected = $('#capabilities-selected')

	function updateSelections(){
	  var capabilitiesToSelectFrom = {}
	  var capabilitiesSelected = {}

	  $selectedCapability.find('option').each(function () {
	    var $el = $(this)
	    capabilitiesSelected[$el.val()] = $el.text()
	  })
	  $capabilitiesSelected.val(JSON.stringify(capabilitiesSelected))

	  $selectCapability.find('option').each(function () {
	    var $el = $(this)
	    capabilitiesToSelectFrom[$el.val()] = $el.text()
	  })
	  $capabilitiesToSelectFrom.val(JSON.stringify(capabilitiesToSelectFrom))
	}

	$('#select-one-capability').click(function () {
	  var $selected = $selectCapability.find('option:selected')
	  $selectedCapability.append($selected.clone())
	  $selected.remove()
	  updateSelections()
	})

	$('#deselect-one-capability').click(function () {
	  var $selected = $selectedCapability.find('option:selected')
	  $selectCapability.append($selected.clone())
	  $selected.remove()
	  updateSelections()
	})

	$('#select-all-capabilities').click(function () {
	  var $selected = $selectCapability.find('option')
	  $selectedCapability.append($selected.clone())
	  $selected.remove()
	  updateSelections()
	})

	$('#deselect-all-capabilities').click(function () {
	  var $selected = $selectedCapability.find('option')
	  $selectCapability.append($selected.clone())
	  $selected.remove()
	  updateSelections()
	})


/***/ }
/******/ ]);