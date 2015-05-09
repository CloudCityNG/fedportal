"use strict";

/**
 *
 * @param {Array} source
 * @param {String} fieldToDisplay
 * @returns {{minLength: number, source: Array, select: Function}}
 */
function sessionAutoComplete(source, fieldToDisplay) {
  return {
    minLength: 1,

    source: source,

    select: function(evt, ui) {
      var
        $el = $(this),
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
  sessionAutoComplete: sessionAutoComplete
};
