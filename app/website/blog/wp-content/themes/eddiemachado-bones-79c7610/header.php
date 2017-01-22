<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title><?php wp_title(''); ?></title>

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldF riendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-touch-icon.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">
            <meta name="theme-color" content="#121212">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>
        
        

		<?php // drop Google Analytics Here ?>
		<?php // end analytics ?>
        
        <script src="<?php echo esc_url( home_url( '../' ) ); ?>js/jquery-3.1.1.min.js"></script>
        <script src="<?php echo esc_url( home_url( '../' ) ); ?>js/materialize.min.js"></script>
        <script src="<?php echo esc_url( home_url( '../' ) ); ?>js/init.js"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo esc_url( home_url( '../' ) ); ?>css/materialize.min.css" media="all" />
        <link rel="stylesheet" type="text/css" href="<?php echo esc_url( home_url( '../' ) ); ?>css/style.css" media="all" />
        <link rel="stylesheet" type="text/css" href="<?php echo esc_url( home_url( '../' ) ); ?>css/blog-style.css" media="all" />
        <style>
            @media only screen and (max-width: 860px) {.video { display: block; }}
            @media only screen and (max-width: 992px) {.parallax-container .section { position: relative; margin-top: 0;}}
        </style>
    </head>

	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

		<div id="container">
            
            <!--- Header
------------------------------------------------------------------------->
<?php include '../inc/_header.php';?> 
            
            


