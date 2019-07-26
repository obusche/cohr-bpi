"use strict";


let fileUser = '/json/hide/user.json';
let counterAttempts = 0;

jQuery(document).ready(function($) {
    
    console.log('edit.js geladen...');

    $("#rma-form").submit(function(e) {
        e.preventDefault();
    });

    $(".form-wrapper .button.confirm").click(function(e){
        let that = $(this);
        
        $.ajax({ 
            url: '/php/confirm.php',
            data: {
                data: $("#id").val(),
                action: 'edit-email'
            },
            type: 'post',
            success: function(output) {
                console.log('Output:', output);
            if (output) {
                $("#incorrectloginmessage").addClass("invisible");
                $("#id2").val($("#id").val());
                nextpage(that);

                $(".form-wrapper .button.set").click(function() {
                    let that2 = $(this);
                    $.ajax({ 
                        url: '/php/confirm.php',
                        data: {
                            id: $("#id2").val(),
                            key: $("#key").val(),
                            action: 'edit-key'
                        },
                        type: 'post',
                        success: function(output) {
                                    if (output) {                                                   
                                        $("#incorrectkey").addClass("invisible");
                                        console.log('Key correct, Cookie set', output);
                                        nextpage(that2);
                                    } else {
                                        $("#incorrectkey").removeClass("invisible");
                                        console.log('Failure: no cookie set');
                                    }
                                }
                    });

                });
                
            } else {
                //No email sent
                $("#incorrectloginmessage").removeClass("invisible");
            }
        }
    });


        // $.getJSON( fileUser, (user) => {
        //     let userEmail = $("#email1").val();
        //     if (user[userEmail]) {
        //         console.log('User',userEmail,'found');
        //         let userAccess = user[userEmail].access;
        //         if (userAccess == 'true') {       
        //             $("#incorrectloginmessage").addClass("invisible");
        //             $("#email2").val($("#email1").val());
        //             sendEmail (userEmail, user[userEmail].key);
        //             nextpage($(this));

        //             $(".form-wrapper .button.set").click(function() {
        //                 userEmail = $("#email2").val();
        //                 let userKey = user[userEmail].key;
        //                 let userInput = $("#key").val();
        //                 if (userKey == userInput) {
        //                     $("#incorrectkey").addClass("invisible");
        //                     setCookie('edit', userEmail + '&' + user[userEmail].key);
        //                     counterAttempts = 0;
        //                     nextpage($(this));
        //                 } else {
        //                     $("#incorrectkey").removeClass("invisible");
        //                     counterAttempts++;
        //                     $("#remainingattempts").text(3 - counterAttempts);
        //                     console.log('Attempts',counterAttempts);
        //                     if (counterAttempts > 2) {
        //                         deactivateUser(user, userEmail);
        //                     }
        //                 }
        //                 //console.log('Cookie set:', getCookie('edit'));
        //             });

        //         } else {
        //             $("#incorrectloginmessage").removeClass("invisible");
        //         }
        //     } else {
        //         console.log('User',userEmail,'not found');
        //         $("#incorrectloginmessage").removeClass("invisible");
        //     }
            
        // })
        // .fail( function () {
        //     console.log('user.json could not be read');
        // });
    });

    $(".form-wrapper .button.last").click(function() {
        previouspage ($(this));
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

let setCookie = function (key, value) {
    var expires = new Date();
    expires.setTime(expires.getTime() + 1000*3600*24*7*52); //1 hour  
    console.log('Cookie',key + '=' + value + ';expires=' + expires.toUTCString() + ';path=/;');
    document.cookie = key + '=' + value + ';expires=' + expires.toUTCString() + ';path=/;';
}

let getCookie = function (key) {
    var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
    return keyValue ? keyValue[2] : null;
}  

// let sendEmail = function (address, key) {
//     //'address='+encodeURIComponent(address)+'&key='+key
//     $.ajax({
//         data: {
//             address: address,
//             key: key 
//         },
//         type: 'POST',
//         url: '/php/emailcode.php'
//       }).done(function(data, textStatus, jqXHR) {
//         console.log('Email sent to', address);
//       }).fail(function(jqXHR, textStatus, errorThrown) {
//         console.log('There was an error.');
//       });
// }

let deactivateUser = function (data, user) {
    data[user].access = 'false';
    console.log('User',data);
    $.ajax({
        data: {
            json: JSON.stringify(data, undefined, 2),
            file: '../json/user.json' 
        },
        type: 'POST',
        url: '/php/jsonwrite.php'
    }).done(function(data, textStatus, jqXHR) {
        console.log('User',user,'deactivated');
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.log('There was an error.');
    });
}