<?php
class GhuwadMaterialLiteCustomize {
    public static function register($wp_customize) {
        $wp_customize->remove_section('colors');
        $wp_customize->remove_section('header_image');
        $wp_customize->remove_section('background_image');
        /*theme options*/
        $wp_customize->add_section('material_lite_options', array(
            'title' => __('Theme Options', 'material-lite'),
            'priority' => 35,
            'capability' => 'edit_theme_options',
            'description' => __('Allows you to customize settings for Material Lite theme.', 'material-lite'),
        ));
        $wp_customize->add_setting('site_width', array(
            'default' => '1080px',
            'type' => 'theme_mod',
            'transport' => 'postMessage',
            'sanitize_callback' => 'material_lite_sanitize_site_width'
        ));
        $wp_customize->add_control('site_width', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'material_lite_options',
            'label' => __('Site Wrapper Width', 'material-lite'),
            'description' => __('Please add px or % at end of value.', 'material-lite'),
        ));
        $wp_customize->add_setting('site_layout', array(
            'default' => 'two-column',
            'type' => 'theme_mod',
            'transport' => 'postMessage',
            'sanitize_callback' => 'material_lite_sanitize_site_layout'
        ));
        $wp_customize->add_control('site_layout', array(
            'type' => 'radio',
            'priority' => 2,
            'section' => 'material_lite_options',
            'choices' => array ('one-column' => 'One Column', 'two-column' => 'Two Column'),
            'label' => __( 'Site Layout', 'material-lite' )
        ));

        if (version_compare(get_bloginfo('version'), 4.3, '<')) {
            $wp_customize->add_setting( 'display_favicon', array(
                'default'    => false,
                'type'       => 'theme_mod',
                'sanitize_callback' => 'material_lite_sanitize_display_favicon'
            ));
            $wp_customize->add_control( 'display_favicon', array(
                'label'      => __( 'Display Favicon', 'material-lite' ),
                'section'    => 'material_lite_options',
                'type'       => 'checkbox',
                'std'         => '1'
            ));
            $wp_customize->add_setting('favicon', array(
                'default' => material_lite__var('home', 'url') . 'favicon.png',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
                'sanitize_callback' => 'esc_url_raw'
            ));
            $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'favicon', array(
                'label' => __('Favicon', 'material-lite'),
                'section' => 'material_lite_options',
                'priority' => 3,
                'type' => 'image',
                'mime_type' => 'image',
                'description' => __('If not visible, then clear browser cache.', 'material-lite'),
            )));
        }
        /*header section*/
        $wp_customize->add_section('header_options', array(
            'title' => __('Header', 'material-lite'),
            'priority' => 36,
            'capability' => 'edit_theme_options',
            'description' => __('Allows you to customize Header.', 'material-lite'),
        ));
        $wp_customize->add_setting( 'header_size', array(
            'default' => 'big',
            'type' => 'theme_mod',
            'transport' => 'postMessage',
            'sanitize_callback' => 'material_lite_sanitize_c_header_size'
        ));
        $wp_customize->add_control( 'header_size', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'header_options',
            'label' => __( 'Header Size', 'material-lite' ),
            'choices' => array ('big' => 'Big', 'small' => 'small'),
            'input_attrs' => array(
                'class' => 'my-custom-class-for-js',
                'style' => 'border: 1px solid #F00'
            ),
        ));
        $wp_customize->add_setting('logo', array(
            'default' => material_lite__var('images','url').'logo.png',
            'type' => 'theme_mod',
            'transport' => 'postMessage',
            'sanitize_callback' => 'esc_url_raw'
        ));
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'logo', array(
            'label' => __('Site Logo', 'material-lite'),
            'section' => 'header_options',
            'priority' => 3,
            'type' => 'image',
            'mime_type' => 'image',
        )));
        /*Footer Section*/
        $wp_customize->add_section('footer_options', array(
            'title' => __('Footer', 'material-lite'),
            'priority' => 37,
            'capability' => 'edit_theme_options',
            'description' => __('Allows you to customize Footer.', 'material-lite'),
        ));
        $socials = material_lite__var('social_fields');
        $i = 3;
        foreach ($socials as $social) {
            $wp_customize->add_setting( 'social_'.$social['field'], array(
                'default' => '',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
                'sanitize_callback' => 'esc_url_raw'
            ));
            $wp_customize->add_control( 'social_'.$social['field'], array(
                'type' => 'text',
                'priority' => $i++,
                'section' => 'footer_options',
                'label' => $social['name']
            ));
        }
        /*Custom CSS section*/
        $wp_customize->add_section('css_theme', array(
            'title' => __('Custom CSS','material-lite'),
            'priority' => 37,
            'capability' => 'edit_theme_options',
            'description' => __('Set color look & feel.', 'material-lite'),
        ));
        $wp_customize->add_setting( 'mdl_custom_css', array(
            'default' => '',
            'type' => 'theme_mod',
            'transport' => 'postMessage',
            'sanitize_callback' => 'esc_url_raw'
        ));
        $wp_customize->add_control( 'mdl_custom_css', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'css_theme',
            'label' => __( 'MDL CSS URL', 'material-lite' ),
            'description' => sprintf(__('Visit <a href="%s" target="_blank">MDL Custom CSS theme builder</a> and paste css cdn path. (only url) (<a href="%s" target="_blank">help</a>)', 'material-lite'), esc_url(__('http://www.getmdl.io/customize/index.html', 'material-lite')), esc_url(admin_url('themes.php?page=ghuwad-material-lite')))
        ));
    }

    public static function header_output() {
?>
<style type="text/css">
    <?php self::generate_css('.site-width', 'max-width', 'site_width'); ?>
</style>
    <?php
    }

    public static function live_preview() {
        wp_enqueue_script('ghuwad-material-lite-themecustomizer', get_template_directory_uri() . '/assets/js/theme-customizer.js', array( 'jquery', 'customize-preview' ) , '', true);
    }

    public static function generate_css($selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = true) {
        $return = '';
        $mod = esc_attr(get_theme_mod($mod_name));
        if (!empty($mod)) {
            $return = sprintf('%s { %s:%s; }', $selector, $style, $prefix . $mod . $postfix);
            if ($echo) {
                echo $return;
            }
        }
        return $return;
    }
}

add_action('customize_register', array(
    'GhuwadMaterialLiteCustomize',
    'register'
));
add_action('wp_head', array(
    'GhuwadMaterialLiteCustomize',
    'header_output'
));
add_action('customize_preview_init', array(
    'GhuwadMaterialLiteCustomize',
    'live_preview'
));