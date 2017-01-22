<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if(!class_exists('GhuwadMaterialLiteFront')) {
    Class GhuwadMaterialLiteFront {
        public function __construct(){
            add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
            add_action( 'the_password_form', array( &$this, 'the_password_form' ) );
        }
        public function the_password_form($value='') {
            global $post;
            $post = get_post( $post );
            $label = 'pwbox-' . ( empty($post->ID) ? rand() : $post->ID );
            $output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post"><div class="alert__warning"><i class="material-icons">warning</i>' . __( 'This content is certainly password protected. To view it please enter your password below:', 'material-lite' ) . '</div><div><div class="mdl-textfield mdl-js-textfield mdl-color-text--primary">
                <input class="mdl-textfield__input" name="post_password" value="" type="password" required1 id="' . $label . '" size="20" autocomplete="off"/>
                <label class="mdl-textfield__label" for="search">' . __( 'Password:', 'material-lite' ) . '</label>
            </div><button type="submit" class="mdl-button mdl-js-button mdl-button--with-icon mdl-button--raised mdl-js-ripple-effect mdl-button--accent"><i class="material-icons">lock_open</i> ' . __( 'Unlock Content', 'material-lite' ) . '</button>
            ';
            return $output;
        }
        public function enqueue_scripts() {
            wp_enqueue_style( 'material-lite-goole-font', 'https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en' );
            wp_enqueue_style( 'material-lite-material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons' );
            wp_enqueue_style( 'material-lite-style', Material_Lite_Theme::get('home','url') . '/style.css' );
            wp_enqueue_style( 'material-lite-mdl-css', Material_Lite_Theme::get('vendor','url') . 'mdl/material.min.css');
            $mdl_custom_css = material_lite__t('mdl_custom_css');
            if ($mdl_custom_css != '') {
                wp_enqueue_style( 'material-lite-mdl-custom-css', $mdl_custom_css);
            }
            wp_enqueue_style( 'material-lite-common-css', Material_Lite_Theme::get('css','url') . 'common.css');
            wp_enqueue_script( 'material-lite-mdl-js',  Material_Lite_Theme::get('vendor','url') . 'mdl/material.min.js', array(), '', true );

            if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
            }
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'material-lite-common-js',  Material_Lite_Theme::get('js','url') . 'common.js', array(), '', true );
        }
    }

    return new GhuwadMaterialLiteFront;
}