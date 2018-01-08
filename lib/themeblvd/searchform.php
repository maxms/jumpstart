<?php
/**
 * Template for displaying a search form.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */
?>
<div <?php themeblvd_searchform_class(); ?>>

	<form method="get" action="<?php themeblvd_home_url(); ?>">

		<div class="search-wrap">

			<i class="search-icon <?php echo esc_attr( themeblvd_get_icon_class( 'search' ) ); ?>"></i>

			<input type="search" class="search-input" name="s" placeholder="<?php echo themeblvd_get_local( 'search' ); ?>" />

			<button class="search-submit btn-primary" type="submit">
				<?php echo esc_html( themeblvd_get_local( 'search_2' ) ); ?>
			</button>

		</div>

	</form>

</div><!-- .tb-search -->