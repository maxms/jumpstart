<?php
/**
 * Deprecated API helper functions.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.7.0
 */

/**
 * Add slider type.
 *
 * This function is deprecated and will no longer
 * actually do anything.
 *
 * @since @@name-framework 2.1.0
 * @deprecated @@name-framework 2.7.0
 *
 * @param string $slider_id       ID for new slider type.
 * @param string $slider_name     Name for new slider type.
 * @param array  $slide_types     Slides types, `image`, `video` or `custom`.
 * @param array  $media_positions Positions for media, `full`, `align-left` or `align-right`.
 * @param array  $slide_elements  Elements to include in slides, `image_link`, `headline`, `description` or `button`.
 * @param array  $options         Options formatted for Options Framework.
 * @param string $callback        Function to display slider on frontend.
 */
function themeblvd_add_slider( $slider_id, $slider_name, $slide_types, $media_positions, $slide_elements, $options, $callback ) {

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.7.0',
		null,
		__( 'The Theme Blvd Sliders plugin is no longer supported.' , '@@text-domain' )
	);

}

/**
 * Remove slider type.
 *
 * This function is deprecated and will no longer
 * actually do anything.
 *
 * @since @@name-framework 2.3.0
 * @deprecated @@name-framework 2.7.0
 *
 * @param string $slider_id ID for slider type to remove.
 */
function themeblvd_remove_slider( $slider_id ) {

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.7.0',
		null,
		__( 'The Theme Blvd Sliders plugin is no longer supported.' , '@@text-domain' )
	);

}
