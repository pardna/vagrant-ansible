<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if(!class_exists('Material_Lite_Theme')) {

	class Material_Lite_Theme {
		static public $_var = array();
		public function __construct() {
			// Define constants
			$this->define_constants();

			// Include required files
			$this->includes();

			// setup theme
			$this->setup();
		}

		static public function init() {
	        // Named global variable to make access for other scripts easier.
	        $GLOBALS[ __CLASS__ ] = new self;
	    }

		private function setup() {
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'post-thumbnails' );
			add_theme_support( "title-tag" );
			add_theme_support( 'html5', array(
				'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
			));

			add_theme_support( 'post-formats', array(
				'aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio', 'chat'
			));
			register_nav_menus( array(
				'main_menu'   => __( 'Main Menu', 'material-lite' ),
			));
			$args = array(
				'height'                 => 241,
				'width'                  => 1080,
			);
			add_theme_support( 'custom-header', $args );
			add_theme_support( 'custom-background', array(
				'default-color' => 'ffffff',
				'default-image' => '',
			));
			add_image_size( 'card-thumb', 300, 300, true );

			add_action ('after_switch_theme', array( &$this, 'theme_activation') );
			add_editor_style( array( 'assets/css/editor-style.css') );
		}
		function theme_activation () {
			$v1 = material_lite__var('version');
		    $v2 = material_lite__var('install_version');

		    if ($v2 == '') { //first time activate
		    	set_theme_mod('site_width', '1080px');
		    	set_theme_mod('site_layout', 'two-column');
		    	set_theme_mod('header_size', 'big');
		    	set_theme_mod('mega_footer_layout', 'four-column');
		    	update_option(material_lite__var('unique').'_version', $v1);
		    }
		    if ( version_compare( $v1, $v2 ) > 0 ) { //do upgrade
		    }
		}

		private function define_constants() {
			$arr = array();
			$arr['version'] = '1.0.6';
			$arr['unique'] = 'material-lite';

			//paths
			$arr['path']['base'] = get_template_directory();
			$arr['path']['inc'] = $arr['path']['base'].'/ghuwad/inc/';
			$arr['path']['admin_view'] = $arr['path']['inc'].'views/admin/';

			$arr['lang'] = $arr['unique'];

			//urls
			$arr['url']['home'] = get_template_directory_uri().'/';
			$arr['url']['css'] = $arr['url']['home'].'assets/css/';
			$arr['url']['vendor'] = $arr['url']['home'].'assets/vendor/';
			$arr['url']['js'] = $arr['url']['home'].'assets/js/';
			$arr['url']['images'] = $arr['url']['home'].'assets/images/';
			$arr['url']['admin'] = get_option('siteurl').'/wp-admin/admin.php';
			$arr['install_version'] = get_option($arr['unique'].'_version');
			$arr['settings'] = get_theme_mods();
			$arr['settings'] = $arr['settings'] && is_array($arr['settings']) ? $arr['settings'] : array();
			$arr['social_fields'] = array(
					array('name'  => 'Facebook',
					   'field' => 'facebook',
					   'help'  => __('Enter your Facebook profile URL.', 'material-lite')),
					array('name'  => 'Google+',
					   'field' => 'gplus',
					   'help'  => __('Enter your Google+ profile URL.', 'material-lite')),
					array('name'  => 'Twitter',
						'field' => 'twitter',
						'help'  => __('Enter your Twitter acount profile URL.', 'material-lite')),
					array('name'  => 'LinkedIn',
					   'field' => 'linkedin',
					   'help'  => __('Enter your LinkedIn profile URL.', 'material-lite')),
					array('name'  => 'YouTube',
					   'field' => 'youtube',
					   'help'  => __('Enter your YouTube profile/channel URL.', 'material-lite')),
					array('name'  => 'Blogger',
					   'field' => 'blogger',
					   'help'  => __('Enter your Blogger URL.', 'material-lite')),
					array('name'  => 'Pinterest',
					   'field' => 'pinterest',
					   'help'  => __('Enter your Pinterest profile URL.', 'material-lite')),
					array('name'  => 'Instagram',
					   'field' => 'instagram',
					   'help'  => __('Enter your Instagram profile URL.', 'material-lite')),
					array('name'  => 'Tumblr',
					   'field' => 'tumblr',
					   'help'  => __('Enter your Tumblr profile URL.', 'material-lite')),
				);
			Material_Lite_Theme::$_var = $arr;
		}

		private function includes() {
			require_once Material_Lite_Theme::get('inc','path').'/common-functions.php';

			require_once Material_Lite_Theme::get('inc','path').'/widgets.php';
			if ( is_admin() ) {
				require_once Material_Lite_Theme::get('inc','path').'/admin.php';
			} else {
				require_once Material_Lite_Theme::get('inc','path').'/front.php';
			}
			require_once Material_Lite_Theme::get('inc','path').'/theme-customizer.php';
			if ( ! is_admin() || defined( 'DOING_AJAX' ) ) { //

			}
		}

		static public function get($key, $key1='') {
			if($key1=='' && isset(Material_Lite_Theme::$_var[$key])){
				return Material_Lite_Theme::$_var[$key];
			}else if(isset(Material_Lite_Theme::$_var[$key1][$key])){
				return Material_Lite_Theme::$_var[$key1][$key];
			}
			return '';
		}

		static public function getVar($key,$key1='') {
			if($key1 == '' && isset(Material_Lite_Theme::$_var[$key])) {
				return Material_Lite_Theme::$_var[$key];
			} else if(isset(Material_Lite_Theme::$_var[$key1][$key])) {
				return Material_Lite_Theme::$_var[$key1][$key];
			}
			return '';
		}

		public function setVar($key,$value) {
			Material_Lite_Theme::$_var[$key] = $value;
			return true;
		}
	}
	add_action( 'after_setup_theme', array ( 'Material_Lite_Theme', 'init' ) );
}