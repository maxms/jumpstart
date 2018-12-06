<?php
/**
 * The template used for displaying the
 * header content within header.php.
 *
 * @link https://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.7.0
 */
?>
<header id="branding" <?php themeblvd_header_class(); ?>>

	<div class="wrap clearfix">

		<?php
		/**
		 * Fires at the top of the header.
		 *
		 * By default, this is where the optional
		 * header top bar gets displayed.
		 *
		 * @hooked themeblvd_header_top_default - 10
		 *
		 * @since Theme_Blvd 2.0.0
		 */
		do_action( 'themeblvd_header_top' );

		/**
		 * Fires where the content of the header
		 * goes.
		 *
		 * @hooked themeblvd_header_content_default - 10
		 *
		 * @since Theme_Blvd 2.0.0
		 */
		do_action( 'themeblvd_header_content' );

		/**
		 * Fires where the main menu goes.
		 *
		 * @hooked themeblvd_header_menu_default - 10
		 *
		 * @since Theme_Blvd 2.0.0
		 */
		do_action( 'themeblvd_header_menu' );
		?>

	</div><!-- .wrap -->

</header><!-- #branding -->
