"use strict";

module.exports = function (source) {
  return {
    minLength: 1,

    source: source,

    select: function (evt, ui) {
      var
        $el = $(this),
        $related = $($el.data('related-value'));

      $related.val(ui.item.id);

      $el.closest('form').formValidation('revalidateField', $el);
      $el.closest('form').formValidation('revalidateField', $related);

      return false;
    }
  };
};
