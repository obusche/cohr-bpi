"use strict";

let fileUser = '/json/hide/user.json';

jQuery(document).ready(function($) {
    
    console.log('user.js geladen...');  


    $("#rma-form").submit(function(e) {
        e.preventDefault();
    });

    $(".close-icon").click ( function () {
        $(".sheet").hide();
        $(".messagewindow").hide();
    });

    /*
     *  Make the fieldset header clickable
     */
    $(".steps li").click( function () {
        $(".steps li, .section").removeClass("is-active");
        $(this).addClass("is-active");
        $(".section." + $(this).attr('value')).addClass("is-active");

    });


    if (logged_in) {
        console.log('login successful');  
        /*
         * Enable the password input field when needed and 
         * link read = true to the admin property
         */
        $("#read, #level, #email").change( () => {
            if ($("#read").val() === 'true' || $("#level") === 'admin' || $("#level") === 'view') {
                $("#newuserpassword").show();
                if ($("#level").val() == 'admin' || $("#level").val() == 'view') {
                    $("#read").val('true');
                }
            }
        });       

        $(".button.confirm").click( function() {
            console.log('data sent...');
            let that = $(this);
            if (isEmail($("#email").val())) {
                $.ajax({ 
                    url: '/php/usermanagement.php',
                    data: {
                        user: $("#myemail").val(),
                        password: $("#password").val(),
                        email: $("#email").val(),
                        access: $("#access").val(),
                        firstname: $("#firstname").val(), 
                        lastname: $("#lastname").val(),
                        level: $("#level").val(),       
                        survey: $("#survey").val(),
                        region: $("#region").val(),
                        language: $("#language").val(),
                        productline: $("#productline").val(),
                        comment: $("#comment").val(),
                        action: $("#action").val(),
                        read: $("#read").val(),
                        newuserpassword: $("#newuserpassword").val()
                    },
                    type: 'post',
                    success: function(output) {
                        console.log('ID', output);

                        $(".sheet").show();
                        $("#useradmin").show();
                        if (output) {
                            $("#useradmin .response").text('The user list has been updated successfully');
                        } else {
                            $("#useradmin .response").text('ERROR: The new user could not be fount in the users list!');
                        }
                    }
                });
            } else {
                alert('This is not a valid Email');
            }
        });
    } 


});


let nextpage = function (button) {
    let currentSection = button.parents(".section");
    let currentSectionIndex = currentSection.index();
    let headerSection = $('.steps li').eq(currentSectionIndex);
    currentSection.removeClass("is-active").next().addClass("is-active");
    headerSection.removeClass("is-active").next().addClass("is-active");
    
    $("input.hidden").hide();
}

let previouspage = function (button) {
    let currentSection = button.parents(".section");
    let currentSectionIndex = currentSection.index();
    let headerSection = $('.steps li').eq(currentSectionIndex);
    currentSection.removeClass("is-active").prev().addClass("is-active");
    headerSection.removeClass("is-active").prev().addClass("is-active");

    $("input.hidden").hide();
}

// let getCookie = function (key) {
//     var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
//     return keyValue ? keyValue[2] : null;
// }  

let isEmail = function (email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}