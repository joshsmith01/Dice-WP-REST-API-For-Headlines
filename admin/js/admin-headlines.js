jQuery(document).ready(function ($) {

    // Set up the tabs for use. -JMS
    $("#mytabs .hidden").removeClass('hidden');
    $("#mytabs").tabs();


    // Work with the media uploader from here. -JMS
    // Fetch and store elements as variables. -JMS


    var smallBannerImg = document.getElementById('banner-small');
    var smallBannerHidden = document.getElementById('banner-small-hidden-field');
    var smallBannerAddButton = document.getElementById('banner-small-upload-button');
    var smallBannerDeleteButton = document.getElementById('banner-small-delete-button');

    var largeBannerImg = document.getElementById('banner-large');
    var largeBannerAddButton = document.getElementById('banner-large-upload-button');
    var largeBannerHidden = document.getElementById('banner-large-hidden-field');
    var largeBannerDeleteButton = document.getElementById('banner-large-delete-button');

    var smallBannerCustomUploader = wp.media({
        title: 'Select an Image',
        button: {
            text: 'Use this Image'
        },
        multiple: false
    });

    var largeBannerCustomUploader = wp.media({
        title: 'Select an Image',
        button: {
            text: 'Use this Image'
        },
        multiple: false
    });


    // Small Banner Visibility
    var smallBannerToggleVisibility = function (action) {
        if ('ADD' === action) {
            smallBannerAddButton.style.display = 'none';
            smallBannerDeleteButton.style.display = '';
            smallBannerImg.setAttribute('style', 'width: 100%;');
        }

        if ('DELETE' === action) {
            smallBannerAddButton.style.display = '';
            smallBannerDeleteButton.style.display = 'none';
            smallBannerImg.removeAttribute('style');
        }
    };

    // Large Banner Visibility
    var largeBannerToggleVisibility = function (action) {
        if ('ADD' === action) {
            largeBannerAddButton.style.display = 'none';
            largeBannerDeleteButton.style.display = '';
            largeBannerImg.setAttribute('style', 'width: 100%;');
        }

        if ('DELETE' === action) {
            largeBannerAddButton.style.display = '';
            largeBannerDeleteButton.style.display = 'none';
            largeBannerImg.removeAttribute('style');
        }
    };


    // Small Banner
    // Open up the WP Media Uploader modal. -JMS
    smallBannerAddButton.addEventListener('click', function () {
        if (smallBannerCustomUploader) {
            smallBannerCustomUploader.open();
        }
    });
    // Add an image to the tab. -JMS
    smallBannerCustomUploader.on('select', function () {
        var smallBannerAttachment = smallBannerCustomUploader.state().get('selection').first().toJSON();
        smallBannerImg.setAttribute('src', smallBannerAttachment.url);
        smallBannerHidden.setAttribute('value', JSON.stringify([{
            id: smallBannerAttachment.id,
            src: smallBannerAttachment.url,
            alt: smallBannerAttachment.alt
        }]));
        smallBannerToggleVisibility('ADD');
    });
    // Remove the image. -JMS
    smallBannerDeleteButton.addEventListener('click', function () {
        smallBannerImg.removeAttribute('src');
        smallBannerHidden.removeAttribute('value');
        smallBannerToggleVisibility('DELETE');
    });


    // Large Banner
    // Open up the WP Media Uploader modal. -JMS
    largeBannerAddButton.addEventListener('click', function () {
        if (largeBannerCustomUploader) {
            largeBannerCustomUploader.open();
        }
    });

    // Add an image to the tab. -JMS
    largeBannerCustomUploader.on('select', function () {
        var largeBannerAttachment = largeBannerCustomUploader.state().get('selection').first().toJSON();
        largeBannerImg.setAttribute('src', largeBannerAttachment.url);
        largeBannerHidden.setAttribute('value', JSON.stringify([{
            id: largeBannerAttachment.id,
            src: largeBannerAttachment.url,
            alt: largeBannerAttachment.alt
        }]));
        largeBannerToggleVisibility('ADD');
    });

    // Remove the image. -JMS
    largeBannerDeleteButton.addEventListener('click', function () {
        largeBannerImg.removeAttribute('src');
        largeBannerHidden.removeAttribute('value');
        largeBannerToggleVisibility('DELETE');
    });

    window.addEventListener('DOMContentLoaded', function () {
        if ("" === customUploads.smallBannerData || 0 === customUploads.smallBannerData.length) {
            smallBannerToggleVisibility('DELETE')
        } else {
            smallBannerImg.setAttribute('src', customUploads.smallBannerData.src);
            smallBannerImg.setAttribute('style', 'width: 100%;');
            smallBannerHidden.setAttribute('value', JSON.stringify([customUploads.smallBannerData]));
            smallBannerToggleVisibility('ADD');
        }
        if ("" === customUploads.largeBannerData || 0 === customUploads.largeBannerData.length) {
            largeBannerToggleVisibility('DELETE')
        } else {
            largeBannerImg.setAttribute('src', customUploads.largeBannerData.src);
            largeBannerImg.setAttribute('style', 'width: 100%;');
            largeBannerHidden.setAttribute('value', JSON.stringify([customUploads.largeBannerData]));
            largeBannerToggleVisibility('ADD');
        }
    });


    // Get the Flatpickr datepicker ready
    $('#post-expiration').flatpickr({
        enableTime: true,
        minuteIncrement: 1
    });
});