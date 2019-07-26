"use strict";

jQuery(document).ready(function($) {
    console.log('other.js geladen...');
    $(".button.home").click( function () {
        window.open("https://rma-mc.com","_self");
    });
});