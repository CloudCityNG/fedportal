/**
 * Created by maneptha on 13-Feb-15.
 */

"use strict";

function numberFormat(string) {

  var originalString = String(string).trim();

  if (originalString === '') {
    return;
  }

  string = originalString.replace(/,/g, "");

  var toNumber = Number(string);

  if (isNaN(toNumber)) {

    return originalString;
  }

  var matched = /^([-\+]?\d+)(\.\d*)$/.exec(toNumber.toFixed(2));

  string = matched[1].replace(/^\+/, '');

  var decimal = matched[2];

  if (string.replace(/^-/, '').length < 4) {
    return string + decimal;
  }

  var neg;

  if (string[0] === '-') {
    string = string.slice(1);
    neg = '-';

  } else {

    neg = '';
  }

  var len = string.length,
      stringArray = [],
      mod = len % 3,
      others = string.slice(mod);

  if (mod) {
    stringArray.push(string.slice(0, mod));
  }

  var _ref = _.range(Math.floor(len / 3));

  for (var _i = 0, _len = _ref.length; _i < _len; _i++) {
    var i = _ref[_i];
    stringArray.push(others.slice(i * 3, i * 3 + 3));
  }

  return neg + stringArray.join(",") + decimal;
}
