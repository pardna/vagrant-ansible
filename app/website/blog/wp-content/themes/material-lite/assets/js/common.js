jQuery(document).ready(function($) {
    jQuery('<i class="material-icons sub-dropdown">arrow_drop_down</i>').insertBefore('ul.sub-menu')
    jQuery( '.menu-toggle' ).click( function( e ) {
        jQuery(this).parent().toggleClass('open');
    });
    jQuery( '.sub-dropdown' ).click( function( e ) {
        e.stopPropagation();
        jQuery(this).next().toggleClass('open');
        if (jQuery(this).text() =='arrow_drop_down') {
            jQuery(this).text('arrow_drop_up');
        }else {
            jQuery(this).text('arrow_drop_down');
        }
    });
});