(function($) {

    wp.customize('header_size', function (value) {
        value.bind( function ( newval ) {
            if (newval == 'small') {
                jQuery('header .header-big-size').hide();
            } else {
                jQuery('header .header-big-size').show();
            }
        });
    });

    wp.customize('site_width', function (value) {
        value.bind( function( newval ) {
            jQuery('.mdl-grid').css('max-width', newval);
        });
    });

    wp.customize('site_layout', function (value) {
        value.bind( function( newval ) {
            if (newval == 'one-column') {
                jQuery('#primary-sidebar').hide();
            } else {
                jQuery('#primary-sidebar').show();
            }
            jQuery('#primary').toggleClass('mdl-cell--9-col-desktop mdl-cell--12-col-desktop');
        });
    });

    wp.customize('favicon', function (value, t) {
        value.bind( function( newval ) {
            if (newval == '') {
                jQuery('head #favicon').hide();
            } else {
                jQuery('head #favicon').attr('href', newval);
            }
        });
    });

    wp.customize('logo', function (value, t) {
        value.bind( function( newval ) {
            if (newval == '') {
                jQuery('header .img-logo').hide();
            } else {
                jQuery('header .img-logo').show().attr('src', newval);
            }
        });
    });

    wp.customize('site_title', function (value) {
        value.bind( function( newval ) {
            jQuery('header .site-title a').text(newval);
        });
    });

    wp.customize('mdl_custom_css', function (value) {
        value.bind( function( newval ) {
            console.log(newval)
            if (newval != '') {
                jQuery("<link/>", {
                   rel: "stylesheet",
                   type: "text/css",
                   href: newval,
                   id: 'material-lite-mdl-custom-css-css'
                }).insertBefore("head #material-lite-common-css-css");
            } else {
                jQuery('#material-lite-mdl-custom-css-css').remove();
            }
        });
    });

    socials = ['facebook', 'twitter', 'gplus', 'linkedin', 'youtube', 'blogger', 'pinterest', 'instagram', 'tumblr'];
    wp.customize('social_facebook', function (value, t) {
        value.bind( function( newval ) {
            if (newval == '') {
                jQuery('.social-icons a.facebook').addClass('hide');
            } else {
                jQuery('.social-icons a.facebook').removeClass('hide').attr('href', newval);
            }
        });
    });
    wp.customize('social_twitter', function (value, t) {
        value.bind( function( newval ) {
            if (newval == '') {
                jQuery('.social-icons a.twitter').addClass('hide');
            } else {
                jQuery('.social-icons a.twitter').removeClass('hide').attr('href', newval);
            }
        });
    });
    wp.customize('social_gplus', function (value, t) {
        value.bind( function( newval ) {
            if (newval == '') {
                jQuery('.social-icons a.gplus').addClass('hide');
            } else {
                jQuery('.social-icons a.gplus').removeClass('hide').attr('href', newval);
            }
        });
    });
    wp.customize('social_linkedin', function (value, t) {
        value.bind( function( newval ) {
            if (newval == '') {
                jQuery('.social-icons a.linkedin').addClass('hide');
            } else {
                jQuery('.social-icons a.linkedin').removeClass('hide').attr('href', newval);
            }
        });
    });
    wp.customize('social_youtube', function (value, t) {
        value.bind( function( newval ) {
            if (newval == '') {
                jQuery('.social-icons a.youtube').addClass('hide');
            } else {
                jQuery('.social-icons a.youtube').removeClass('hide').attr('href', newval);
            }
        });
    });
    wp.customize('social_blogger', function (value, t) {
        value.bind( function( newval ) {
            if (newval == '') {
                jQuery('.social-icons a.blogger').addClass('hide');
            } else {
                jQuery('.social-icons a.blogger').removeClass('hide').attr('href', newval);
            }
        });
    });
    wp.customize('social_pinterest', function (value, t) {
        value.bind( function( newval ) {
            if (newval == '') {
                jQuery('.social-icons a.pinterest').addClass('hide');
            } else {
                jQuery('.social-icons a.pinterest').removeClass('hide').attr('href', newval);
            }
        });
    });
    wp.customize('social_instagram', function (value, t) {
        value.bind( function( newval ) {
            if (newval == '') {
                jQuery('.social-icons a.instagram').addClass('hide');
            } else {
                jQuery('.social-icons a.instagram').removeClass('hide').attr('href', newval);
            }
        });
    });
    wp.customize('social_tumblr', function (value, t) {
        value.bind( function( newval ) {
            if (newval == '') {
                jQuery('.social-icons a.tumblr').addClass('hide');
            } else {
                jQuery('.social-icons a.tumblr').removeClass('hide').attr('href', newval);
            }
        });
    });
})(jQuery);