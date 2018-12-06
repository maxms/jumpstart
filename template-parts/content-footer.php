<?php
/**
 * The template used for displaying the
 * footer content within footer.php.
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
<footer id="colophon" <?php themeblvd_footer_class(); ?>>

	<div class="wrap clearfix">

		<?php
		/**
		 * Fires above the footer content.
		 *
		 * @since Theme_Blvd 2.0.0
		 */
		do_action( 'themeblvd_footer_above' );

		/**
		 * Fires where the footer content goes.
		 *
		 * By default this is includes the footer
		 * column configured from the theme options.
		 *
		 * @hooked themeblvd_footer_content_default - 10
		 *
		 * @since Theme_Blvd 2.0.0
		 */
		do_action( 'themeblvd_footer_content' );

		/**
		 * Fires where the footer sub content goes.
		 *
		 * By default this includes the copyright
		 * info and the Footer Navigation menu
		 * location.
		 *
		 * @hooked themeblvd_footer_sub_content_default - 10
		 *
		 * @since Theme_Blvd 2.0.0
		 */
		do_action( 'themeblvd_footer_sub_content' );

		/**
		 * Fires below the footer content and sub
		 * content.
		 *
		 * By default this includes the collapsible
		 * widget area below the footer.
		 *
		 * @hooked themeblvd_footer_below_default - 10
		 *
		 * @since Theme_Blvd 2.0.0
		 */
		do_action( 'themeblvd_footer_below' );
		?>

	</div><!-- .wrap -->

</footer><!-- #colophon -->
