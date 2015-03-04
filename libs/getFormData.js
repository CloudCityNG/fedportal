/**
 * Created by maneptha on 01-Mar-15.
 */

module.exports = function ($form) {
  "use strict";

  var postData = {};

  _.each($form.serializeArray(), function (el) {
    postData[el.name] = el.value;
  });

  return postData;
};
