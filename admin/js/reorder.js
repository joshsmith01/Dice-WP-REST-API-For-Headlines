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
});