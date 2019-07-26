"use strict";

let fileUser = '/json/hide/user.json';

jQuery(document).ready(function($) {
    
    console.log('results.js geladen...');  

    $("#rma-form").submit(function(e) {
        e.preventDefault();
    });

    $(".form-wrapper .button.last").click(function() {
        previouspage ($(this));
    });

    /*
     * Functions for the <select> Filter
     */
    $(".filter-box .set-button").click( function () {
        $(".select_option").hide();
        $(".select_arrow").removeClass("selected");
    });

    $(".select_head").click ( function () {
        $(this).parent().find(".select_option").toggle();
        $(this).find(".select_arrow").toggleClass('selected');
    });
    
    $(".select_option").click ( function () {
        $(this).toggleClass("selected");
    });
    
    /*
     *  Make the fieldset header and the menu buttons clickable
     */
    $(".steps li, .login .picture-button").click( function () {
        $(".steps li, .section").removeClass("is-active");
        //$(this).addClass("is-active");
        $(".steps ." + $(this).attr('value')).addClass("is-active");
        $(".section." + $(this).attr('value')).addClass("is-active");

    });


    if (logged_in) {
        console.log('login successful', login);  
        $("#failure").hide();            
        //nextpage(that);

        $(".form-wrapper .button.next").click(function() {
            nextpage ($(this));
        });

        /*
         *  Load all responses at start
         */
        $.ajax({ 
            url: 'php/data_sets.php',
            data: {
                filter: 'all'
            },
            type: 'post',
            success: function(output) {
                $("#resultslist").html(output);
                $(".bpidot").click( function () {
                    $(".survey-response").hide();
                    $("#" + $(this).attr('id') + "-").show();
                });
            }
        });  

        $.ajax({ 
            url: 'php/data_history.php',
            data: {
                filter: 'all',
                to_date: $("#to_date").val(),
                from_date: $("#from_date").val()
            },
            type: 'post',
            success: function(output) {
                $("#resultslist21").html(output);  
                setBargraphClicks();
                                        
            }                       
        });   

        /*
         *  Load datasets on 'Responses' screen
         */
        $(".responses .set-button").click(function() {   
            let filter = new Array();
            $(this).parent().find(".select_option.selected").each( function (key, val) {
                filter.push($(this).attr("value"));
                console.log($(this));
            });
            //console.log('Filter',filter); 

            $.ajax({ 
                url: 'php/data_sets.php',
                data: {
                    filter: filter
                },
                type: 'post',
                success: function(output) {
                    console.log('Input', filter);
                    $("#resultslist").html(output);

                    $(".bpidot, .bpibar").click( function () {
                        $(".survey-response").hide();
                        $("#" + $(this).attr('id') + "-").show();
                    });
                }
            });      
        });

        /*
         *  Load datahistory on 'Graph' screen
         */
        $(".graph .set-button").click(function() { 
            
            /*
             *  Collect all selected filter options and write them into array
             */
            let filter = new Array();
            $(this).parent().find(".select_option.selected").each( function (key, val) {
                filter.push($(this).attr("value"));
            });
            $.ajax({ 
                url: 'php/data_history.php',
                data: {
                    filter: filter,
                    to_date: $("#to_date").val(),
                    from_date: $("#from_date").val()
                },
                type: 'post',
                success: function(output) {
                    $("#resultslist21").html(output);                           
                }
            });                        
        });

        /*
         *  Load trend chart
         */
        $(".trend .set-button").click(function() { 
            /*
             *  Collect all selected filter options and write them into array
             */
            let trend_filter = new Array();
            $(this).parent().find(".filter .select_option.selected").each( function (key, val) {
                trend_filter.push($(this).attr("value"));
            });
            let trend_questions = new Array();
            $(this).parent().find(".questions .select_option.selected").each( function (key, val) {
                trend_questions.push($(this).attr("value"));
            });
            if (trend_filter.length > 0) {
                if (trend_questions.length > 0) {
                    $.ajax({ 
                        url: 'php/data_trend.php',
                        data: {
                            filter: trend_filter,
                            questions: trend_questions,
                            to_date: $("#to_date3").val(),
                            from_date: $("#from_date3").val()
                        },
                        type: 'post',
                        success: function(output) {
                            console.log('Trend:',output);
                            $("#resultslist3").html(output);                           
                        }
                    });
                } else {
                    $("#resultslist3").html('Please, select questions!');
                }   
            } else {
                $("#resultslist3").html('Please, select productline and region!');
            }                          
        });

    } else {
        $("#failure").show();
    }
        
});

/*
 *  FUNCTION LIBRARY
 */

let setBargraphClicks = function () {
    //console.log('Bargraph Clicks set');
    $(".bargraph.green").click ( function () {
        showResponses('green', $(this).attr('question'));
    });
    $(".bargraph.yellow").click ( function () {
        showResponses('yellow', $(this).attr('question'));
    });
    $(".bargraph.red").click ( function () {
        showResponses('red', $(this).attr('question'));
    }); 
}

let showResponses = function (color, question) {
    console.log (color, question);
    $.ajax({ 
        url: '/php/data_groups.php',
        data: {
            color: color,
            question: question,
            to_date: $("#to_date").val(),
            from_date: $("#from_date").val()
        },
        type: 'post',
        success: function(output) {
            $("#resultslist22").html(output);                           
        }
    });  
}

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
 

let isEmail = function (email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

