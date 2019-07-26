"use strict";

//let fileUser = '/json/hide/user.json';

jQuery(document).ready(function($) {
    
    console.log('invitations.js geladen...');  

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

   language('en');

    $(".form-wrapper .button.login").click(function(e){
        let that = $(this);
        $.ajax({ 
            url: 'php/login.php',
            data: {
                user: $("#myemail").val(),
                password: $("#password").val(),
                type: 'admin'
            },
            type: 'post',
            success: function(login) {
                if (login === 'OK') {
                    console.log('login successful', login);  
                    $("#failure").hide();            
                    nextpage(that);

                    $(".form-wrapper .button.next").click(function() {
                        nextpage ($(this));
                    });
                    

                    $("#set").click(function() {  
                        /*
                         * Read all filter fields into an array and send to php for user list
                         */
                        let filter = new Array();
                        $(".list .select_option.selected").each( function (key, val) {
                            filter.push($(this).attr("value"));
                        });        
                        //console.log (filter);          
                        $.ajax({ 
                            url: 'php/invitationlist.php',
                            data: {
                                filter: filter
                            },
                            type: 'post',
                            success: function(output) {  
                                //console.log(output);  
                                let users = JSON.parse(output);
                                let distr_list = '';
                                let userlist = '<h3>Invitation List:</h3>';
                                userlist += '<table class="radio">';
                                userlist += '<tr><td><input type="checkbox" class="chk_all chk" colwidth="2" checked></td></tr>';
                                $.each(users, function (id, user) {
                                    userlist += '<tr>';
                                    userlist += '<td><input type="checkbox" class="chk" value="' + id + '" checked></td>';
                                    userlist += '<td>' + user.firstname + ' ' + user.lastname + '</td>';
                                    userlist += '<td><span class="language" code="' + user.survey + '"></span></td>';
                                    userlist += '<td><span class="language" code="' + user.region + '"></span></td>';
                                    userlist += '<td><span class="language" code="' + user.productline + '"></span></td>';
                                    userlist += '<td><a href="' + user.mailto + '">send</a></td>';
                                    userlist += '</tr>';
                                    distr_list += user.email + '; ';
                                });  
                                userlist += '</select>'; 
                                 
                                userlist += '</table>';  
                                userlist += '<a href="mailto:' + distr_list + '" id="distr"><div class="inline-button email">Send one e-mail to all selected users</div></a>';
                                userlist += '<h4 class="marginbottom">Please, click on send for all users you want to invite. Your e-mail program will be used to send the invitations to avoid that the message is blocked by the spam filter.</h4>';                    
                                $("#resultslist").html(userlist);
                                language('en');

                                /*
                                 *  Uncheck or check all checkboxes at the same time
                                 */
                                $(".chk_all").change ( function () {
                                    if ($(this).prop("checked")) {
                                        $(".chk").prop("checked", true);
                                    } else {
                                        $(".chk").prop("checked", false);
                                    }
                                });

                                /*
                                 *  redefine the send to all email button
                                 */
                                $(".chk").change( function () {
                                    distr_list = '';
                                    let n = 0;
                                    $(".chk:checked").each(function() {
                                        if (users[$(this).val()]) {
                                            distr_list += users[$(this).val()].email + '; ';
                                            n++;
                                        }
                                    });
                                    //console.log (distr_list);
                                    $(".inline-button.email").text('Send an Email to the selected ' + n + ' users');
                                    $("#distr").attr('href', 'mailto:' + distr_list);
                                });

                            }
                        });                        
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

// let getCookie = function (key) {
//     var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
//     return keyValue ? keyValue[2] : null;
// }  

let isEmail = function (email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

let language = function (language) {
    /*
     * Read the language file
     * As the language file will be read directly by the rowser, the information is not safe
     */
    //console.log('language', language);
    let languageFile = 'json/language.json';

    $.getJSON( languageFile, function (text) {
        $.each($("p.language, div.language, li.language, h3.language, h4.language, h5.language, td.language, label.language, span.language"), function (index, value) {
            //console.log('Field:', value);
            if (text[$(this).attr("code")][language]) {
                $(this).html(text[$(this).attr("code")][language]);
            } else {
                $(this).html(text[$(this).attr("code")]["en"]);
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