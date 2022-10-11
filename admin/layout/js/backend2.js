$(function () {
    'use strict';

    var pass = $('.password');

    $('.show-pass').hover(function () {
        pass.attr('type', 'text');
    }, function () {
        pass.attr('type', 'password');
    });

    $('.confirm').click(function () {
        return confirm("Are you sure you want to delete ?");
    });
});