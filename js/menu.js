"use strict";

jQuery(document).ready(function($) {
    console.log('menu.js geladen...');
    
    $(".menu .hamburger, .sheet").click ( function () {
        $(".menu .hamburger").toggle();
        $(".menu .dropdown").toggle();
        $(".sheet").toggle();
        $(".messagewindow").hide();
    });

    /*
        * Message window functions
        */
    $(".dropdown .changepassword").click( function () {
        $(".messagewindow").hide();
        $(".sheet").show();
        $("#changepassword").show();
    });

    $(".dropdown .usersettings").click( function () {
        $(".messagewindow").hide();
        $(".sheet").show();
        $("#usersettings").show();
    });

    /*
     *  Show Login window when user is not logged in
     */
    if (!logged_in) {
        $(".messagewindow.auto-open").show();
    }

    $(".dropdown .login").click( function () {
        $(".messagewindow").hide();
        $(".sheet").show();
        $("#login").show();
    });

    $(".dropdown .logout").click ( function () {
        console.log ('Logout');
        $.ajax({ 
            url: 'php/logout.php',
            type: 'post',
            success: function(logout) {
                console.log ('Logout successful', logout);
                location.reload();
            }
        });
    });

    $(".close-icon").click ( function () {
        $(".sheet").hide();
        $(".messagewindow").hide();
        $(".dropdown").hide();
        $(".hamburger").toggle();
    });

    $(".messagewindow input").change ( function () {
        $(".messagewindow .response").html('');
    });

    $("#login .set-button").click ( function () {
        $.ajax({ 
            url: 'php/login.php',
            data: {
                user: $("#em").val(),
                password: $("#pw").val(),
                type: 'results'
            },
            type: 'post',
            success: function(login) {
                if (login == 'OK') {

                    $("#login .response").html('Login success!');
                    //console.log ('Login successful');
                    location.reload();

                } else {

                    $("#login .response").html('Login failed!');
                    
                }
            }
        });
    });

    $("#changepassword .set-button").click( function () {
        if ($("#old_pw").val() && $("#new_pw1").val() && $("#new_pw1").val() == $("#new_pw2").val()) {
            $.ajax({ 
                url: 'php/changepassword.php',
                data: {
                    id: $("#input_id").val(),
                    old_pw: $("#old_pw").val(),
                    new_pw: $("#new_pw1").val()
                },
                type: 'post',
                success: function(response) {
                    
                        $("#changepassword .response").html(response);

                }
            });  
        } else {
            $("#changepassword .response").html('Error: Please check your input!');
        }
    });

    $("#usersettings .set-button").click( function () {
        if (true) {
            $.ajax({ 
                url: 'php/usersettings.php',
                data: {
                    id: $("#input_id").val(),
                    firstname: $("#us_firstname").val(),
                    lastname: $("#us_lastname").val(),
                    email: $("#us_email").val(),
                    password: $("#us_pw").val()
                },
                type: 'post',
                success: function(response) {
                    $("#usersettings .response").html(response);
                }
            });  
        } else {
            $("#usersettings .response").html('Error: Changes could not be done!');
        }
    });
});     

