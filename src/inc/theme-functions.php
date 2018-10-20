<?php
/**
 * Theme-Specific Functions
 *
 * This file is included immediately after framework
 * runs and serves as basis for all theme-specific
 * modifications.
 *
 * All theme-specific functionality should be included
 * in the theme's root `inc` directory. And any files
 * in there besides this one should be included from
 * this file.
 *
 * @author    Jason Bobich <info@themeblvd.com>
 * @copyright 2009-2017 Theme Blvd
 * @package   Jump_Start
 * @since     Jump_Start 2.0.0
 */

/**
 * Include in-dashboard update system.
 */
include_once( get_template_directory() . '/inc/theme-updates.php' );

/**
 * Global configuration, enable theme bases.
 *
 * @since Jump_Start 2.0.0
 *
 * @param  array $config Configuration settings from framework.
 * @return array $config Modified configuration settings.
 */
function jumpstart_global_config( $config ) {

	$config['admin']['base'] = true;

	return $config;

}
add_filter( 'themeblvd_global_config', 'jumpstart_global_config' );

/**
 * Include shared functions used for theme
 * bases.
 */
if ( themeblvd_supports( 'admin', 'base' ) ) {

	include_once( get_template_directory() . '/inc/theme-shared.php' );

}

/**
 * Setup theme bases admin.
 *
 * @since Jump_Start 2.0.0
 */
function jumpstart_bases() {

	if ( is_admin() && themeblvd_supports( 'admin', 'base' ) ) {

		/**
		 * Filters the theme bases added by the theme.
		 *
		 * @since Jump_Start 2.0.0
		 *
		 * @param array Theme bases being added.
		 */
		$bases = apply_filters( 'themeblvd_bases', array(
			'dev' => array(
				'name' => __( 'Developer', 'jumpstart' ),
				'desc' => __( 'If you\'re a developer looking to create a custom-designed child theme, this is the base for you.', 'jumpstart' ),
			),
			'superuser' => array(
				'name' => __( 'Super User', 'jumpstart' ),
				'desc' => __( 'For the super user, this base builds on the default theme to give you more visual, user options.', 'jumpstart' ),
			),
			'agent' => array(
				'name' => __( 'Agent', 'jumpstart' ),
				'desc' => __( 'A modern and open, agency-style design with a bit less user options.', 'jumpstart' ),
			),
			'entrepreneur' => array(
				'name' => __( 'Entrepreneur', 'jumpstart' ),
				'desc' => __( 'A more standard, corporate design with a lot of user options.', 'jumpstart' ),
			),
			'executive' => array(
				'name' => __( 'Executive', 'jumpstart' ),
				'desc' => __( 'A more classic, corporate design with a lot of user options.', 'jumpstart' ),
			),
		) );

		$admin = new Theme_Blvd_Bases( $bases, themeblvd_get_default_base() ); // Class included with is_admin().

	}

}
add_action( 'after_setup_theme', 'jumpstart_bases' );

/*
 * Include theme base.
 */
$base = themeblvd_get_base();

if ( $base ) {

	include_once( themeblvd_get_base_path( $base ) . '/base.php' );

}

/**
 * Enqueue Jump Start CSS files.
 *
 * @since 1.0.0
 */
function jumpstart_css() {

	$suffix = themeblvd_script_debug() ? '' : '.min';

	$theme = wp_get_theme( get_template() );

	$ver = $theme->get( 'Version' );

	$stylesheet_ver = $ver;

	if ( get_template() !== get_stylesheet() ) {

		$theme = wp_get_theme( get_stylesheet() );

		$stylesheet_ver = $theme->get( 'Version' );

	}

	/*
	 * Theme Stylesheet
	 */
	if ( is_rtl() ) {

		wp_enqueue_style(
			'jumpstart',
			esc_url( get_template_directory_uri() . "/assets/css/theme-rtl{$suffix}.css" ),
			array( 'themeblvd' ),
			$ver
		);

	} else {

		wp_enqueue_style(
			'jumpstart',
			esc_url( get_template_directory_uri() . "/assets/css/theme{$suffix}.css" ),
			array( 'themeblvd' ),
			$ver
		);

	}

	/*
	 * Theme Base Stylesheet
	 */
	$base = themeblvd_get_base();

	if ( $base && 'dev' !== $base ) {

		wp_enqueue_style(
			'jumpstart-base',
			esc_url( themeblvd_get_base_uri( $base ) . "/base{$suffix}.css" ),
			array( 'themeblvd', 'jumpstart' ),
			$ver
		);

	}

	/*
	 * IE Stylesheet
	 */
	wp_enqueue_style(
		'themeblvd-ie',
		esc_url( get_template_directory_uri() . '/assets/css/ie.css' ),
		array( 'themeblvd' ),
		$ver
	);

	$GLOBALS['wp_styles']->add_data( 'themeblvd-ie', 'conditional', 'IE' );

	/*
	 * Primary style.css
	 */
	wp_enqueue_style(
		'themeblvd-theme',
		esc_url( get_stylesheet_uri() ),
		array(),
		$stylesheet_ver
	);

}
add_action( 'wp_enqueue_scripts', 'jumpstart_css', 20 );

/**
 * Jump Start base check.
 *
 * If WP user is logged in, output message on frontend
 * to tell them their saved theme options don't match
 * the theme base they've selected.
 *
 * @since Jump_Start 2.0.0
 */
function jumpstart_base_check() {

	if ( ! is_user_logged_in() ) {

		return;

	}

	if ( ! themeblvd_supports( 'admin', 'base' ) ) {

		return;

	}

	if ( themeblvd_get_option( 'theme_base' ) == themeblvd_get_base() ) {

		return; // All good!

	}

	themeblvd_alert(
		array(
			'style' => 'warning',
			'class' => 'full',
		),
		__( 'Warning: Your saved theme options do not currently match the theme base you\'ve selected. Please configure and save your theme options page.', 'jumpstart' )
	);

}
add_action( 'themeblvd_before', 'jumpstart_base_check' );

/**
 * Add Jump Start Homepage to sample layouts of
 * Layout Builder plugin.
 *
 * @since Jump_Start 2.0.0
 *
 * @param  array $layouts All sample layouts.
 * @return array $layouts Modified sample layouts.
 */
function jumpstart_sample_layouts( $layouts ) {

	$layouts['jumpstart-home'] = array(
		'name'   => __( 'Jump Start: Home', 'jumpstart' ),
		'id'     => 'jumpstart-home',
		'dir'    => get_template_directory() . '/inc/layouts/home/',
		'uri'    => get_template_directory_uri() . '/inc/layouts/home/',
		'assets' => get_template_directory_uri() . '/inc/layouts/home/img/',
	);

	return $layouts;

}
add_filter( 'themeblvd_sample_layouts', 'jumpstart_sample_layouts' );
