"use strict";

jQuery(document).ready(function($) {
    console.log('main.js geladen...');        
     
    // /*
    //     Stops page from opening the php target
    // */
    // $("#rma-form").submit(function(e) {
    //     e.preventDefault();
    // });

    $(".form-wrapper .button.next").click( function () {
        nextpage($(this));
    });

    $(".form-wrapper .button.last").click( function () {
        previouspage ($(this));
    });

    $(".button.contact").click( function () {
        //window.open("https://rma-mc.com/contact.php","_self");
    });
    
    $( ".form-wrapper .button.save" ).click(function () {
        saveForm();
    });

    $(".negative-comment").hide();

    
    
    /*
        set the language to default English
    */
    language(lng);

    $(".flag.german").click ( function () {
        language('de');
        lng = 'de';
        $("#language").val('de');
    });
    $(".flag.english").click ( function () {
        language('en');
        lng = 'en';
        $("#language").val('en');
    });

    /*
     *  Avoid the login window to be opened
     */
    $(".messagewindow.auto-open").removeClass("auto-open");

    /*
     *  Range Slider Boxes functions
     *  Check or unchecks the select boxes and opens the comment window
     */
    $(".range_box").click ( function () {
        $(this).parent().children().removeClass("selected skipped");
        $(this).addClass("selected");
        $(this).parent().find(".hidden-slider-value").val($(this).attr("value"));
        $(this).parent().find(".skip").prop("checked", false);
        if ($(this).attr("value") < 2) {
            $(this).parent().parent().find(".negative-comment").show();
        } else {
            $(this).parent().parent().find(".negative-comment").hide(); 
        }
    });

    /*
     *  Functionality for skipping a question
     */
    $(".skip").click( function () {
        if ( $(this).prop("checked") ) {
            $(this).parent().find(".range_box").removeClass("selected").addClass("skipped");
            $(this).parent().find(".hidden-slider-value").val('x');
        } else {
            $(this).parent().find(".range_box").removeClass("skipped");
        }

    });

    let hidden_fname = $("#input_fname").val();
    let hidden_lname = $("#input_lname").val();
    let hidden_email = $("#input_email").val();

    $("#make_anonymous").change( function () {
        if ($(this).prop( "checked" )) {
            /*
             *  Check in user file of user has alread sent an anonymous 
             *  survey this month
             */
            $.ajax({ 
                url: 'php/anonymity.php',
                data: {
                    id: $("#input_id").val()
                },
                type: 'post',
                success: function(response) {
                    if (response == 'true') {
                        $("#input_fname").val('anonymous');
                        $("#input_lname").val('anonymous');
                        $("#input_email").val('anonymous');
                    } else {
                        $("#make_anonymous").prop("checked", false);
                        alert ('We have already received one survey from you this month. Because you have sent this survey anonymous, we cannot track it and cannot offer to change your input.');
                    }
                }
            });            
        } else {
            $("#input_fname").val(hidden_fname);
            $("#input_fname").val(hidden_lname);
            $("#input_fname").val(hidden_email);
        }
    });
    
});


/*
 *   FUNCTION LIBRARY
 */

let nextpage = function (button) {
    let currentSection = button.parents(".section");
    let currentSectionIndex = currentSection.index();
    let headerSection = $('.steps li').eq(currentSectionIndex);
    if (currentSection["0"].name == 'decon' && $('#r1').prop('checked')) {
        //skip the substances chapter when no is checked
        currentSection.removeClass("is-active").next().next().addClass("is-active");
        headerSection.removeClass("is-active").next().next().addClass("is-active");
    } else {
        currentSection.removeClass("is-active").next().addClass("is-active");
        headerSection.removeClass("is-active").next().addClass("is-active");
    }
}

let previouspage = function (button) {
    let currentSection = button.parents(".section");
    let currentSectionIndex = currentSection.index();
    let headerSection = $('.steps li').eq(currentSectionIndex);
    currentSection.removeClass("is-active").prev().addClass("is-active");
    headerSection.removeClass("is-active").prev().addClass("is-active");
}

/*
    Sumbit the form
*/
let saveForm = function () {
    let formContent = $('#cs-form').serialize();
    $.ajax({
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: formContent,
        type: 'POST',
        url: 'php/save.php',
        success: function(response) {
            console.log(response);
            let success = jQuery.parseJSON(response);
            if (success.code == 'failure') {
                $(".thanks_failure").removeClass("hidden");
                $(".thanks").addClass("hidden");
            }
        }
    }).done(function(data, textStatus, jqXHR) {
        console.log('Data sent.');
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.log('There was an error.');
    });
}

  
let language = function (language) {
    /*
     * Read the language file
     * As the language file will be read directly by the rowser, the information is not safe
    */
   let languageFile = 'json/language.json';

    $.getJSON( languageFile, function (text) {
        $.each($("p.language, div.language, li.language, h3.language, h4.language, h5.language, td.language, label.language, span.language"), function (index, value) {
            //console.log('Field:', value);
            if (text[$(this).attr("code")][language]) {
                $(this).html(text[$(this).attr("code")][language]);
            } else if (text[$(this).attr("code")]["en"]) {
                $(this).html(text[$(this).attr("code")]["en"]);
            } else {
                $(this).html('# missing language #');
                console.log('Missing language:',$(this).attr("code"));
            }
        });
        $.each($("textarea.language, input.language"), function (index, value) {
            //console.log('Field:', value);
            if (text[$(this).attr("code")][language]) {
                $(this).attr("placeholder", text[$(this).attr("code")][language]);
            } else {
                $(this).attr("placeholder", text[$(this).attr("code")]["en"]);
            }
        });
    })
    .fail(function() {
        console.log ('Language file not read');
    })
}


