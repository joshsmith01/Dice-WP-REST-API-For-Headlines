jQuery(document).ready(function ($) {

    // Remove the annoying status updates that don't disappear on their own. -JMS

    $('#headline-sort').on('click', '#message',function () {
        $('#message').remove();
    });

    var sortList = $('ul#custom-type-list');
    var animation = $('#loading-animation');
    var pageTitle = $('#headline-page-title');



    sortList.sortable({
        update: function (event, ui) {
            animation.show();

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'save_sort',
                    order: sortList.sortable('toArray'),
                    security: WP_HEADLINE_LISTING.security
                },
                success: function ( response ) {
                    $('#message').remove();
                    animation.hide();
                    if (true === response.success) {
                        pageTitle.after('<div id="message" class="updated"><p>' + WP_HEADLINE_LISTING.success + '</p></div>');
                    } else {
                        pageTitle.after('<div id="message" class="error"><p>' + WP_HEADLINE_LISTING.failure + '</p></div>');
                    }
                },
                error: function ( error ) {
                    $('#message').remove();
                    pageTitle.after('<div id="message" class="error"><p>' + WP_HEADLINE_LISTING.failure + '</p></div>');
                    animation.hide();
                    console.error('Ajax was not able to execute the sorting request');
                }
            });
        }
    });




    var removeHeadlineLink = $('.remove-headline');
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

    var topChoice = $('input.top-headline-choice');

    topChoice.click(function () {
        var checked;
        var parentId;

        // Allow authors to chose a top post to go to mobile platforms. -JMS
        $(".top-headline-choice").change(function () {
            var checked = $(this).is(':checked');
            var parentId = parseInt($(this).parent().attr('id'));

            $(".top-headline-choice").prop('checked', false);
            if (checked) {
                $(this).prop('checked', true);
                $('#message').fadeOut();
                animation.show();
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'update_top_headline',
                        parentId: parentId,
                        security: WP_HEADLINE_LISTING.security
                    },
                    success: function (response) {
                        $('#message').remove();

                        animation.hide();
                        if (true === response.success || response === 0 ) {
                            pageTitle.after('<div id="message" class="updated"><p>' + WP_HEADLINE_LISTING.addTopHeadlineSuccess + '</p></div>');
                        } else {
                            pageTitle.after('<div id="message" class="error"><p>' + WP_HEADLINE_LISTING.addTopHeadlineFailure + '</p></div>');
                        }
                    },
                    error: function (error) {
                        $('#message').remove();
                        pageTitle.after('<div id="message" class="error"><p>' + WP_HEADLINE_LISTING.failure + '</p></div>');
                        animation.hide();
                        console.error('Headline was not removed 1');
                    }
                });
            } else {

                $('#message').remove();
                animation.show();
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'remove_top_headline',
                        parentId: parentId,
                        security: WP_HEADLINE_LISTING.security
                    },
                    success: function (response) {
                        $('#message').remove();
                        animation.hide();
                        if (true === response.success) {
                            pageTitle.after('<div id="message" class="updated"><p>' + WP_HEADLINE_LISTING.removeTopHeadlineSuccess + '</p></div>');
                        } else {
                            pageTitle.after('<div id="message" class="error"><p>' + WP_HEADLINE_LISTING.removeTopHeadlineFailure + '</p></div>');
                        }
                    },
                    error: function (error) {
                        $('#message').remove();
                        pageTitle.after('<div id="message" class="error"><p>' + WP_HEADLINE_LISTING.failure + '</p></div>');
                        animation.hide();
                        console.error('Headline was not removed 2');
                    }
                });
            }
        });
    })
});