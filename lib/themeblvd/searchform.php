<?php
/**
 * Template for displaying a search form.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */
?>
<div <?php themeblvd_searchform_class(); ?>>

	<form method="get" action="<?php themeblvd_home_url(); ?>">

		<div class="search-wrap">

			<?php echo themeblvd_get_icon( themeblvd_get_icon_class( themeblvd_get_search_icon_name(), array( 'search-icon' ) ) ); ?>

			<input type="search" class="search-input" name="s" placeholder="<?php echo themeblvd_get_local( 'search' ); ?>" />

			<button class="search-submit btn-primary" type="submit">
				<?php echo esc_html( themeblvd_get_local( 'search_2' ) ); ?>
			</button>

		</div>

	</form>

</div><!-- .tb-search -->
