"use strict";

var scriptElement = $("script[src*='main.min.js']");
var base_url = scriptElement.attr('data-base-url');
var search_type_relations_value = scriptElement.attr('data-search-type-relations-value');

var rejstriky = {

    init: function () {

        var _ = this;

        //this.slickInit();
        //this.smoothScroll();

        _.slickInit();
        _.tooltip();
        _.advancedBox();
        _.countEqual();
        _.dlEqual();
        _.accordion();
        _.search();
        _.account();
        _.formRS();
        _.fileInput();
        _.toggleFormElement();
        _.initializeDeleteConfirm();
        _.initializePrintButton();
        _.initializeShareButton();
        _.initializeSearchAutocomplete();
        _.initializeBlurredClick();
        _.initializeAddToWatchClick();

        $('*[data-animation="play"]').on('click', function (e) {
            e.preventDefault();
            _.animationPlay();
        });
        $(window).on('load', function () {
            setTimeout(function () {
                _.animationPlay();
            }, 250);
        });

        $(window).on('resize', function () {
            _.countEqual();
            _.dlEqual();
        });


    },

    createCookie: function (name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        }
        document.cookie = name + "=" + value + expires + "; path=/";
    },

    readCookie: function (name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) {
                return c.substring(nameEQ.length, c.length);
            }
        }
        return null;
    },

    smoothScroll: function () {

        // Select all links with hashes
        $('a[href*="#"]')
        // Remove links that don't actually link to anything
            .not('[href="#"]')
            .not('[href="#0"]')
            .click(function (event) {
                // On-page links
                if (
                    location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                    &&
                    location.hostname === this.hostname
                ) {
                    // Figure out element to scroll to
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    // Does a scroll target exist?
                    if (target.length) {
                        // Only prevent default if animation is actually gonna happen
                        event.preventDefault();
                        $('html, body').animate({
                            scrollTop: target.offset().top
                        }, 1000, function () {
                            // Callback after animation
                            // Must change focus!
                            var $target = $(target);
                            $target.focus();
                            if ($target.is(":focus")) { // Checking if the target was focused
                                return false;
                            } else {
                                $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                                $target.focus(); // Set focus again
                            }
                        });
                    }
                }
            });

    },

    slickInit: function () {

        if ($('.hp__testimonials__list').length > 0) {
            $('.hp__testimonials__list').slick({
                slidesToShow: 7,
                slidesToScroll: 7,
                autoplay: false,
                dots: true,
                touchMove: true,
                touchThreshold: 10,
                adaptiveHeight: false,
                arrows: true,
                fade: false,
                responsive: [
                    {
                        breakpoint: 980,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: "unslick"
                    }
                ]
            });
        }

        if ($('.rs_hp__hero__slider').length > 0) {
            $('.rs_hp__hero__slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: false,
                dots: false,
                touchMove: false,
                adaptiveHeight: false,
                arrows: false,
                fade: false,
                responsive: [
                    {
                        breakpoint: 767,
                        settings: {
                            adaptiveHeight: true
                        }
                    }
                ]
            });
        }

        if ($('.rs_hp__hero__handle').length) {
            $('.rs_hp__hero__slider').slick('slickGoTo', parseInt($('.rs_hp__hero__handle').find('li.active').index()), true);
        }

        $('.rs_hp__hero__handle').on('click', 'a', function (e) {
            e.preventDefault();
            $('.rs_hp__hero__handle').find('li').removeClass('active');
            $(this).parent().addClass('active');
            $('.rs_hp__hero__slider').slick('slickGoTo', parseInt($(this).parent().index()), true);
        });

    },

    tooltip: function () {


        $('body').on('click', function (e) {
            if ($(this).find('.tooltip__content:visible').length && !$(e.target).closest('.tooltip').length) {
                $(this).find('.tooltip__content:visible').stop(true, true).fadeOut(250);
            }
        });

        var tooltipOut = null;
        $(document).on('mouseenter', '.tooltip__handle', function() {
            if ($(window).width() > 767) {
                $('.tooltip__content:visible').fadeOut(50);
                $(this).parents('.tooltip').find('.tooltip__content').stop(true, true).fadeIn(250);
            }
        });
        $(document).on('mouseenter', '.tooltip', function() {
            if ($(window).width() > 767) {
                clearTimeout(tooltipOut);
            }
        });
        $(document).on('mouseleave', '.tooltip', function() {
            var el = $(this);
            tooltipOut = setTimeout(function () {
                el.find('.tooltip__content').stop(true, true).fadeOut(250);
            }, 150);
        });

    },

    advancedBox: function () {

        $('.main_header__search__advanced').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('main_header__search__advanced--active');
            $('.main_header__search__more-box').slideToggle(250);
        });

    },

    countEqual: function () {

        var items = {};
        $('*[data-equal-group]').each(function () {
            items[$(this).attr('data-equal-group')] = true;
        });

        var result = new Array();
        for (var i in items) {
            result.push(i);
        }
        $(result).each(function (i) {
            var el = $("*[data-equal-group=" + result[i] + "]");

            el.css('min-height', '').removeClass('equalized');

            if (el.hasClass('product__card') && el.first().parent().outerWidth(true) == el.first().parent().parent().width()) {
                return;
            }
            var max_height = 0;
            el.each(function () {
                if ($(this).outerHeight() > max_height) {
                    max_height = $(this).outerHeight();
                }
            }).css('min-height', max_height + 'px').addClass('equalized');
        });

    },

    dlEqual: function () {

        $('.dl_equal').each(function () {

            $(this).find('dt').css('min-width', '');
            var maxWidth = 0;
            $(this).find('dt').each(function () {
                if ($(this).outerWidth() > maxWidth) {
                    maxWidth = $(this).outerWidth() + 1;
                }
            });

            $(this).find('dt').css('min-width', maxWidth + 'px');
        });

    },

    accordion: function () {

        var _ = this;
        $('.accordion__item__header').on('click', 'h2, h3', function (e) {
            e.preventDefault();
            var accordionItem = $(this).parents('.accordion__item');
            if (accordionItem.hasClass('accordion__item--active')) {
                accordionItem.find('.accordion__item__content').slideUp(250, function () {
                    accordionItem.removeClass('accordion__item--active');
                });
            } else {
                accordionItem.find('.accordion__item__content').slideDown(250, function () {
                    _.dlEqual();
                    accordionItem.addClass('accordion__item--active');
                })
            }
        });

    },

    search: function () {

        $('.main_header__search__tabs').find('input[type="radio"]:checked').parents('li').addClass('main_header__search__tabs__tab--active');
        $('.main_header__search__tabs').on('change', 'input[type="radio"]', function (e) {
            e.preventDefault();
            $('.main_header__search__tabs__tab--active').removeClass('main_header__search__tabs__tab--active');
            $(this).parents('li').addClass('main_header__search__tabs__tab--active');
        });

    },

    animationPlay: function () {

        var _ = this;

        if ($('.animation-how-to').length > 0) {

            var el = $('.animation-how-to');

            el.addClass('playing');
            el.addClass('step1');
            window.setTimeout(function () {
                el.addClass('step2');
            }, 600);
            window.setTimeout(function () {
                el.addClass('step3');
            }, 1200);
            window.setTimeout(function () {
                el.addClass('step4');
            }, 1800);
            window.setTimeout(function () {
                el.addClass('step5');
            }, 2400);
            window.setTimeout(function () {
                el.addClass('step6');
            }, 3000);
            window.setTimeout(function () {
                el.addClass('step7');
            }, 3600);
            window.setTimeout(function () {
                el.addClass('step8');
            }, 4200);
            window.setTimeout(function () {
                el.addClass('step9');
            }, 4800);
            window.setTimeout(function () {
                el.addClass('preload');
                setTimeout(function () {
                    el.removeClass('step1 step2 step3 step4 step5 step6 step7 step8 step9');
                    setTimeout(function () {
                        el.removeClass('preload');
                        _.animationPlay();
                    }, 500);
                }, 500);
            }, 5600);
        }

    },

    account: function () {

        $('a[data-account-handle="true"]').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active')
            $('.main_header__top_nav__account-popup').slideToggle(250, function () {
                $(this).css('display', '').toggleClass('active');
            });
        });

    },

    formRS: function () {

        $('a[data-toggle-side-menu="true"]').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $('.rs_form__switch').children('ul').toggleClass('active');
        });

    },

    fileInput: function () {

        $('input.styled_file').on('change', function(e) {
           $(this).next('label').find('.btn_desc').text( e.target.value.split( '\\' ).pop() );
        });

    },

    toggleFormElement: function() {

        $('*[data-toggle-form-element]').on('change', function(e) {
           if ( $(this).is(':checked') ) {
               $('*[name="'+ $(this).attr('data-toggle-form-element') +'"]').closest('.form_control_wrap').show(0).removeClass('hide');
           } else {
               $('*[name="'+ $(this).attr('data-toggle-form-element') +'"]').closest('.form_control_wrap').hide(0).addClass('hide');
           }
        });

    },

    initializeDeleteConfirm: function() {

        $('*[data-confirm-delete]').on('click', function(e) {
            return confirm('Opravdu smazat?');
        });

    },

    initializePrintButton: function() {

        $('*[data-print]').on('click', function(e) {
            window.print();
            return false;
        });

    },

    initializeShareButton: function() {

        var dialog = $("#share-dialog").dialog({
            autoOpen: false,
            height: 115,
            width: 430,
            modal: true
        });
       
        $('*[data-share]').on('click', function(e) {
            dialog.dialog("open");    
            return false;
        });
        
    },

    initializeSearchAutocomplete: function() {

        var subjectSearch = $("#subject-search")
            .autocomplete({
                source: function (request, response) {
                    var requestArray = $("#search-form").serializeArray();
                    
                    var requestObject = {};
                    $(requestArray).each(function(index, obj) {
                        requestObject[obj.name] = obj.value;
                    });  
                                     
                    var url = requestObject.type === search_type_relations_value 
                        ? base_url + "search/results_relations_input_ajax"
                        : base_url + "search/results_input_ajax";

                    request = $("#search-form").serialize();
                                    
                    $.post(url, request, function(data) {
                        var serialized = $.map(JSON.parse(data), function (item) {
                            return {
                                label: item.name,
                                value: item.link
                            }
                        });

                        response(serialized);   
                    });
                },    
                delay: 100,        
                minLength: 1
            })
            .autocomplete("instance");

        if (subjectSearch != undefined) {
            subjectSearch._renderItem = function(ul, item) {
                var value = item.value;
                item.value = "";
                return $("<li style='text-align: left; padding: 5px 0;'>")
                    .append("<a style='display: block;' href='" + value + "'>" + item.label + "</a>")
                    .appendTo(ul);
                
            };
        }
    },

    initializeBlurredClick: function() {
        var dialog = $("#register-dialog").dialog({
            autoOpen: false,
            height: 100,
            width: 500,
            modal: true
        });
       
        $('body').on('click', '.blurred', function() {
            dialog.dialog("open");    
            return false;
        });
    },

    initializeAddToWatchClick: function() {
        $('body').on('click', '[data-add-to-watch]', function() {
            $.get(base_url + 'monitoring/watches_add_direct/' + $(this).attr('data-ic'), function(result) {
                $('<div />')
                    .html(result)
                    .dialog({
                        autoOpen: false,
                        height: 115,
                        width: 430,
                        modal: true
                    })
                    .dialog('open');
            });

            return false;
        });
    }

};

jQuery(document).ready(function ($) {
    rejstriky.init();
});