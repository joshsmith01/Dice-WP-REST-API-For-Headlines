jQuery(document).ready(function ($) {

    $('.open-extra-info').click(function() {
        if ( $('.extra-headline-info:first').is(":hidden") )  {
            $(this).next('.extra-headline-info').slideDown();
        } else {
            $('.extra-headline-info').hide();
        }
    });

    // if ($("div:first").is(":hidden")) {
    //     $("div").slideDown("slow");
    // } else {
    //     $("div").hide();
    // }

    var sortList = $('ul#custom-type-list');
    var animation = $('#loading-animation');
    var pageTitle = $('#headline-page-title');
    var sortOrder = [];
    var parentId;
    var checked;
    var removeHeadlineLink = $('.remove-headline');
    var topChoice = $('input.top-headline-choice');

    // Remove the annoying status updates that don't disappear on their own. -JMS
    $('#headline-sort').on('click', '#message',function () {
        $('#message').remove();
    });

    function getSortOrder() {
        sortOrder = [];
        $('#custom-type-list li').each(function () {
            sortOrder.push(parseInt($(this).attr('id')));
        });
        return sortOrder;
    }
    getSortOrder();


    function getTopHeadlineId () {
        var checked = $('.top-headline-choice').is(':checked');
        if(checked) {
            parentId = parseInt($('.top-headline-choice:checked').parent().attr('id'));
        } else {
            parentId = null;
        }
        return parentId;
    }
    getTopHeadlineId();


    sortList.sortable({
        update: function (event, ui) {
            getSortOrder();
            $('#message').remove();
        }
    });


    removeHeadlineLink.click(function () {
         parentId = parseInt($(this).parent().attr('id'));
    });
    removeHeadlineLink.click(function () {
        animation.show();
       $.ajax({
           url: ajaxurl,
           type: 'POST',
           dataType: 'json',
           data: {
               action: 'remove_headline',
               parentId: parentId,
               security: WP_HEADLINE_LISTING.security
           },
           success: function (response) {

               $('#message').remove();
               $('#custom-type-list li#' + parentId).fadeOut(200, function () {
                  $(this).hide().remove();
               });

               animation.hide();
               if (true === response.success) {
                   pageTitle.after('<div id="message" class="updated"><p>' + WP_HEADLINE_LISTING.catSuccess + '</p></div>');
               } else {
                   pageTitle.after('<div id="message" class="error"><p>' + WP_HEADLINE_LISTING.catFailure + '</p></div>');
               }
           },
           error: function (error) {
               $('#message').remove();
               pageTitle.after('<div id="message" class="error"><p>' + WP_HEADLINE_LISTING.failure + '</p></div>');
               animation.hide();
           }
       })
    });




    topChoice.click(function () {
        var checked;

        // Allow authors to choose one and only one top post to go to mobile platforms. -JMS
    $(".top-headline-choice").change(function () {
        var checked = $(this).is(':checked');


        $(".top-headline-choice").prop('checked', false).removeAttr('checked');
        if (checked) {
            $(this).prop('checked', true).attr('checked', 'checked');
            parentId = parseInt($(this).parent().attr('id'));
            $('#message').fadeOut();
        } else {
            $('#message').remove();
        }
            return parentId;
        });
    });




    $('#update-headlines').click(function () {
        getSortOrder();
        getTopHeadlineId();
        animation.show();

        var data = {
            action: 'update_headlines',
            order: sortOrder,
            security: WP_HEADLINE_LISTING.security
        };

        if ( parentId ) {
            data.topHeadlineId = parentId;
        }



        $.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (response) {
                $('#message').remove();
                animation.hide();
                if (true === response.success) {
                    pageTitle.after('<div id="message" class="updated"><p>' + WP_HEADLINE_LISTING.success + '</p></div>');
                } else {
                    pageTitle.after('<div id="message" class="error"><p>' + WP_HEADLINE_LISTING.failure + '</p></div>');
                }
            },
            error: function (error) {
                $('#message').remove();
                pageTitle.after('<div id="message" class="error"><p>' + WP_HEADLINE_LISTING.failure + '</p></div>');
                animation.hide();
                console.error('Ajax was not able to execute the Headlines Post request');
            }
        });

    });
});