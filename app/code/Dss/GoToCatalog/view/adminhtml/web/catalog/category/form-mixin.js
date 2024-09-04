define([
  'jquery'
], function ($) {
  'use strict';

  return function (target) {
      $(document).ready(function () {
          function changeButtonState() {
              var $viewInStore = $("#goToCatalogViewInStore");
              if (!$viewInStore.length) {
                  return;
              }

              if ($('[name="is_active"]').val() == 0) {
                  $viewInStore.prop("disabled", true);
              } else {
                  $viewInStore.prop("disabled", false);
              }
          }

          $(document).on("change", '[name="is_active"]', function () {
              changeButtonState();
          });

          $(document).ajaxComplete(function () {
              changeButtonState();
          });

          changeButtonState();
      });
      return target;
  };
});
