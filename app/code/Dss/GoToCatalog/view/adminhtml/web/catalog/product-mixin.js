define(["jquery"], function ($) {
    "use strict";

    $(document).ready(function () {
        function changeButtonState() {
            var $viewInStore = $("#goToCatalogViewInStore");
            if (!$viewInStore.length) {
                return;
            }
            if (
                $('[name="product[status]"]').val() == 2 ||
                $('[name="product[visibility]"]').val() == 1
            ) {
                $viewInStore.prop("disabled", true);
            } else {
                $viewInStore.prop("disabled", false);
            }
        }

        $(document).on("change", '[name="product[status]"]', function () {
            changeButtonState();
        });

        $(document).on("change", '[name="product[visibility]"]', function () {
            changeButtonState();
        });

        $(document).ajaxComplete(function () {
            changeButtonState();
        });
        changeButtonState();
    });
});
