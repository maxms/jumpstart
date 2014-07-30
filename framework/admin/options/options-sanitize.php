<?php
/**
 * Sanitization Filters
 *
 * This function gets hooked to admin_init when the framework
 * runs on the admin side, but why have this in a function? --
 * Because on the frontend of the site, if the options have
 * never been saved we can call this function to allow default
 * option values to be generated. This will almost never happen,
 * so this is our way of including them, but only if needed;
 * no point in adding these filters every time the frontend loads.
 *
 * @since 2.2.0
 */
function themeblvd_add_sanitization() {
	add_filter( 'themeblvd_sanitize_hidden', 'themeblvd_sanitize_hidden', 10, 2 );
	add_filter( 'themeblvd_sanitize_text', 'themeblvd_sanitize_text' );
	add_filter( 'themeblvd_sanitize_textarea', 'themeblvd_sanitize_textarea' );
	add_filter( 'themeblvd_sanitize_select', 'themeblvd_sanitize_enum', 10, 2 );
	add_filter( 'themeblvd_sanitize_radio', 'themeblvd_sanitize_enum', 10, 2 );
	add_filter( 'themeblvd_sanitize_images', 'themeblvd_sanitize_enum', 10, 2 );
	add_filter( 'themeblvd_sanitize_checkbox', 'themeblvd_sanitize_checkbox' );
	add_filter( 'themeblvd_sanitize_multicheck', 'themeblvd_sanitize_multicheck', 10, 2 );
	add_filter( 'themeblvd_sanitize_color', 'themeblvd_sanitize_hex' );
	add_filter( 'themeblvd_sanitize_gradient', 'themeblvd_sanitize_gradient' );
	add_filter( 'themeblvd_sanitize_button', 'themeblvd_sanitize_button' );
	add_filter( 'themeblvd_sanitize_upload', 'themeblvd_sanitize_upload' );
	add_filter( 'themeblvd_sanitize_background', 'themeblvd_sanitize_background' );
	add_filter( 'themeblvd_background_repeat', 'themeblvd_sanitize_background_repeat' );
	add_filter( 'themeblvd_background_position', 'themeblvd_sanitize_background_position' );
	add_filter( 'themeblvd_background_attachment', 'themeblvd_sanitize_background_attachment' );
	add_filter( 'themeblvd_background_size', 'themeblvd_sanitize_background_size' );
	add_filter( 'themeblvd_sanitize_typography', 'themeblvd_sanitize_typography' );
	add_filter( 'themeblvd_font_face', 'themeblvd_sanitize_font_face' );
	add_filter( 'themeblvd_font_style', 'themeblvd_sanitize_font_style' );
	add_filter( 'themeblvd_font_face', 'themeblvd_sanitize_font_face' );
	add_filter( 'themeblvd_sanitize_columns', 'themeblvd_sanitize_columns' );
	add_filter( 'themeblvd_sanitize_tabs', 'themeblvd_sanitize_tabs' );
	add_filter( 'themeblvd_sanitize_testimonials', 'themeblvd_sanitize_testimonials' );
	add_filter( 'themeblvd_sanitize_toggles', 'themeblvd_sanitize_toggles' );
	add_filter( 'themeblvd_sanitize_content', 'themeblvd_sanitize_content' );
	add_filter( 'themeblvd_sanitize_logo', 'themeblvd_sanitize_logo' );
	add_filter( 'themeblvd_sanitize_social_media', 'themeblvd_sanitize_social_media' );
	add_filter( 'themeblvd_sanitize_slide', 'themeblvd_sanitize_slide' );
	add_filter( 'themeblvd_sanitize_slider', 'themeblvd_sanitize_slider' );
	add_filter( 'themeblvd_sanitize_logos', 'themeblvd_sanitize_logos' );
	add_filter( 'themeblvd_sanitize_conditionals', 'themeblvd_sanitize_conditionals', 10, 3 );
	add_filter( 'themeblvd_sanitize_editor', 'themeblvd_sanitize_editor' );
	add_filter( 'themeblvd_sanitize_editor_modal', 'themeblvd_sanitize_editor' );
	add_filter( 'themeblvd_sanitize_code', 'themeblvd_sanitize_editor' );
	add_filter( 'themeblvd_sanitize_locations', 'themeblvd_sanitize_locations' );
	add_filter( 'themeblvd_sanitize_geo', 'themeblvd_sanitize_geo' );
	add_filter( 'themeblvd_sanitize_sectors', 'themeblvd_sanitize_sectors' );
	add_filter( 'themeblvd_sanitize_datasets', 'themeblvd_sanitize_datasets' );
	add_filter( 'themeblvd_sanitize_bars', 'themeblvd_sanitize_bars' );
	add_filter( 'themeblvd_sanitize_buttons', 'themeblvd_sanitize_buttons' );
}

/**
 * Hidden
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_hidden( $input, $option ) {

	if ( $option['id'] == 'framework_version' ) {

		$output = TB_FRAMEWORK_VERSION;

	} else if ( $option['id'] == 'theme_version' ) {

		$theme = wp_get_theme( get_template() );
		$output = $theme->get('Version');

	} else {

		$allowedtags = themeblvd_allowed_tags();
		$output = wp_kses( $input, $allowedtags );
		$output = htmlspecialchars_decode( $output );
		$output = str_replace( "\r\n", "\n", $output );

	}
	return $output;
}

/**
 * Text
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_text( $input ) {
	$allowedtags = themeblvd_allowed_tags();
	$output = wp_kses( $input, $allowedtags );
	$output = htmlspecialchars_decode( $output );
	$output = str_replace( "\r\n", "\n", $output );
	return $output;
}

/**
 * Textarea
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_textarea( $input ) {
	$allowedtags = themeblvd_allowed_tags();
	$output = wp_kses( $input, $allowedtags );
	$output = htmlspecialchars_decode( $output );
	$output = str_replace( "\r\n", "\n", $output );
	return $output;
}

/**
 * Info
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_allowedtags( $input ) {
	$allowedtags = themeblvd_allowed_tags();
	$output = wpautop(wp_kses( $input, $allowedtags));
	return $output;
}

/**
 * Checkbox
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = "1";
	} else {
		$output = "0";
	}
	return $output;
}

/**
 * Multicheck
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_multicheck( $input, $option ) {
	$output = '';
	if ( is_array( $input ) ) {
		foreach ( $option['options'] as $key => $value ) {
			$output[$key] = "0";
		}
		foreach ( $input as $key => $value ) {
			if ( array_key_exists( $key, $option['options'] ) && $value ) {
				$output[$key] = "1";
			}
		}
	}
	return $output;
}

/**
 * Uploader
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_upload( $input ) {

	if ( is_array( $input ) ) {

		$output = array(
			'id'		=> 0,
			'src'		=> '',
			'full'		=> '',
			'title'		=> '',
			'crop'		=> '',
			'width'		=> 0,
			'height'	=> 0
		);

		if ( isset( $input['id'] ) ) {
			$output['id'] = intval( $input['id'] );
			$full = wp_get_attachment_image_src( $output['id'], 'full' );
			$output['full'] = $full[0];
		}

		if ( isset( $input['src'] ) ) {
			$output['src'] = wp_kses( $input['src'], array() );
		}

		if ( isset( $input['id'] ) ) {
			$output['id'] = intval( $input['id'] );
		}

		if ( isset( $input['title'] ) ) {
			$output['title'] = wp_kses( $input['title'], array() );
		}

		if ( isset( $input['crop'] ) ) {
			$output['crop'] = wp_kses( $input['crop'], array() );
		}

		if ( isset( $input['width'] ) ) {
			$output['width'] = intval( $input['width'] );
		}

		if ( isset( $input['height'] ) ) {
			$output['height'] = intval( $input['height'] );
		}

	} else {
		$output = wp_kses( $input, array() );
	}
	return $output;
}

/**
 * Check that the key value sent is valid
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_enum( $input, $option ) {
	$output = '';
	if ( isset( $option['options'] ) && is_array( $option['options'] ) ) {

		// Manual select, with standard options set
		if ( array_key_exists( $input, $option['options'] ) ) {
			$output = $input;
		}

	} else if ( isset( $option['select'] ) ) {

		// Dynamic Select
		$options = themeblvd_get_select( $option['select'], true );

		if ( array_key_exists( $input, $options ) ) {
			$output = $input;
		}
	}
	return $output;
}

/**
 * Background
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_background( $input ) {

	$output = wp_parse_args( $input, array(
		'color' 		=> '',
		'image'  		=> '',
		'repeat'  		=> 'repeat',
		'position' 		=> 'top center',
		'attachment' 	=> 'scroll',
		'size'			=> 'auto'
	) );

	if ( isset( $input['color'] ) ) { // color is optional, may not exist
		$output['color'] = apply_filters( 'themeblvd_sanitize_hex', $input['color'] );
	}

	$output['image'] = apply_filters( 'themeblvd_sanitize_upload', $input['image'] );
	$output['repeat'] = apply_filters( 'themeblvd_background_repeat', $input['repeat'] );
	$output['position'] = apply_filters( 'themeblvd_background_position', $input['position'] );
	$output['attachment'] = apply_filters( 'themeblvd_background_attachment', $input['attachment'] );
	$output['size'] = apply_filters( 'themeblvd_background_size', $input['size'] );

	return $output;
}

/**
 * Background - repeat
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_background_repeat( $value ) {
	$recognized = themeblvd_recognized_background_repeat();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_background_repeat', current( $recognized ) );
}

/**
 * Background - position
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_background_position( $value ) {
	$recognized = themeblvd_recognized_background_position();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_background_position', current( $recognized ) );
}

/**
 * Background - attachment
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_background_attachment( $value ) {
	$recognized = themeblvd_recognized_background_attachment();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_background_attachment', current( $recognized ) );
}

/**
 * Background - size
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_background_size( $value ) {
	$recognized = themeblvd_recognized_background_size();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_background_size', current( $recognized ) );
}

/**
 * Get recognized background positions
 *
 * @since 2.2.0
 */
function themeblvd_recognized_background_position() {
	$default = array(
		'center top'    => __('Background Position: Top Center', 'themeblvd'),
		'left top'      => __('Background Position: Top Left', 'themeblvd'),
		'right top'     => __('Background Position: Top Right', 'themeblvd'),
		'center center' => __('Background Position: Middle Center', 'themeblvd'),
		'left center'   => __('Background Position: Middle Left', 'themeblvd'),
		'right center'  => __('Background Position: Middle Right', 'themeblvd'),
		'center bottom' => __('Background Position: Bottom Center', 'themeblvd'),
		'left bottom'   => __('Background Position: Bottom Left', 'themeblvd'),
		'right bottom'  => __('Background Position: Bottom Right', 'themeblvd')
	);
	return apply_filters( 'themeblvd_recognized_background_position', $default );
}

/**
 * Get recognized background attachment
 *
 * @since 2.2.0
 */
function themeblvd_recognized_background_attachment() {
	$default = array(
		'scroll' 	=> __('Background Scrolling: Normal', 'themeblvd'),
		'parallax'  => __('Background Scrolling: Parallax Effect', 'themeblvd'),
		'fixed'  	=> __('Background Scrolling: Fixed in Place', 'themeblvd'),
	);
	return apply_filters( 'themeblvd_recognized_background_attachment', $default );
}

/**
 * Get recognized background repeat settings
 *
 * @since 2.2.0
 */
function themeblvd_recognized_background_repeat() {
	$default = array(
		'no-repeat' => __('Background Repeat: No Repeat', 'themeblvd'),
		'repeat-x'  => __('Background Repeat: Repeat Horizontally', 'themeblvd'),
		'repeat-y'  => __('Background Repeat: Repeat Vertically', 'themeblvd'),
		'repeat'    => __('Background Repeat: Repeat All', 'themeblvd')
	);
	return apply_filters( 'themeblvd_recognized_background_repeat', $default );
}

/**
 * Get recognized background positions
 *
 * @since 2.5.0
 */
function themeblvd_recognized_background_size() {
	$default = array(
		'auto'    	=> __('Background Size: Auto', 'themeblvd'),
		'cover'     => __('Background Size: Cover', 'themeblvd'),
		'contain'   => __('Background Size: Contain', 'themeblvd'),
		'100% 100%' => __('Background Size: 100% x 100%', 'themeblvd')
	);
	return apply_filters( 'themeblvd_recognized_background_size', $default );
}

/**
 * Typography
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_typography( $input ) {
	$output = wp_parse_args( $input, array(
		'size' 		=> '',
		'face'  	=> '',
		'style' 	=> '',
		'color' 	=> '',
		'google' 	=> ''
	) );
	$output['size']  = apply_filters( 'themeblvd_font_size', $output['size'] );
	$output['face']  = apply_filters( 'themeblvd_font_face', $output['face'] );
	$output['style'] = apply_filters( 'themeblvd_font_style', $output['style'] );
	$output['color'] = apply_filters( 'themeblvd_color', $output['color'] );
	$output['google'] = apply_filters( 'themeblvd_sanitize_text', $output['google'] );
	return $output;
}

/**
 * Typography - font size
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_font_size( $value ) {
	$recognized = themeblvd_recognized_font_sizes();
	$value = preg_replace('/px/','', $value);
	if ( in_array( (int) $value, $recognized ) ) {
		return (int) $value;
	}
	return (int) apply_filters( 'themeblvd_default_font_size', $recognized );
}

/**
 * Typography - font style
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_font_style( $value ) {
	$recognized = themeblvd_recognized_font_styles();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_font_style', 'normal' );
}

/**
 * Typography - font face
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_font_face( $value ) {
	$recognized = themeblvd_recognized_font_faces();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_font_face', current( $recognized ) );
}

/**
 * Columns
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_columns( $input ) {
	return wp_kses( $input, array() );
}

/**
 * Tabs
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_tabs( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['title'] = themeblvd_sanitize_text( $item['title'] );
			$output[$item_id]['content'] = themeblvd_sanitize_content( $item['content'] );
		}
	}

	return $output;
}

/**
 * Testimonials
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_testimonials( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['text'] = themeblvd_sanitize_textarea( $item['text'] );
			$output[$item_id]['name'] = themeblvd_sanitize_text( $item['name'] );
			$output[$item_id]['tagline'] = themeblvd_sanitize_text( $item['tagline'] );
			$output[$item_id]['company'] = themeblvd_sanitize_text( $item['company'] );
			$output[$item_id]['company_url'] = themeblvd_sanitize_text( $item['company_url'] );
			$output[$item_id]['image'] = themeblvd_sanitize_upload( $item['image'] );
		}
	}

	return $output;
}

/**
 * Toggles
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_toggles( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {

			$output[$item_id] = array();

			$output[$item_id]['title'] = themeblvd_sanitize_text( $item['title'] );
			$output[$item_id]['content'] = themeblvd_sanitize_textarea( $item['content'] );

			$output[$item_id]['wpautop'] = '0';

			if ( isset($item['wpautop']) ) {
				$output[$item_id]['wpautop'] = '1';
			}

			$output[$item_id]['open'] = '0';

			if ( isset($item['open']) ) {
				$output[$item_id]['open'] = '1';
			}
		}
	}

	return $output;
}

/**
 * Dynamic Content
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_content( $input ) {

	$allowedtags = themeblvd_allowed_tags();
	$output = array();

	// Verify type
	$types = array( 'widget', 'current', 'page', 'raw' );
	if ( in_array( $input['type'], $types ) ) {
		$output['type'] = $input['type'];
	} else {
		$output['type'] = null;
	}

	// Add type's corresponding option
	switch ( $output['type'] ) {

		case 'widget' :
			if ( isset( $input['sidebar'] ) ) {
				$output['sidebar'] = $input['sidebar'];
			} else {
				$output['sidebar'] = null;
			}
			break;

		case 'page' :
			$output['page'] = $input['page'];
			break;

		case 'raw' :
			$output['raw'] = wp_kses( $input['raw'], $allowedtags );
			$output['raw'] = str_replace( "\r\n", "\n", $output['raw'] );
			$output['raw_format'] = '0';
			if ( isset( $input['raw_format'] ) ) {
				$output['raw_format'] = '1';
			}
			break;
	}

	return $output;
}

/**
 * Logo
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_logo( $input ) {

	$output = array();

	// Type
	if ( is_array( $input ) && isset( $input['type'] ) ) {
		$output['type'] = $input['type'];
	}

	// Custom
	if ( isset( $input['custom'] ) ) {
		$output['custom'] = sanitize_text_field( $input['custom'] );
	}
	if ( isset( $input['custom_tagline'] ) ) {
		$output['custom_tagline'] = sanitize_text_field( $input['custom_tagline'] );
	}

	// Image (standard)
	if ( isset( $input['image'] ) ) {
		$filetype = wp_check_filetype( $input['image'] );
		if ( $filetype["ext"] ) {
			$output['image'] = $input['image'];
			if ( isset( $input['image_width'] ) ) {
				$output['image_width'] = $input['image_width'];
			}
		} else {
			$output['image'] = null;
			$output['image_width'] = null;
		}
	}

	// Image (for retina)
	if ( isset( $input['image_2x'] ) ) {
		$filetype = wp_check_filetype( $input['image_2x'] );
		if ( $filetype["ext"] ) {
			$output['image_2x'] = $input['image_2x'];
		} else {
			$output['image_2x'] = null;
		}
	}

	return $output;
}

/**
 * Social Media Buttons
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_social_media( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['icon'] = wp_kses( $item['icon'], array() );
			$output[$item_id]['url'] = wp_kses( $item['url'], array() );
			$output[$item_id]['label'] = wp_kses( $item['label'], array() );
			$output[$item_id]['target'] = wp_kses( $item['target'], array() );
		}
	}

	return $output;
}

/**
 * jQuery UI slider
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_slide( $input ) {
	return wp_kses( $input, array() );
}

/**
 * Simple Slider
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_slider( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {

			$output[$item_id] = array();

			// Crop size
			$output[$item_id]['crop'] = wp_kses( $item['crop'], array() );

			// Attachment ID
			$output[$item_id]['id'] = intval( $item['id'] );

			// Attachment title
			$output[$item_id]['alt'] = get_the_title( $output[$item_id]['id'] );

			// Attachment Image
			$attachment = wp_get_attachment_image_src( $output[$item_id]['id'], $output[$item_id]['crop'] );
			$output[$item_id]['src'] = $attachment[0];
			$output[$item_id]['width'] = $attachment[1];
			$output[$item_id]['height'] = $attachment[2];

			// Thumbnail
			$thumb = wp_get_attachment_image_src( $output[$item_id]['id'], apply_filters('themeblvd_simple_slider_thumb_crop', 'square_small') );
			$output[$item_id]['thumb'] = $thumb[0];

			// Slide info
			$output[$item_id]['title'] = wp_kses( $item['title'], array() );
			$output[$item_id]['desc'] = themeblvd_sanitize_textarea( $item['desc'] );

			// Link
			$output[$item_id]['link'] = wp_kses( $item['link'], array() );

			if ( $output[$item_id]['link'] == 'none' ) {
				$output[$item_id]['link'] = '';
			}

			$output[$item_id]['link_url'] = wp_kses( $item['link_url'], array() );
		}
	}

	return $output;
}

/**
 * Partner Logos
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_logos( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {

			$output[$item_id] = array();

			// Attachment ID
			$output[$item_id]['id'] = intval( $item['id'] );

			// Attachment title
			$output[$item_id]['alt'] = get_the_title( $output[$item_id]['id'] );

			// Attachment Image
			$attachment = wp_get_attachment_image_src( $output[$item_id]['id'], 'full' );
			$output[$item_id]['src'] = $attachment[0];
			$output[$item_id]['width'] = $attachment[1];
			$output[$item_id]['height'] = $attachment[2];

			// Thumbnail
			$thumb = wp_get_attachment_image_src( $output[$item_id]['id'], apply_filters('themeblvd_simple_slider_thumb_crop', 'square_small') );
			$output[$item_id]['thumb'] = $thumb[0];

			// Partner Name, description, and Link
			$output[$item_id]['name'] = wp_kses( $item['name'], array() );
			//$output[$item_id]['desc'] = wp_kses( $item['desc'], array() );
			$output[$item_id]['link'] = wp_kses( $item['link'], array() );

		}
	}

	return $output;
}

/**
 * Conditionals
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_conditionals( $input, $sidebar_slug = null, $sidebar_id = null ) {

	$conditionals = themeblvd_conditionals_config();
	$output = array();

	// Prepare items that weren't straight-up arrays
	// gifted on a platter for us.
	if ( ! empty( $input['post'] ) ) {
		$input['post'] = str_replace(' ', '', $input['post'] );
		$input['post'] = explode( ',', $input['post'] );
	}
	if ( ! empty( $input['tag'] ) ) {
		$input['tag'] = str_replace(' ', '', $input['tag'] );
		$input['tag'] = explode( ',', $input['tag'] );
	}

	// Now loop through each group and then each item
	foreach ( $input as $type => $group ) {
		if ( is_array( $group ) && ! empty( $group ) ) {
			foreach ( $group as $item_id ) {
				$name = '';
				switch ( $type ) {

					case 'page' :
						$page_id = themeblvd_post_id_by_name( $item_id, 'page' );
						$page = get_page( $page_id );
						if ( $page ) {
							$name = $page->post_title;
						}
						break;

					case 'post' :
						$post_id = themeblvd_post_id_by_name( $item_id );
						$post = get_post( $post_id );
						if ( $post ) {
							$name = $post->post_title;
						}
						break;

					case 'posts_in_category' :
						$category = get_category_by_slug( $item_id );
						if ( $category ) {
							$name = $category->slug;
						}
						break;

					case 'category' :
						$category = get_category_by_slug( $item_id );
						if ( $category ) {
							$name = $category->slug;
						}
						break;

					case 'tag' :
						$tag = get_term_by( 'slug', $item_id, 'post_tag' );
						if ( $tag ) {
							$name = $tag->name;
						}
						break;

					case 'top' :
						$name = $conditionals['top']['items'][$item_id];
						break;
				}
				if ( $name ) {
					$output[$type.'_'.$item_id] = array(
						'type' 		=> $type,
						'id' 		=> $item_id,
						'name' 		=> $name,
						'post_slug' => $sidebar_slug,
						'post_id' 	=> $sidebar_id
					);
				}
			}
		}
	}
	// Add in cusotm conditional
	if ( ! empty( $input['custom'] ) ) {
		$output['custom'] = array(
			'type' 		=> 'custom',
			'id' 		=> themeblvd_sanitize_text( $input['custom'] ),
			'name' 		=> themeblvd_sanitize_text( $input['custom'] ),
			'post_slug' => $sidebar_slug,
			'post_id' 	=> $sidebar_id
		);
	}

	return $output;
}

/**
 * Sanitize Editor option type
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_editor( $input ) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		$output = $input;
	} else {
		$output = wp_kses( $input, themeblvd_allowed_tags() );
	}
	return $output;
}

/**
 * Sanitize gradient option type
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_gradient( $input ){

	$output = array(
		'start'	=> '',
		'end'	=> ''
	);

	if ( isset( $input['start'] ) ) {
		$output['start'] = apply_filters( 'themeblvd_sanitize_hex', $input['start'] );
	}

	if ( isset( $input['end'] ) ) {
		$output['end'] = apply_filters( 'themeblvd_sanitize_hex', $input['end'] );
	}

	return $output;
}

/**
 * Sanitize button option type
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_button( $input ){

	$output = array(
		'bg' 				=> '',
		'bg_hover'			=> '',
		'border' 			=> '',
		'text'				=> '',
		'text_hover'		=> '',
		'include_bg'		=> 1,
		'include_border'	=> 1
	);

	$output['bg'] = apply_filters( 'themeblvd_sanitize_hex', $input['bg'] );
	$output['bg_hover'] = apply_filters( 'themeblvd_sanitize_hex', $input['bg_hover'] );
	$output['border'] = apply_filters( 'themeblvd_sanitize_hex', $input['border'] );
	$output['text'] = apply_filters( 'themeblvd_sanitize_hex', $input['text'] );
	$output['text_hover'] = apply_filters( 'themeblvd_sanitize_hex', $input['text_hover'] );

	if ( empty( $input['include_bg'] ) ) {
		$input['include_bg'] = '';
	}

	$output['include_bg'] = apply_filters( 'themeblvd_sanitize_checkbox', $input['include_bg'] );

	if ( empty( $input['include_border'] ) ) {
		$input['include_border'] = '';
	}

	$output['include_border'] = apply_filters( 'themeblvd_sanitize_checkbox', $input['include_border'] );

	return $output;
}

/**
 * Sanitize a color represented in hexidecimal notation.
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_hex( $hex, $default = '' ) {
	if ( themeblvd_validate_hex( $hex ) ) {
		return $hex;
	}
	return $default;
}

/**
 * Get recognized font sizes.
 *
 * @since 2.2.0
 */
function themeblvd_recognized_font_sizes() {
	$sizes = range( 9, 70 );
	$sizes = apply_filters( 'themeblvd_recognized_font_sizes', $sizes );
	$sizes = array_map( 'absint', $sizes );
	return $sizes;
}

/**
 * Get recognized font faces.
 *
 * @since 2.2.0
 */
function themeblvd_recognized_font_faces() {
	$default = array(
		'arial'     	=> 'Arial',
		'baskerville'	=> 'Baskerville',
		'georgia'   	=> 'Georgia',
		'helvetica' 	=> 'Helvetica*',
		'lucida'  		=> 'Lucida Sans',
		'palatino'  	=> 'Palatino',
		'tahoma'    	=> 'Tahoma, Geneva',
		'times'     	=> 'Times New Roman',
		'trebuchet' 	=> 'Trebuchet',
		'verdana'   	=> 'Verdana, Geneva',
		'google'		=> 'Google Font'
	);
	return apply_filters( 'themeblvd_recognized_font_faces', $default );
}

/**
 * Get recognized font styles.
 *
 * @since 2.2.0
 */
function themeblvd_recognized_font_styles() {
	$default = array(
		'normal'      => 'Normal',
		'italic'      => 'Italic',
		'bold'        => 'Bold',
		'bold-italic' => 'Bold Italic'
	);
	return apply_filters( 'themeblvd_recognized_font_styles', $default );
}

/**
 * Verify formatted in hexidecimal notation
 *
 * @since 2.2.0
 */
function themeblvd_validate_hex( $hex ) {

	$hex = trim( $hex );

	/* Strip recognized prefixes. */
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	} else if ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}

	/* Regex match. */
	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	} else {
		return true;
	}

}

/**
 * Locations
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_locations( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['name'] = wp_kses( $item['name'], array() );
			$output[$item_id]['geo'] = themeblvd_sanitize_geo( $item['geo'] );
			$output[$item_id]['info'] = themeblvd_sanitize_textarea( $item['info'] );
			$output[$item_id]['image'] = themeblvd_sanitize_upload( $item['image'] );
		}
	}

	return $output;
}

/**
 * Geo (latitude and longitude)
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_geo( $input ) {

	$output = array(
		'lat' 	=> 0,
		'long'	=> 0
	);

	if ( ! empty( $input['lat'] ) ) {

		$lat = floatval($input['lat']);

		if ( $lat > -90 && $lat < 90  ) {
			$output['lat'] = $lat;
		}
	}

	if ( ! empty( $input['long'] ) ) {

		$long = floatval($input['long']);

		if ( $long > -180 && $long < 180  ) {
			$output['long'] = $long;
		}
	}

	return $output;
}

/**
 * Sectors
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_sectors( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['label'] = wp_kses( $item['label'], array() );
			$output[$item_id]['value'] = wp_kses( $item['value'], array() );
			$output[$item_id]['color'] = themeblvd_sanitize_hex( $item['color'] );
		}
	}

	return $output;
}

/**
 * Data Sets
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_datasets( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['label'] = wp_kses( $item['label'], array() );
			$output[$item_id]['values'] = wp_kses( $item['values'], array() );
			$output[$item_id]['color'] = themeblvd_sanitize_hex( $item['color'] );
		}
	}

	return $output;
}

/**
 * Progress Bars
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_bars( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['label'] = wp_kses( $item['label'], array() );
			$output[$item_id]['label_value'] = wp_kses( $item['label_value'], array() );
			$output[$item_id]['value'] = wp_kses( $item['value'], array() );
			$output[$item_id]['total'] = wp_kses( $item['total'], array() );
			$output[$item_id]['color'] = themeblvd_sanitize_hex( $item['color'] );
		}
	}

	return $output;
}

/**
 * Buttons
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_buttons( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['color'] = wp_kses( $item['color'], array() );
			$output[$item_id]['custom'] = themeblvd_sanitize_button( $item['custom'] );
			$output[$item_id]['text'] = themeblvd_sanitize_text( $item['text'] );
			$output[$item_id]['size'] = wp_kses( $item['size'], array() );
			$output[$item_id]['url'] = wp_kses( $item['url'], array() );
			$output[$item_id]['target'] = wp_kses( $item['target'], array() );
			$output[$item_id]['icon_before'] = wp_kses( $item['icon_before'], array() );
			$output[$item_id]['icon_after'] = wp_kses( $item['icon_after'], array() );
		}
	}

	return $output;
}