"use strict";

console.log('sec.js loaded...');
readonly = '';
admin = true;

jQuery(document).ready(function($) {

    console.log('Edit Mode');
    
    $(".admin").show();
    $("#editmode").append(username);

    /*
    * allows to jump to pages directly ny clicking o the header line
    */
    $(".steps li").not(".noclick").click( function (b) {
        //console.log('click',$(this),$(this).attr('fieldset'));
        $(".section").removeClass("is-active");
        $(".steps li").removeClass("is-active");
        $(".section." + $(this).attr('fieldset')).addClass("is-active");
        $(this).addClass("is-active");
    })
    /*
     * Set all input field to writable
     */
    $('input').attr('readonly', false);

    let newquoteselector = $.parseHTML('<div id="pricelistDropdown" ><select id="pricelistSelection" class="dropdown"><option value="EUR">EUR</option><option value="GBP">GBP</option><option value="USD">USD</option></select></div><input type="hidden" name="form[currency]" id="currency"><div id="productDropdown"></div><div><button id="deleteQuotelines">Clear</button></div>');
    $("#newquoteselector").append(newquoteselector);


    /*
     * Open the contracts.json file to get the quote information
     * Define the click function
     */
    let contractsJson = '../json/hide/contracts.json';

    $.ajax({ 
        url: '/php/json.php',
        data: {
            file: contractsJson,
            action: 'read'
        },
        dataType: 'json',
        type: 'post',
        success: function(data) {
            if (data) {
                console.log( "Contracts File Read Success" );
                /*
                *  Create the selection field for the instrumenty by the key in the contracts.json file
                */
                let productList = '<select name="productSelection" id="productDropdown" class="dropdown"><option>Products</option>';
                $.each(data, function (key, val) {
                    productList += '<option value="' + key + '">' + key + '</option>'; 
                });
                productList += '</select>';
                let productDropdown = $.parseHTML(productList);
                
                $(productDropdown).change( function () {
                    /*
                     * Update the price list if an editor creates a new quote
                     * First open the price list then start the function to write proces into the contracts file
                     * A quote can still be created when this operation fails, as the prices are pulled from the contracts file
                     * 
                     * This function is not save and has to be changed as the file is not protected by a .htaccess file
                     */
                    $.ajax({
                        type: "GET",
                        url: "assets/AllActiveMCProducts.csv",
                        dataType: "text",
                        success: function(data) {
                            updatePricelist(data);
                        }
                    })
                    .always(function() {
                        /*
                        * Regardless if the price list update is successful the new quote positions will generates, 
                        * but only after the attempt was made to update the price list
                        */
                        console.log('Generate new quote...');
                        $("#quotePositions").children().remove();
                        let priceListCurrency = $("#pricelistSelection").val();
                        $("#currency").val(priceListCurrency);
                        $("#displaycurrency").text(priceListCurrency);
                        let n = 0;

                        $.each(data[$("#productDropdown option:selected").text()], function (key, val) {
                            n++;
                            //console.log(key, val);
                            let quotePosition = 
                                '<label class="switch">' +
                                    '<input type="checkbox" name="form[check-quote' + n + ']" class="' + val['type'] + '" id="c' + n + '" value="c' + n + '">' +
                                    '<span class="slider round"></span>' +
                                '</label>' +
                                '<div class="left"><input type="text" class="partnumber" name="form[quote][' + key + '][number]" value="' + key + '" ' + readonly + ' ></div>' +
                                '<div class="left"><textarea name="form[quote][' + key + '][text]" rows="4" ' + readonly + ' >' + val['text'][lng] + '</textarea></div>' +
                                '<div class="right"><input type="text" class="price" name="form[quote][' + key + '][formattedprice]" value="' + parseFloat(val[priceListCurrency]).format(2, 3, priceListCurrency) + '" ' + readonly + ' ></div>' +
                                '<input name="form[quote][' + key + '][price]" value="' + val[priceListCurrency] + '" hidden>' +
                                '<input name="form[quote][' + key + '][type]" value="' + val['type'] + '" hidden>'
                            ;
                            $("#quotePositions").append($.parseHTML(quotePosition));
                            
                        });
                        quoteCheckboxFunctions();
                        saveForm(); 
                        
                    });
                });

                $("#productDropdown").append(productDropdown);

            } else {
                console('No date returned from contracts file');
            }
        },
        error: function () {
            console.log('Contracts file could not be opened');
        }
    });

});


file_not_found = function (that) {
    console.log('SE-File could not be opened');
    /*
        * Check if user is logged in. If yes
        * allow to create a new RMA
        */ 
    $.ajax({ 
        url: '/php/confirm.php',
        data: {
            action: 'login'
        },
        type: 'post',
        success: function(output) {
            if (output) {
                if ($("#se-number").val() && $("#email").val()) {
                    if(confirm($("#se-number").val() + ' does not exist. Do you want to create a new RMA?')){
                        nextpage(that); 
                    }
                } else {
                    console.log('No permission to create a new RMA form');
                }
            } 
        }
    });
}