<!DOCTYPE html>
<html <?php language_attributes() ?>>
	<head>

		<meta name="msvalidate.01" content="A728D597CD41A3CB78810B4A099886E4" />
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'web2feel' ), max( $paged, $page ) );

	?></title>

		  	<!-- Set the viewport width to device width for mobile -->
		  	<meta name="viewport" content="width=device-width" />

		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?><!-- enables nested comments in WP 2.7 -->
		
		<!-- Custom CSS -->
		<?php include (TEMPLATEPATH . '/includes/css-options.php'); ?>
		
		<!-- CSS -->
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		
		<?php wp_head(); //leave for plugins ?>
		
		<!-- TradeDoubler site verification 2147723 -->
		
		<link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/favicons/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/favicons/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-touch-icon-60x60.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/favicons/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/favicons/apple-touch-icon-76x76.png" />
    <link rel="icon" type="image/png" href="/favicons/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="/favicons/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/favicons/favicon-96x96.png" sizes="96x96" />
    <meta name="msapplication-TileColor" content="#da532c" />
    <meta name="msapplication-TileImage" content="/favicons/mstile-144x144.png" />
    <meta name="msapplication-square70x70logo" content="/favicons/mstile-70x70.png" />
    <meta name="msapplication-square150x150logo" content="/favicons/mstile-150x150.png" />

		
	</head>
	<body <?php body_class(''. stripslashes(of_get_option('layout')) .''); ?>>
		<div class="wrapper" id="pre_header">
			<div class="container" id="pre_header_container">
				<div id="pre_message"><?php echo stripslashes(of_get_option('pre_message')); ?></div>
			</div>
		</div>
		<div class="wrapper" id="header">
			<div class="container">
				<?php if (of_get_option('logo') != '') { ?>
				<a href="<?php echo site_url(); ?>" title="Back home" class="left the_logo">
					<img src="<?php echo stripslashes(of_get_option('logo')); ?>" alt="<?php bloginfo('name'); ?>" id="logo" />
				</a>
					<?php } else { ?>
				<h1 class="left the_logo"><a href="<?php echo site_url(); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a>
				</h1>
					<?php } ?>
				<div id="main_menu">
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'depth' => '2' ) ); ?>
				</div>
				<div id="clear"></div>
			</div>
		</div>
		<div class="wrapper" id="content"> <!-- #content ends in footer.php -->
			<div class="container">
