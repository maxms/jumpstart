<?php
/**
 * Plugin Compatibility: Subtitles
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.6.0
 */

/**
 * Add extended Subtitles plugin compatibility.
 *
 * This class follows the singleton pattern,
 * meaning it can only be instantiated in
 * one instance.
 *
 * @since Theme_Blvd 2.6.0
 */
class Theme_Blvd_Compat_Subtitles {

	/**
	 * A single instance of this class.
	 *
	 * @since Theme_Blvd 2.6.0
	 */
	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since Theme_Blvd 2.6.0
	 *
	 * @return Theme_Blvd_Compat_Subtitles A single instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

	/**
	 * Constructor. Hook everything in.
	 *
	 * @since Theme_Blvd 2.6.0
	 */
	public function __construct() {

		$subtitles = Subtitles::getinstance();

		// Add support with Portfolios plugin.
		add_post_type_support( 'portfolio_item', 'subtitles' );

		// Remove default styles.
		remove_action( 'wp_head', array( $subtitles, 'subtitle_styling' ) );

		/*
		 * Make sure subtitles only output on single
		 * posts and pages.
		 */
		add_filter( 'subtitle_view_supported', array( $this, 'view' ) );

		/*
		 * Make sure no subtitle shows in instances of the title
		 * lower in a post, like with the title of the comments,
		 * for example.
		 */
		add_action( 'themeblvd_single_footer', array( $this, 'single_footer' ) );

		/*
		 * Add subtitles to post showcases.
		 *
		 * The subititle will replace the taxonomy terms output
		 * below the title when a featured image is hovered on.
		 */
		add_filter( 'themeblvd_item_info_subtitle', array( $this, 'item_info_subtitle' ), 10, 2 );

	}

	/**
	 * Make sure subtitles only outputs on single
	 * posts and pages.
	 *
	 * This method is filtered onto
	 * `subtitle_view_supported` - 10
	 *
	 * @since Theme_Blvd 2.6.0
	 *
	 * @param  bool $support Whether to display subtitle.
	 * @return bool $support Whether to display subtitle.
	 */
	public function view( $support ) {

		if ( ! is_singular() || themeblvd_get_att( 'doing_second_loop' ) ) {

			$support = false;

		}

		return $support;

	}

	/**
	 * Make sure no subtitle shows in instances of the
	 * title lower in a post, like with the title of
	 * the comments, for example.
	 *
	 * This method is hooked to:
	 * `themeblvd_single_footer` - 10
	 *
	 * @since Theme_Blvd 2.6.0
	 */
	public function single_footer() {

		add_filter( 'subtitle_view_supported', '__return_false' );

	}

	/**
	 * Display the subititle with post showcase
	 * featured images.
	 *
	 * This method is filtered onto:
	 * `themeblvd_item_info_subtitle` - 10
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param  string $input   HTML output for item subtitle.
	 * @param  int    $post_id Post ID.
	 * @return string $output  Modified HTML output for item subtitle.
	 */
	public function item_info_subtitle( $input, $post_id ) {

		$subtitle = get_the_subtitle( $post_id );

		if ( $subtitle ) {

			$output = $subtitle;

		} else {

			$output = $input;

		}

		return $output;

	}

}
