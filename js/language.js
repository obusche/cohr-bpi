"use strict";

let fileLang = 'json/language.json';

jQuery(document).ready(function($) {
    
    console.log('results.js geladen...');  

    $("#rma-form").submit(function(e) {
        e.preventDefault();
    });

    $(".form-wrapper .button.last").click(function() {
        previouspage ($(this));
    });


    $(".form-wrapper .button.login").click(function(e){
        let that = $(this);
        $.ajax({ 
            url: 'php/login.php',
            data: {
                user: $("#myemail").val(),
                password: $("#password").val(),
                type: 'results'
            },
            type: 'post',
            success: function(login) {
                console.log('login',login);
                if (login === 'OK') {
                    nextpage(that);
                    console.log ('Selected:', $("#languages").val());
                    $("#languages").change (function () {
                        console.log('Language selected:',$("#available_languages").val());
                        if ($("#languages").val()) {
                            $.ajax({ 
                                url: 'php/language_form.php',
                                data: {
                                    language: $("#languages").val(),
                                    target_language: $("#available_languages").val()
                                },
                                type: 'post',
                                success: function(output) {
                                    $("#language_form").html(output);
                                    $("#available_languages").change (function () {                               
                                        //console.log('Available languages:',$("#available_languages").val());
                                        $.ajax({ 
                                            url: 'php/language_form.php',
                                            data: {
                                                language: $("#languages").val(),
                                                target_language: $("#available_languages").val()
                                            },
                                            type: 'post',
                                            success: function(output) {
                                                $("#language_form").html(output);
                                            }
                                        });
                                    });

                                    $(".button .save").click ( function () {
                                        $.ajax({ 
                                            url: 'php/language_write.php',
                                            data: {
                                                language: $("#languages").val()
                                            },
                                            type: 'post',
                                            success: function(output) {
                                                $("#language_result").html(output);
                                            }
                                        });
                                    });
                                }
                                    
                            });
                        }
                    });

                } else {
                    $("#failure").show();
                }
            
            }
        });
    
    });

});

let nextpage = function (button) {
    let currentSection = button.parents(".section");
    let currentSectionIndex = currentSection.index();
    let headerSection = $('.steps li').eq(currentSectionIndex);
    currentSection.removeClass("is-active").next().addClass("is-active");
    headerSection.removeClass("is-active").next().addClass("is-active");
}

let previouspage = function (button) {
    let currentSection = button.parents(".section");
    let currentSectionIndex = currentSection.index();
    let headerSection = $('.steps li').eq(currentSectionIndex);
    currentSection.removeClass("is-active").prev().addClass("is-active");
    headerSection.removeClass("is-active").prev().addClass("is-active");
}

