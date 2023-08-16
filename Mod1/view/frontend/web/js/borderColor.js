define(['jquery'], function ($) {
    /*$('.button-change-border-color').on('click', function () {
        $(".studyid-index-index").css({'border' : 'solid 10px #0A4B06FF'});
        $(".input-form").css({'border' : 'solid 10px #88189d'});
    });*/

    $.widget('studynamespace.borderWidget', {
        options: {

        },
        _create: function () {
            $('.button-change-border-color').on('click', this.changeBorderColor.bind(this));
        },
        changeBorderColor: function () {
                $(".studyid-index-index").css({'border': 'solid 10px #0A4B06FF'});
                $(".input-form").css({'border': 'solid 10px #88189d'});

        }
    })

    return $.studynamespace.borderWidget;
})
