<?php
/**
 * @version    1.0
 * @package    Material Lite
 * @author     Ghuwad Team <contact@ghuwad.com>
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.ghuwad.com
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width">
	<meta name="mobile-web-app-capable" content="yes">
	<title><?php wp_title( ' ', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
    <?php material_lite_theme_favicon(); ?>
</head>

<body <?php body_class('mdl-color--grey-100 mdl-color-text--grey-700 mdl-base'); ?>>
    <div id="page" class="hfeed site mdl-layout mdl-layout--fixed-header">
		<header id="masthead" class="site-header mdl-layout__header mdl-layout__header--scroll mdl-color--primary  <?php echo material_lite__tx('header_size', 'small') ? 'small-header':''; ?>">
			<div class="mdl-layout__content">
				<div class="mdl-grid site-width mdl-grid--no-spacing">
					<div class="mdl-cell mdl-cell--8-col">
						<div class="mdl-layout--large-screen-only header-big-size <?php echo material_lite__tx('header_size', 'small') ? 'hide':''; ?> mdl-layout__header-row"></div>
						<div class="mdl-layout__header-row">
							<div class="site-title-wrap">
								<a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                                    <?php
                                    $logo = material_lite__t('logo');
                                    if(!empty($logo)) {
                                        echo '<img class="img-logo" src="',esc_url($logo),'" alt='.esc_attr(get_bloginfo('name')).'>';
                                    }
                                    ?>
                                    <?php if(display_header_text()) {?>
									<h3 class="site-title"><?php bloginfo('name'); ?></h3>
                                    <?php }?>
                                </a>
                            </div>
                        </div>
                        <div class="mdl-layout--large-screen-only header-big-size mdl-layout__header-row <?php echo material_lite__tx('header_size', 'small') ? 'hide':''; ?> "></div>
                    </div>
                    <div class="mdl-cell mdl-cell--8-col mdl-cell--4-col-desktop text-center">
                        <div class="mdl-layout--large-screen-only header-big-size mdl-layout__header-row <?php echo material_lite__tx('header_size', 'small') ? 'hide':''; ?> "></div>
                        <?php get_search_form(); ?>
                    </div>
                </div>
                <?php if(display_header_text()) {?>
                <div class="mdl-grid site-width mdl-grid--no-spacing make-touch-description">
                    <h4 class="site-description "><?php bloginfo( 'description' ); ?></h4>
                </div>
                <?php }?>
                <?php if ( has_nav_menu( 'main_menu' ) ) { ?>
				<div class="mdl-color--primary-dark">
					<div class="mdl-grid site-width main-navigation mdl-grid--no-spacing">
						<div class="menu-toggle mdl-layout--small-screen-only "><?php _e( 'Menu', 'material-lite' ); ?> <i class="material-icons">menu</i></div>
						<?php wp_nav_menu( array( 'theme_location' => 'main_menu', 'menu_class' => 'nav-menu', 'menu_id' => 'primary-menu'  ) ); ?>
					</div>
		          <div class="clear"></div>
		        </div>
		        <?php } ?>
			</div>
		</header>

		<div id="main" class="site-main">