$(function () {
    'use strict';

    // switch between login and signup

    $('.login-box h1 span').click(function () {
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-box form').hide();
        $("." + $(this).data('class')).fadeIn(100);

    });
    // end of switch

// ////
    var pass = $('.password');

    $('.show-pass').hover(function () {
        pass.attr('type', 'text');
    }, function () {
        pass.attr('type', 'password');
    });

    $('.confirm').click(function () {
        return confirm("Are you sure you want to delete ?");
    });
    // ///////

    // start the live preview

    $('.live-name').keyup(function () {
        $('.live-preview .caption h3').text($(this).val());
    });
    $('.live-description').keyup(function () {
        $('.live-preview .caption p').text($(this).val());
    });
    $('.live-price').keyup(function () {
        $('.live-preview .price-tag').text($(this).val() + '$');
    });



    // end the live preview



});