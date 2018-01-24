"use strict";

$(function () {
    $(".pencil").on("click", function() {
        $(this).parent().next().toggle();
    });
});
