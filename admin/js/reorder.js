jQuery(document).ready(function ($) {




    $('.open-extra-info').click(function() {
        if ( $(this).parent().next('.extra-headline-info').is(":hidden") )  {
            $(this).parent().next('.extra-headline-info').slideDown(100);
        } else {
            $('.extra-headline-info').slideUp(100);
        }
    });


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
        var sortOrder = [];
        $('#custom-type-list li').each(function () {
            sortOrder.push(parseInt($(this).attr('id')));
        });
        return sortOrder;
    }
    getSortOrder();


    /**
     * Find the Top Headline post ID and return it to the caller. -JMS
     * @returns int
     */
    function getTopHeadlineId () {
        var checked = $('.top-headline-choice').is(':checked');
        if( checked ) {
            parentId = parseInt($('.top-headline-choice:checked').parents('.headline-item').attr('id'));
        } else {
            parentId = null;
        }
        return parentId;
    }
    getTopHeadlineId();


    /**
     * Run sortable methods on page load and on demand, hence placing it in function. -JMS
     */
    function runSortable () {
        sortList.sortable({
            cancel: 'li.static, input',
            update: function (event, ui) {
                getSortOrder();
                $('#message').remove();
            }
        });
    }
    runSortable();




    removeHeadlineLink.click(function () {
         parentId = parseInt($(this).parents('.headline-item').attr('id'));
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
               if ( true === response.success ) {
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


    $('input.lock-order').change( function () {
        if ($('.lock-order').is(':checked')) {
            $(this).parents('.headline-item').addClass('static').removeClass('ui-sortable-handle');
            runSortable();
        } else {
            $(this).parents('.headline-item').removeClass('static').addClass('ui-sortable-handle');
            runSortable();
        }
    });


    function getLockedMenuOrder() {
        var lockedOrder = [];
        $('.lock-order').each(function() {
           if ($(this).is(':checked')) {
                var parentsId = $(this).parents('.headline-item').attr('id');
                lockedOrder.push(parentsId);
           }
        });
        return lockedOrder;
    }

    /**
     * Loop through all the posts to determine if there is a specific tracking code associated with it. -JMS
     * @returns array;
     */
    function getTrackingCode () {
        var trackingArr = {};
        $('.headline-tracking-code').each(function () {
            var postId = $(this).parents('li.headline-item').attr('id');
            if ( $(this).val() ) {
                console.log( $(this).val() );
                trackingArr[postId] = $(this).val();
            } else {
                trackingArr[postId] = "";
            }
        });
        return trackingArr;
    }




    topChoice.click(function () {
        var checked;

        // Allow authors to choose one and only one top post to go to mobile platforms. -JMS
    $(".top-headline-choice").change(function () {
        var checked = $(this).is(':checked');


        $(".top-headline-choice").prop('checked', false).removeAttr('checked');
        if ( checked ) {
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
        runSortable();
        animation.show();

        var data = {
            action: 'update_headlines',
            order: sortOrder,
            lockOrder: getLockedMenuOrder(),
            trackingCode: getTrackingCode(),
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
                // location.reload();
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