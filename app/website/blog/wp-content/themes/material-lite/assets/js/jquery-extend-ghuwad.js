(function ( $ ) {
    $.assignTab = function(){
        jQuery(document).on('click', '.gnav > a', function(event) {
            jQuery('> a',jQuery(this).parent()).each(function(index, ele){
                id = '#gtab-'+jQuery(ele).data('tab');
                //jQuery(id).hide();
                jQuery(id).hide().removeClass('open');
                jQuery(this).removeClass('active');
            });
            id = '#gtab-'+jQuery(this).data('tab');
            jQuery(id).slideDown();
            jQuery(this).addClass('active');
            console.log(id);
        });
    }
}( jQuery ));