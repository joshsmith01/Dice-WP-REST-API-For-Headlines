jQuery(document).ready(function ($) {

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
                    console.log('success');
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
                    console.log('error');
                }
            });
        }
    });




    var removeHeadlineLink = $('.remove-headline');
    removeHeadlineLink.click(function () {
         parentId = parseInt($(this).parent().attr('id'));
    });
    removeHeadlineLink.click(function () {
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

               // $('#custom-type-list li').remove('#' + parentId);

               $('#custom-type-list li#' + parentId).fadeOut(200, function () {
                  $(this).hide().remove();
               });




               console.log('success');
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
               console.log('error');
           }
       })
    });



});