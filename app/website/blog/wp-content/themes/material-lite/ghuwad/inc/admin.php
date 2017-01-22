<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
Class GhuwadMaterialLiteAdmin{
    public function __construct() {
        add_action( 'admin_init', array( &$this, 'admin_init') );
        add_action( 'admin_menu', array( &$this, 'admin_menu' ), 9 );
    }
    function admin_init() {
        wp_register_style( 'ghuwad-material-lite-css-admin', material_lite__var('css','url') . 'admin/admin-styles.css');

        $this->registersetting();
        global $ginit ;
        $ginit = 1;

        if(isset($_REQUEST['g_request'])){
            switch($_REQUEST['g_request']){
                case 'contact-us':
                    include material_lite__var('admin_view','path').'default.php';
                    break;
            }
        }
        $ginit = 0;
    }

    public function registersetting() {
        register_setting( 'ghuwad-material-lite-settings', 'ghuwad-material-lite-settings' );
    }

    function admin_enqueue() {
        wp_enqueue_style( 'ghuwad-material-lite-css-admin' );
        wp_enqueue_script( 'ghuwad-material-lite-jquery-extend-script', material_lite__var('js','url') . 'jquery-extend-ghuwad.js', array('jquery')  );
        wp_enqueue_script( 'ghuwad-material-lite-admin-script', material_lite__var('js','url') . 'admin/admin-scripts.js', array('jquery')  );
    }

    public function admin_menu() {
        global $menu;
        $main_page = add_theme_page('MaterialLite Options', 'MaterialLite Options', 'edit_theme_options', 'ghuwad-material-lite', array(&$this,'router'));
        add_action('admin_print_scripts-' . $main_page, array( &$this,'admin_enqueue' ));
    }

    public function router() {
        get_template_part('ghuwad/inc/views/admin/default');
    }
}

return new GhuwadMaterialLiteAdmin;