<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if(!class_exists('GhuwadMaterialLiteWidgets')) {
    Class GhuwadMaterialLiteWidgets {
        public function __construct(){
            add_action( 'widgets_init', array( &$this, 'widgets_init' ) );
        }

        public function widgets_init() {
            register_sidebar( array(
                'name'          => __( 'Right Sidebar', 'material-lite' ),
                'id'            => 'primary-sidebar',
                'description'   => 'This is the right sidebar if you are using a two column layout for site.',
                'before_widget' => '<aside id="%1$s" class="widget mdl-card mdl-shadow--2dp %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ) );

            register_sidebar( array(
                'name'          => __( 'Footer Column 1', 'material-lite' ),
                'id'            => 'footer-column-1',
                'description'   => 'The first column in footer top area.',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title mdl-color-text--white">',
                'after_title'   => '</h3>',
            ) );

            register_sidebar( array(
                'name'          => __( 'Footer Column 2', 'material-lite' ),
                'id'            => 'footer-column-2',
                'description'   => 'The second column in footer top area.',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title mdl-color-text--white">',
                'after_title'   => '</h3>',
            ) );

            register_sidebar( array(
                'name'          => __( 'Footer Column 3', 'material-lite' ),
                'id'            => 'footer-column-3',
                'description'   => 'The third column in footer top area.',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title mdl-color-text--white">',
                'after_title'   => '</h3>',
            ) );

            register_sidebar( array(
                'name'          => __( 'Footer Column 4', 'material-lite' ),
                'id'            => 'footer-column-4',
                'description'   => 'The fourth column in footer top area.',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title mdl-color-text--white">',
                'after_title'   => '</h3>',
            ) );
        }
    }

    return new GhuwadMaterialLiteWidgets;
}