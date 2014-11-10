<?php
/**
 * Theme Blvd Sortable Option.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 * @since 		2.5.0
 */
abstract class Theme_Blvd_Sortable_Option {

	/**
	 * Force Extending class to define these methods
	 */
	abstract protected function get_options();
	abstract protected function get_labels();

	/*--------------------------------------------*/
	/* Properties, private
	/*--------------------------------------------*/

	/**
	 * Options for each sortable element
	 *
	 * @since 2.5.0
	 * @var array
	 */
	private $options = array();

	/**
	 * Trigger option. This option's value will
	 * get feed to the toggle's handle as its updated.
	 *
	 * @since 2.5.0
	 * @var string
	 */
	private $trigger = '';

	/*--------------------------------------------*/
	/* Properties, protected
	/*--------------------------------------------*/

	/**
	 * Text strings for managing items.
	 *
	 * @since 2.5.0
	 * @var array
	 */
	protected $labels = array();

	/**
	 * Current advanced option type. Set by child class.
	 *
	 * @since 2.5.0
	 * @var string
	 */
	protected $type = '';

	/**
	 * Optional maximum number of sortable items
	 *
	 * @since 2.5.0
	 * @var int
	 */
	protected $max = 0;

	/*--------------------------------------------*/
	/* Constructor
	/*--------------------------------------------*/

	/**
	 * Constructor.
	 *
	 * @since 2.5.0
	 */
	public function __construct( $ajax = true ) {

		// Setup labels
		$this->labels = array(
			'add' 					=> __('Add Item', 'themeblvd'),
			'delete'				=> __('Delete Item', 'themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this item?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Items','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all items?','themeblvd')
		);

		// Set labels (inherited from child class)
		$this->labels = wp_parse_args( $this->get_labels(), $this->labels );

		// Set Options (inherited from child class)
		$this->options = $this->get_options();

		// Determine trigger option
		foreach ( $this->options as $option ) {
			if ( isset( $option['trigger'] ) && $option['trigger'] ) {
				$this->trigger = $option['id'];
			}
		}

		if ( $ajax ) {

			// Make sure there's no duplicate AJAX actions added
			remove_all_actions( 'wp_ajax_themeblvd_add_'.$this->type.'_item' );

			// Add item with AJAX - Use: themeblvd_add_{$type}_item
			add_action( 'wp_ajax_themeblvd_add_'.$this->type.'_item', array( $this, 'add_item' ) );
		}
	}

	/*--------------------------------------------*/
	/* Methods
	/*--------------------------------------------*/

	/**
	 * Display the option.
	 *
	 * @since 2.5.0
	 */
	public function get_display( $option_id, $option_name, $items ) {

		$ajax_nonce = wp_create_nonce( 'themeblvd_sortable_option' );

		$output  = sprintf('<div class="tb-sortable-option" data-security="%s" data-name="%s" data-id="%s" data-type="%s" data-max="%s">', $ajax_nonce, $option_name, $option_id, $this->type, $this->max );

		// Header (blank by default)
		$output .= $this->get_display_header( $option_id, $option_name, $items );

		// Output blank option for the sortable items. If the user doesn't
		// setup any sortable items and then they save, this will ensure that
		// at least a blank value get saved to the option.
		$output .= sprintf( '<input type="hidden" name="%s" />', $option_name.'['.$option_id.']' );

		// Start sortable section
		$output .= '<div class="item-container">';

		if ( is_array( $items ) && count( $items ) > 0 ) {
			foreach ( $items as $item_id => $item ) {
				$output .= $this->get_item( $option_id, $item_id, $item, $option_name );
			}
		}

		$output .= '</div><!-- .item-container (end) -->';

		// Footer and button to add items
		$output .= $this->get_display_footer( $option_id, $option_name, $items );

		$output .= '</div><!-- .tb-sortable-option (end) -->';

		return $output;
	}

	/**
	 * Display the header.
	 *
	 * @since 2.5.0
	 */
	protected function get_display_header( $option_id, $option_name, $items ) {
		return '';
	}

	/**
	 * Display the footer.
	 *
	 * @since 2.5.0
	 */
	protected function get_display_footer( $option_id, $option_name, $items ) {

		$footer  = '<footer class="clearfix">';

		$disabled = '';
		if ( $this->max && count($items) >= $this->max ) {
			$disabled = 'disabled';
		}

		$footer .= sprintf( '<input type="button" class="add-item button-secondary" value="%s" %s />', $this->labels['add'], $disabled );

		if ( $this->max ) {
			$footer .= sprintf('<div class="max">%s: %s</div>', __('Maximum', 'themeblvd'), $this->max);
		}

		$footer .= sprintf( '<a href="#" title="%s" class="tb-tooltip-link delete-sortable-items hide" data-tooltip-text="%s"><i class="tb-icon-cancel-circled"></i></a>', $this->labels['delete_all_confirm'], $this->labels['delete_all'] );
		$footer .= '</footer>';

		return $footer;
	}

	/**
	 * Individual sortable item.
	 *
	 * @since 2.5.0
	 */
	public function get_item( $option_id, $item_id, $item, $option_name ) {

		$item_output  = sprintf( '<div id="%s" class="widget item">', $item_id );

		$item_output .= $this->get_item_handle( $item );

		$item_output .= '<div class="item-content">';

		foreach ( $this->options as $option ) {

			// Wrap a some of the options in a DIV
			// to be utilized from the javascript.

			if ( $option['type'] == 'subgroup_start' ) {
				$class = 'subgroup';
				if ( isset( $option['class'] ) ) {
					$class .= ' '.$option['class'];
				}

				$item_output .= sprintf('<div class="%s">', $class);
				continue;
			}

			if ( $option['type'] == 'subgroup_end' ) {
				$item_output .= '</div><!-- .subgroup (end) -->';
				continue;
			}

			// Continue with normal form items
			$class = 'section-'.$option['type'];
			if ( isset( $option['class'] ) ) {
				$class .= ' '.$option['class'];
			}

			$item_output .= sprintf( '<div class="section %s">', $class );

			if ( isset( $option['name'] ) && $option['name'] ) {
				$item_output .= sprintf( '<h4>%s</h4>', $option['name'] );
			}

			$item_output .= '<div class="option clearfix">';
			$item_output .= '<div class="controls">';

			$current = '';
			if ( isset($option['id']) && isset($item[$option['id']]) ) {
				$current = $item[$option['id']];
			}

			switch ( $option['type'] ) {

				/*---------------------------------------*/
				/* Hidden input
				/*---------------------------------------*/

				case 'hidden' :
					$class = 'of-input';
					if ( $this->trigger == $option['id'] ) {
						$class .= ' handle-trigger';
					}
					$item_output .= sprintf( '<input id="%s" class="%s" name="%s" type="hidden" value="%s" />', esc_attr( $option['id'] ), $class, esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), stripslashes( esc_attr( $current ) ) );
					break;

				/*---------------------------------------*/
				/* Text input
				/*---------------------------------------*/

				case 'text':

					$place_holder = '';
					if ( ! empty( $option['pholder'] ) ) {
						$place_holder = ' placeholder="'.$option['pholder'].'"';
					}

					$item_output .= '<div class="input-wrap">';

					if ( isset( $option['icon'] ) && ( $option['icon'] == 'image' || $option['icon'] == 'vector' ) ) {
						$item_output .= '<a href="#" class="tb-input-icon-link tb-tooltip-link" data-target="themeblvd-icon-browser-'.$option['icon'].'" data-icon-type="'.$option['icon'].'" data-tooltip-text="'.__('Browse Icons', 'themeblvd').'"><i class="tb-icon-picture"></i></a>';
					}

					$class = 'of-input';
					if ( $this->trigger == $option['id'] ) {
						$class .= ' handle-trigger';
					}

					$item_output .= sprintf( '<input id="%s" class="%s" name="%s" type="text" value="%s"%s />', esc_attr( $option['id'] ), $class, esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), stripslashes( esc_attr( $current ) ), $place_holder );
					$item_output .= '</div><!-- .input-wrap (end) -->';
					break;

				/*---------------------------------------*/
				/* Textarea
				/*---------------------------------------*/

				case 'textarea':

					$place_holder = '';
					if ( ! empty( $option['pholder'] ) ) {
						$place_holder = ' placeholder="'.$option['pholder'].'"';
					}

					$cols = '8';
					if ( isset( $option['options'] ) && isset( $option['options']['cols'] ) ) {
						$cols = $option['options']['cols'];
					}

					if ( isset( $option['editor'] ) || isset( $option['code'] ) ) {

						$item_output .= '<div class="textarea-wrap with-editor-nav">';

						$item_output .= '<nav class="editor-nav">';

						if ( isset( $option['editor'] ) && $option['editor'] ) {
							$item_output .= '<a href="#" class="tb-textarea-editor-link tb-tooltip-link" data-tooltip-text="'.__('Open in Editor', 'themeblvd').'" data-target="themeblvd-editor-modal"><i class="tb-icon-pencil"></i></a>';
						}

						if ( isset( $option['code'] ) && in_array( $option['code'], array( 'html', 'javascript', 'css' ) ) ) {
							$item_output .= '<a href="#" class="tb-textarea-code-link tb-tooltip-link" data-tooltip-text="'.__('Open in Code Editor', 'themeblvd').'" data-target="'.esc_textarea( $option['id'] ).'" data-title="'.$option['name'].'" data-code_lang="'.$option['code'].'"><i class="tb-icon-code"></i></a>';
						}

						$item_output .= '</nav>';

					} else {
						$item_output .= '<div class="textarea-wrap">';
					}

					$class = 'of-input';
					if ( $this->trigger == $option['id'] ) {
						$class .= ' handle-trigger';
					}

					$item_output .= sprintf( '<textarea id="%s" class="%s" name="%s" cols="%s" rows="8"%s>%s</textarea>', esc_textarea( $option['id'] ), $class, stripslashes( esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ) ), esc_attr( $cols ), $place_holder, esc_textarea( $current ) );
					$item_output .= '</div><!-- .textarea-wrap (end) -->';

					break;

				/*---------------------------------------*/
				/* <select> menu
				/*---------------------------------------*/

				case 'select':

					$item_output .= '<div class="tb-fancy-select">';

					$class = 'of-input';
					if ( $this->trigger == $option['id'] ) {
						$class .= ' handle-trigger';
					}

					$item_output .= sprintf( '<select class="%s" name="%s" id="%s">', $class, esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), esc_attr($option['id']) );

					foreach ( $option['options'] as $key => $value ) {
						$item_output .= sprintf( '<option%s value="%s">%s</option>', selected( $key, $current, false ), esc_attr( $key ), esc_html( $value ) );
					}

					$item_output .= '</select>';
					$item_output .= '<span class="trigger"></span>';
					$item_output .= '<span class="textbox"></span>';
					$item_output .= '</div><!-- .tb-fancy-select (end) -->';

					break;

				/*---------------------------------------*/
				/* Checkbox
				/*---------------------------------------*/

				case 'checkbox':
					$item_output .= sprintf( '<input id="%s" class="of-input" name="%s" type="checkbox" %s />', esc_attr( $option['id'] ), esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), checked( $current, 1, false ) );
					break;

				/*---------------------------------------*/
				/* Content option type
				/*---------------------------------------*/

				case 'content' :
					$item_output .= themeblvd_content_option( $option['id'], $option_name.'['.$option_id.']['.$item_id.']', $current, $option['options'] );
					break;

				/*---------------------------------------*/
				/* Color
				/*---------------------------------------*/

				case 'color' :
					$def_color = '';
					if ( ! empty( $option['std'] ) ) {
						$def_color = $option['std'];
					}
					$item_output .= sprintf( '<input id="%s" name="%s" type="text" value="%s" class="tb-color-picker" data-default-color="%s" />', esc_attr( $option['id'] ), esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), esc_attr( $current ), $def_color );
					break;

				/*---------------------------------------*/
				/* Uploader
				/*---------------------------------------*/

				case 'upload' :

					$args = array(
						'option_name'	=> $option_name.'['.$option_id.']['.$item_id.']',
						'id'			=> $option['id']
					);

					if ( ! empty( $option['advanced'] ) ) {

						// Advanced type will allow for selecting
						// image crop size for URL.
						$args['type'] = 'advanced';

						if ( isset( $current['src'] ) ) {
							$args['value_src'] = $current['src'];
						}

						if ( isset( $current['id'] ) ) {
							$args['value_id'] = $current['id'];
						}

						if ( isset( $current['title'] ) ) {
							$args['value_title'] = $current['title'];
						}

						if ( isset( $current['crop'] ) ) {
							$args['value_crop'] = $current['crop'];
						}

						if ( isset( $current['width'] ) ) {
							$args['value_width'] = $current['width'];
						}

						if ( isset( $current['height'] ) ) {
							$args['value_height'] = $current['height'];
						}

					} else {

						$args['value'] = $current;
						$args['type'] = 'standard';

						if ( isset( $option['send_back'] ) ) {
							$args['send_back'] = $option['send_back'];
						} else {
							$args['send_back'] = 'url';
						}

						if ( ! empty( $option['video'] ) ) {
							$args['type'] = 'video';
						}
					}

					$item_output .= themeblvd_media_uploader( $args );

					break;

				/*---------------------------------------*/
				/* Button
				/*---------------------------------------*/

				case 'button' :
					$item_output .= themeblvd_button_option( $option['id'], $option_name.'['.$option_id.']['.$item_id.']', $current );
					break;

				/*---------------------------------------*/
				/* Geo (Latitude and Longitude)
				/*---------------------------------------*/

				case 'geo' :

					// Values
					$lat = '';
					if ( isset( $current['lat'] ) ) {
						$lat = $current['lat'];
					}

					$long = '';
					if ( isset( $current['long'] ) ) {
						$long = $current['long'];
					}

					$item_output .= '<div class="geo-wrap clearfix">';

					// Latitude
					$item_output .= '<div class="geo-lat">';
					$item_output .= sprintf( '<input id="%s_lat" class="of-input geo-input" name="%s" type="text" value="%s" />', esc_attr($option['id']), esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].'][lat]' ), esc_attr($lat) );
					$item_output .= '<span class="geo-label">'.__('Latitude', 'themeblvd').'</span>';
					$item_output .= '</div><!-- .geo-lat (end) -->';

					// Longitude
					$item_output .= '<div class="geo-long">';
					$item_output .= sprintf( '<input id="%s_long" class="of-input geo-input" name="%s" type="text" value="%s" />', esc_attr($option['id']), esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].'][long]' ), esc_attr($long) );
					$item_output .= '<span class="geo-label">'.__('Longitude', 'themeblvd').'</span>';
					$item_output .= '</div><!-- .geo-long (end) -->';

					$item_output .= '</div><!-- .geo-wrap (end) -->';

					// Generate lat and long
					$item_output .= '<div class="geo-generate">';
					$item_output .= '<h5>'.__('Generate Coordinates', 'themeblvd').'</h5>';
					$item_output .= '<div class="data clearfix">';
					$item_output .= '<span class="overlay"><span class="tb-loader ajax-loading"><i class="tb-icon-spinner"></i></span></span>';
					$item_output .= '<input type="text" value="" class="address" />';
					$item_output .= sprintf( '<a href="#" class="button-secondary geo-insert-lat-long" data-oops="%s">%s</a>', __('Oops! Sorry, we weren\'t able to get coordinates from that address. Try again.', 'themeblvd'), __('Generate', 'themeblvd') );
					$item_output .= '</div><!-- .data (end) -->';
					$item_output .= '<p class="note">';
					$item_output .= __('Enter an address, as you would do at maps.google.com.', 'themeblvd').'<br>';
					$item_output .= __('Example Address', 'themeblvd').': "123 Smith St, Chicago, USA"';
					$item_output .= '</p>';
					$item_output .= '</div><!-- .geo-generate (end) -->';

			}

			$item_output .= '</div><!-- .controls (end) -->';

			if ( ! empty( $option['desc'] ) ) {
				if ( is_array( $option['desc'] ) ) {
					foreach ( $option['desc'] as $desc_id => $desc ) {
						$item_output .= '<div class="explain hide '.$desc_id.'">';
						$item_output .= wp_kses( $desc, themeblvd_allowed_tags() );
						$item_output .= '</div>';
					}
				} else {
					$item_output .= '<div class="explain">';
					$item_output .= wp_kses( $option['desc'], themeblvd_allowed_tags() );
					$item_output .= '</div>';
				}
			}

			$item_output .= '</div><!-- .options (end) -->';

			$item_output .= '</div><!-- .section (end) -->';
		}

		// Delete item
		$item_output .= '<div class="section">';
		$item_output .= sprintf( '<a href="#%s" class="delete-sortable-item" title="%s">%s</a>', $item_id, $this->labels['delete_confirm'], $this->labels['delete'] );
		$item_output .= '</div>';

		$item_output .= '</div><!-- .item-content (end) -->';
		$item_output .= '</div>';

		return $item_output;
	}

	/**
	 * Get the handle for an item.
	 *
	 * @since 2.5.0
	 */
	protected function get_item_handle( $item ) {
		$handle  = '<div class="item-handle closed">';
		$handle .= '<h3>&nbsp;</h3>';
		$handle .= '<span class="tb-icon-sort"></span>';
		$handle .= '<a href="#" class="toggle"><span class="tb-icon-up-dir"></span></a>';
		$handle .= '</div>';
		return $handle;
	}

	/**
	 * Set default value for a new item.
	 *
	 * @since 2.5.0
	 */
	public function get_default() {

		$default = array();

		foreach ( $this->options as $option ) {
			if ( isset( $option['std'] ) ) {
				$default[$option['id']] = $option['std'];
			}
		}

		return $default;
	}

	/**
	 * Add item via Ajax.
	 *
	 * @since 2.5.0
	 */
	public function add_item() {
		check_ajax_referer( 'themeblvd_sortable_option', 'security' );
		echo $this->get_item( $_POST['data']['option_id'], uniqid( 'item_'.rand() ), $this->get_default(), $_POST['data']['option_name'] );
		die();
	}

}

/**
 * Progress Bars option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Bars_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'bars';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'label',
				'name'		=> __('Display Label', 'themeblvd'),
				'desc'		=> __('Enter a label for this display.<br>Ex: Graphic Design', 'themeblvd'),
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'label_value',
				'name'		=> __('Value Display Label', 'themeblvd'),
				'desc'		=> __('Enter a label to display the value.<br>Ex: 80%', 'themeblvd'),
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'value',
				'name'		=> __('Value', 'themeblvd'),
				'desc'		=> __('Enter a number for the value.<br>Ex: 80', 'themeblvd'),
				'std'		=> '',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'total',
				'name'		=> __('Total', 'themeblvd'),
				'desc'		=> __('Enter a number, which your above value should be divided into. If your above value is meant to represent a straight percantage, then this "total" number should be 100.', 'themeblvd'),
				'std'		=> '100',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'color',
				'name'		=> __('Color', 'themeblvd'),
				'desc'		=> __('Select a color that represents this progress bar.', 'themeblvd'),
				'std'		=> '#cccccc',
				'type'		=> 'color'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Progress Bar','themeblvd'),
			'delete' 				=> __('Delete Progress Bar','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this progress bar?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Progress Bars','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all progress bars?','themeblvd')
		);
		return $labels;
	}

}

/**
 * Buttons option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Buttons_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'buttons';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'show-hide-toggle'
		    ),
			array(
				'id' 		=> 'color',
				'name'		=> __( 'Button Color', 'themeblvd' ),
				'desc'		=> __( 'Select what color you\'d like to use for this button.', 'themeblvd' ),
				'type'		=> 'select',
				'class'		=> 'trigger',
				'options'	=> themeblvd_colors()
			),
			array(
				'id' 		=> 'custom',
				'name'		=> __( 'Custom Button Color', 'themeblvd' ),
				'desc'		=> __( 'Configure a custom style for the button.', 'themeblvd' ),
				'std'		=> array(
					'bg' 				=> '#ffffff',
					'bg_hover'			=> '#ebebeb',
					'border' 			=> '#cccccc',
					'text'				=> '#333333',
					'text_hover'		=> '#333333',
					'include_bg'		=> 1,
					'include_border'	=> 1
				),
				'type'		=> 'button',
				'class'		=> 'hide receiver receiver-custom'
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
			array(
				'id' 		=> 'text',
				'name'		=> __( 'Button Text', 'themeblvd' ),
				'desc'		=> __( 'Enter the text for the button.', 'themeblvd' ),
				'std'		=> 'Get Started Today!',
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'size',
				'name'		=> __( 'Button Size', 'themeblvd' ),
				'desc'		=> __( 'Select the size you\'d like used for this button.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'large',
				'options'	=> array(
					'mini' 		=> __( 'Mini', 'themeblvd' ),
					'small' 	=> __( 'Small', 'themeblvd' ),
					'default' 	=> __( 'Normal', 'themeblvd' ),
					'large' 	=> __( 'Large', 'themeblvd' ),
					'x-large' 	=> __( 'Extra Large', 'themeblvd' )
				)
			),
			array(
				'id' 		=> 'url',
				'name'		=> __( 'Link URL', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL where you want the button\'s link to go.', 'themeblvd' ),
				'std'		=> 'http://www.your-site.com/your-landing-page',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'target',
				'name'		=> __( 'Link Target', 'themeblvd' ),
				'desc'		=> __( 'Select how you want the button to open the webpage.', 'themeblvd' ),
				'type'		=> 'select',
				'options'	=> array(
			        '_self' 	=> __( 'Same Window', 'themeblvd' ),
			        '_blank' 	=> __( 'New Window', 'themeblvd' ),
			        'lightbox' 	=> __( 'Lightbox Popup', 'themeblvd' )
				)
			),
			array(
				'id' 		=> 'icon_before',
				'name'		=> __( 'Icon Before Button Text (optional)', 'themeblvd' ),
				'desc'		=> __( 'Icon before text of button. This can be any FontAwesome vector icon ID.', 'themeblvd' ),
				'type'		=> 'text',
				'icon'		=> 'vector'
			),
			array(
				'id' 		=> 'icon_after',
				'name'		=> __( 'Icon After Button Text (optional)', 'themeblvd' ),
				'desc'		=> __( 'Icon after text of button. This can be any FontAwesome vector icon ID.', 'themeblvd' ),
				'type'		=> 'text',
				'icon'		=> 'vector'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Button','themeblvd'),
			'delete' 				=> __('Delete Button','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this button?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Buttons','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all buttons?','themeblvd')
		);
		return $labels;
	}

}

/**
 * Datasets option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Datasets_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'datasets';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'label',
				'name'		=> __('Label', 'themeblvd'),
				'desc'		=> __('Enter a label for this dataset.', 'themeblvd'),
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'values',
				'name'		=> __('Values', 'themeblvd'),
				'desc'		=> __('Enter a comma separated list of values for this data set.<br>Ex: 10, 20, 30, 40, 50, 60', 'themeblvd'),
				'std'		=> '',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'color',
				'name'		=> __('Color', 'themeblvd'),
				'desc'		=> __('Select a color that represents this data set.', 'themeblvd'),
				'std'		=> '#cccccc',
				'type'		=> 'color'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Data Set','themeblvd'),
			'delete' 				=> __('Delete Data Set','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this data set?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Data Sets','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all data sets?','themeblvd')
		);
		return $labels;
	}

}

/**
 * Markers option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Locations_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'locations';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'name',
				'name'		=> __('Location Name', 'themeblvd'),
				'desc'		=> __('Enter a name for this location.', 'themeblvd'),
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'geo',
				'name'		=> __('Location Latitude and Longitude', 'themeblvd'),
				'desc'		=> __('For this marker to be displayed, there needs to be a latitude and longitude saved. You can use the tool below the text fields to generate the coordinates.', 'themeblvd'),
				'std'		=> array(
					'lat'	=> 0,
					'long'	=> 0
				),
				'type'		=> 'geo'
			),
			array(
				'id' 		=> 'info',
				'name'		=> __('Location Information', 'themeblvd'),
				'desc'		=> __('When the marker is clicked, this information will be shown. You can put basic HTML formatting in here, if you like; just don\'t get too carried away.', 'themeblvd'),
				'type'		=> 'textarea',
				'editor'	=> true,
				'code'		=> 'html'
			),
			array(
				'id' 		=> 'image',
				'name'		=> __('Custom Marker Image (optional)', 'themeblvd'),
				'desc'		=> __('If you\'d like a custom image to replace the default Google Map marker, you can insert it here.', 'themeblvd'),
				'std'		=> '',
				'type'		=> 'upload',
				'advanced'	=> true
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Map Location','themeblvd'),
			'delete' 				=> __('Delete Location','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this location?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Locations','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all map locations?','themeblvd')
		);
		return $labels;
	}

}

/**
 * Sectors option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Sectors_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'sectors';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'label',
				'name'		=> __('Label', 'themeblvd'),
				'desc'		=> __('Enter a label for this sector.', 'themeblvd'),
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'value',
				'name'		=> __('Value', 'themeblvd'),
				'desc'		=> __('Enter a numeric value for this sector.', 'themeblvd'),
				'std'		=> '0',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'color',
				'name'		=> __('Color', 'themeblvd'),
				'desc'		=> __('Select a color that represents this sector.', 'themeblvd'),
				'std'		=> '#cccccc',
				'type'		=> 'color'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Sector','themeblvd'),
			'delete' 				=> __('Delete Sector','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this sector?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Sectors','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all sectors?','themeblvd')
		);
		return $labels;
	}

}

/**
 * Slider option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Slider_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type -- Check to make sure type
		// is not set to allow child classes
		// of this child.
		if ( ! $this->type ) {
			$this->type = 'slider';
		}

		// Run parent
		parent::__construct();
	}

	/**
	 * Display the footer.
	 *
	 * @since 2.5.0
	 */
	protected function get_display_footer( $option_id, $option_name, $items ) {
		$footer  = '<footer>';
		$footer .= sprintf( '<a href="#" id="%s" class="add-images button-secondary" data-title="%s" data-button="%s">%s</a>', uniqid('slider_'), $this->labels['modal_title'], $this->labels['modal_button'], $this->labels['add'] );
		$footer .= sprintf( '<a href="#" title="%s" class="tb-tooltip-link delete-sortable-items hide" data-tooltip-text="%s"><i class="tb-icon-cancel-circled"></i></a>', $this->labels['delete_all_confirm'], $this->labels['delete_all'] );
		$footer .= '</footer>';
		return $footer;
	}

	/**
	 * Get the handle for an item.
	 *
	 * @since 2.5.0
	 */
	protected function get_item_handle( $item ) {

		$handle  = '<div class="item-handle closed">';

		if ( isset( $item['thumb'] ) ) {
			$handle .= sprintf( '<span class="preview"><img src="%s" /></span>', $item['thumb'] );
		}

		$handle .= '<h3>&nbsp;</h3>';
		$handle .= '<span class="tb-icon-sort"></span>';
		$handle .= '<a href="#" class="toggle"><span class="tb-icon-up-dir"></span></a>';
		$handle .= '</div>';

		return $handle;
	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'id',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'src',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'alt',
				'type'		=> 'hidden',
				'std'		=> '',
				'trigger'	=> true
			),
			/*
			array(
				'id' 		=> 'crop',
				'type'		=> 'hidden',
				'std'		=> 'slider-large',
				'class'		=> 'match' // Will match with image crop selection
			),
			*/
			array(
				'id' 		=> 'thumb',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'title',
				'name'		=> __('Title (optional)', 'themeblvd'),
				'desc'		=> __('If you\'d like a headline to show on the slide, you may enter it here.', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> '',
				'class'		=> 'slide-title'
			),
			array(
				'id' 		=> 'desc',
				'name'		=> __('Description (optional)', 'themeblvd'),
				'desc'		=> __('If you\'d like a description to show on the slide, you may enter it here.', 'themeblvd'),
				'type'		=> 'textarea',
				'std'		=> '',
				'class'		=> 'slide-desc'
			),
			array(
				'type' 		=> 'subgroup_start',
				'class'		=> 'slide-link show-hide-toggle desc-toggle'
			),
			array(
				'id' 		=> 'link',
				'name'		=> __( 'Link', 'themeblvd' ),
				'desc'		=> __( 'Select if and how this image should be linked.', 'themeblvd' ),
				'type'		=> 'select',
				'options'	=> array(
			        'none'		=> __( 'No Link', 'themeblvd' ),
			        '_self' 	=> __( 'Link to webpage in same window.', 'themeblvd' ),
			        '_blank' 	=> __( 'Link to webpage in new window.', 'themeblvd' ),
			        'image' 	=> __( 'Link to image in lightbox popup.', 'themeblvd' ),
			        'video' 	=> __( 'Link to video in lightbox popup.', 'themeblvd' )
				),
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'link_url',
				'name'		=> __( 'Link URL', 'themeblvd' ),
				'desc'		=> array(
			        '_self' 	=> __( 'Enter a URL to a webpage.<br />Ex: http://yoursite.com/example', 'themeblvd' ),
			        '_blank' 	=> __( 'Enter a URL to a webpage.<br />Ex: http://google.com', 'themeblvd' ),
			        'image' 	=> __( 'Enter a URL to an image file.<br />Ex: http://yoursite.com/uploads/image.jpg', 'themeblvd' ),
			        'video' 	=> __( 'Enter a URL to a YouTube or Vimeo page.<br />Ex: http://vimeo.com/11178250‎</br />Ex: https://youtube.com/watch?v=ginTCwWfGNY', 'themeblvd' )
				),
				'type'		=> 'text',
				'std'		=> '',
				'pholder'	=> 'http://',
				'class'		=> 'receiver receiver-_self receiver-_blank receiver-image receiver-video'
			),
			array(
				'type' 		=> 'subgroup_end'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Images','themeblvd'),
			'delete' 				=> __('Remove Image','themeblvd'),
			'delete_all' 			=> __('Remove All Images','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to remove all images?','themeblvd'),
			'modal_title'			=> __('Select Images','themeblvd'),
			'modal_button'			=> __('Add Images','themeblvd')
		);
		return $labels;
	}

	/**
	 * Add item via Ajax.
	 *
	 * @since 2.5.0
	 */
	public function add_item() {
		check_ajax_referer( 'themeblvd_sortable_option', 'security' );
		$items = $_POST['data']['items'];

		foreach ( $items as $item ) {
			$val = array(
				'id' 	=> $item['id'],
				'alt'	=> $item['title'],
				'thumb'	=> $item['preview']
			);
			echo $this->get_item( $_POST['data']['option_id'], uniqid( 'item_'.rand() ), $val, $_POST['data']['option_name'] );
		}
		die();
	}

}

/**
 * Logos option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Logos_Option extends Theme_Blvd_Slider_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'logos';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'id',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'src',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'alt',
				'type'		=> 'hidden',
				'std'		=> '',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'thumb',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'name',
				'name'		=> __('Partner Name', 'themeblvd'),
				'desc'		=> __('Enter a name that corresponds to this logo.', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> ''
			),
			/*
			array(
				'id' 		=> 'desc',
				'name'		=> __('Partner Description (optional)', 'themeblvd'),
				'desc'		=> __('Enter very brief description that will display as a tooltip when the user hovers on the logo.', 'themeblvd'),
				'type'		=> 'textarea',
				'std'		=> ''
			),
			*/
			array(
				'id' 		=> 'link',
				'name'		=> __( 'Partner Link (optional)', 'themeblvd' ),
				'desc'		=> __( 'Enter a URL you\'d like this logo to link to.<br>Ex: http://partersite.com', 'themeblvd' ),
				'type'		=> 'text',
				'pholder'	=> 'http://'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Logos','themeblvd'),
			'delete' 				=> __('Remove Logo','themeblvd'),
			'delete_all' 			=> __('Remove All Logos','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to remove all logos?','themeblvd'),
			'modal_title'			=> __('Select Logos','themeblvd'),
			'modal_button'			=> __('Add Logos','themeblvd')
		);
		return $labels;
	}

}

/**
 * Pricing table column option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Price_Cols_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'price_cols';

		// Max number of items that can be added
		$this->max = 6;

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
		    array(
				'id' 		=> 'highlight',
				'name'		=> __('Highlight Color', 'themeblvd'),
				'desc'		=> __('If you wish, you can give the column a highlight color.', 'themeblvd'),
				'type'		=> 'select',
				'std'		=> 'none',
				'options'	=> themeblvd_colors(true, false) // Include bootstrap, don't include "Custom Color" option
			),
			array(
				'id' 		=> 'title',
				'name'		=> __('Title', 'themeblvd'),
				'desc'		=> __('Enter a title for this column.<br>Ex: Gold Package', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> '',
				'trigger'	=> true
			),
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'show-hide'
		    ),
			array(
				'id' 		=> 'popout',
				'name'		=> null,
				'desc'		=> __('Pop out column so it stands out from the rest.', 'themeblvd'),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'title_subline',
				'name'		=> __('Popout Title Subline', 'themeblvd'),
				'desc'		=> __('Because the column is popped out, enter a very brief subline for the title.<br>Ex: Best Value', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> 'Best Value',
				'class'		=> 'hide receiver'
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
			array(
				'id' 		=> 'price',
				'name'		=> __('Price', 'themeblvd'),
				'desc'		=> __('Enter a value for the price, without the currency symbol.<br>Ex: 50', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'id' 		=> 'price_subline',
				'name'		=> __('Price Subline (optional)', 'themeblvd'),
				'desc'		=> __('Enter a very brief subline for the price to show what it represents.<br>Ex: per month', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'id' 		=> 'features',
				'name'		=> __('Feature List', 'themeblvd'),
				'desc'		=> __('Enter each feature, seprated by a line break. If you like, spice it up with some icons.<br><br><em>[vector_icon icon="check" color="#00aa00"]</em><br><em>[vector_icon icon="times" color="#aa0000"]</em>', 'themeblvd'),
				'type'		=> 'textarea',
				'std'		=> "Feature 1\nFeature 2\nFeature 3",
				'html'		=> true,
				'editor'	=> true
			),
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'show-hide'
		    ),
			array(
		    	'id' 		=> 'button',
				'name'		=> __( 'Button', 'themeblvd' ),
				'desc'		=> __( 'Show button below feature list?', 'themeblvd' ),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'hide receiver show-hide-toggle'
		    ),
			array(
				'id' 		=> 'button_color',
				'name'		=> __( 'Button Color', 'themeblvd' ),
				'desc'		=> __( 'Select what color you\'d like to use for this button.', 'themeblvd' ),
				'type'		=> 'select',
				'class'		=> 'trigger',
				'options'	=> themeblvd_colors()
			),
			array(
				'id' 		=> 'button_custom',
				'name'		=> __( 'Custom Button Color', 'themeblvd' ),
				'desc'		=> __( 'Configure a custom style for the button.', 'themeblvd' ),
				'std'		=> array(
					'bg' 				=> '#ffffff',
					'bg_hover'			=> '#ebebeb',
					'border' 			=> '#cccccc',
					'text'				=> '#333333',
					'text_hover'		=> '#333333',
					'include_bg'		=> 1,
					'include_border'	=> 1
				),
				'type'		=> 'button',
				'class'		=> 'hide receiver receiver-custom'
			),
			array(
				'type'		=> 'subgroup_end'
			),
			array(
				'id' 		=> 'button_text',
				'name'		=> __( 'Button Text', 'themeblvd' ),
				'desc'		=> __( 'Enter the text for the button.', 'themeblvd' ),
				'std'		=> 'Purchase Now',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_url',
				'name'		=> __( 'Link URL', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL where you want the button\'s link to go.', 'themeblvd' ),
				'std'		=> 'http://www.your-site.com/your-landing-page',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_size',
				'name'		=> __( 'Button Size', 'themeblvd' ),
				'desc'		=> __( 'Select the size you\'d like used for this button.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'default',
				'class'		=> 'hide receiver',
				'options'	=> array(
					'mini' 		=> __( 'Mini', 'themeblvd' ),
					'small' 	=> __( 'Small', 'themeblvd' ),
					'default' 	=> __( 'Normal', 'themeblvd' ),
					'large' 	=> __( 'Large', 'themeblvd' ),
					'x-large' 	=> __( 'Extra Large', 'themeblvd' )
				)
			),
			array(
				'id' 		=> 'button_icon_before',
				'name'		=> __( 'Icon Before Button Text (optional)', 'themeblvd' ),
				'desc'		=> __( 'Icon before text of button. This can be any FontAwesome vector icon ID.', 'themeblvd' ),
				'type'		=> 'text',
				'icon'		=> 'vector',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_icon_after',
				'name'		=> __( 'Icon After Button Text (optional)', 'themeblvd' ),
				'desc'		=> __( 'Icon after text of button. This can be any FontAwesome vector icon ID.', 'themeblvd' ),
				'type'		=> 'text',
				'icon'		=> 'vector',
				'class'		=> 'hide receiver'
			),
			array(
				'type'		=> 'subgroup_end'
			)

		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Pricing Table Column','themeblvd'),
			'delete' 				=> __('Remove Column','themeblvd'),
			'delete_confirm' 		=> __('Are you sure you want to delete this column?','themeblvd'),
			'delete_all' 			=> __('Remove All Columns','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to remove all columns?','themeblvd')
		);
		return $labels;
	}

}

/**
 * Social Media buttons option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Social_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'social_media';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'icon',
				'name'		=> __('Social Icon', 'themeblvd'),
				'type'		=> 'select',
				'std'		=> 'facebook',
				'options'	=> themeblvd_get_social_media_sources(),
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'url',
				'name'		=> __('Link URL', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> 'http://'
			),
			array(
				'id' 		=> 'label',
				'name'		=> __('Label', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'id' 		=> 'target',
				'name'		=> __('Link Target', 'themeblvd'),
				'type'		=> 'select',
				'std'		=> '_blank',
				'options'	=> array(
					'_blank'	=> __('New Window', 'themeblvd'),
					'_self' 	=> __('Same Window', 'themeblvd')
				)
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Icon','themeblvd'),
			'delete' 				=> __('Delete Icon','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this icon?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Icons','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all icons?','themeblvd')
		);
		return $labels;
	}

}

/**
 * Share buttons option type -- similar to
 * social_media, but simplified.
 *
 * @since 2.5.0
 */
class Theme_Blvd_Share_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'share';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'icon',
				'name'		=> __('Social Icon', 'themeblvd'),
				'type'		=> 'select',
				'std'		=> 'facebook',
				'options'	=> themeblvd_get_share_sources(),
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'label',
				'name'		=> __('Label', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> ''
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Share Icon','themeblvd'),
			'delete' 				=> __('Delete Share Icon','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this share icon?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Share Icons','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all share icons?','themeblvd')
		);
		return $labels;
	}

}

/**
 * Tabs option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Tabs_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'tabs';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'title',
				'name'		=> __('Tab Title', 'themeblvd'),
				'desc'		=> __('Enter a short title to represent this tab.', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> 'Tab Title',
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'content',
				'name'		=> __('Tab Content', 'themeblvd'),
				'desc'		=> __('Configure the content of the tab. Try not to make the content too complex, as it is possible that not all shortcodes and HTML will work as expected within a set of tabs.', 'themeblvd'),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Tab','themeblvd'),
			'delete' 				=> __('Delete Tab','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this tab?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Tabs','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all tabs?','themeblvd')
		);
		return $labels;
	}

}

/**
 * Toggles option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Testimonials_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'testimonials';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			'text' => array(
				'id' 		=> 'text',
				'name' 		=> __( 'Testimonial Text', 'themeblvd'),
				'desc'		=> __( 'Enter any text of the testimonial.', 'themeblvd'),
				'type'		=> 'textarea',
				'editor'	=> true,
				'code'		=> 'html'
		    ),
			'name' => array(
				'id' 		=> 'name',
				'name' 		=> __( 'Name', 'themeblvd'),
				'desc'		=> __( 'Enter the name of the person giving the testimonial.', 'themeblvd'),
				'type'		=> 'text',
				'trigger'	=> true // Triggers this option's value to be used in toggle
		    ),
		    'tagline' => array(
				'id' 		=> 'tagline',
				'name' 		=> __( 'Tagline (optional)', 'themeblvd'),
				'desc'		=> __( 'Enter a tagline for the person giving the testimonial.<br>Ex: Founder and CEO', 'themeblvd'),
				'type'		=> 'text'
		    ),
		    'company' => array(
				'id' 		=> 'company',
				'name' 		=> __( 'Company (optional)', 'themeblvd'),
				'desc'		=> __( 'Enter the company the person giving the testimonial belongs to.', 'themeblvd'),
				'type'		=> 'text'
		    ),
		    'company_url' => array(
				'id' 		=> 'company_url',
				'name' 		=> __( 'Company URL (optional)', 'themeblvd'),
				'desc'		=> __( 'Enter the website URL for the company or the person giving the testimonial.', 'themeblvd'),
				'type'		=> 'text',
				'pholder'	=> 'http://'
		    ),
		    'image' => array(
				'id' 		=> 'image',
				'name' 		=> __( 'Image (optional)', 'themeblvd'),
				'desc'		=> __( 'Select a small image for the person giving the testimonial.  This will look best if you select an image size that is square.', 'themeblvd'),
				'type'		=> 'upload',
				'advanced'	=> true
		    )
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Testimonial','themeblvd'),
			'delete' 				=> __('Delete Testimonial','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this testimonial?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Testimonials','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all testimonials?','themeblvd')
		);
		return $labels;
	}

}

/**
 * Toggles option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Toggles_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'toggles';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'title',
				'name'		=> __('Title', 'themeblvd'),
				'desc'		=> __('Enter a short title to represent this toggle.', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> 'Toggle Title',
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'content',
				'name'		=> __('Content', 'themeblvd'),
				'desc'		=> __('Configure the content of the toggle. Try not to make the content too complex, as it is possible that not all shortcodes and HTML will work as expected within toggle which is initially hidden.', 'themeblvd'),
				'type'		=> 'textarea',
				'editor'	=> true,
				'code'		=> 'html'
			),
			array(
				'id' 		=> 'wpautop',
				'name'		=> __('Content Formatting', 'themeblvd'),
				'desc'		=> __('Apply WordPress automatic formatting.', 'themeblvd'),
				'type'		=> 'checkbox',
				'std'		=> '1'
			),
			array(
				'id' 		=> 'open',
				'name'		=> __('Initial State', 'themeblvd'),
				'desc'		=> __('Toggle is open when the page intially loads.', 'themeblvd'),
				'type'		=> 'checkbox',
				'std'		=> '0'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Toggle','themeblvd'),
			'delete' 				=> __('Delete Toggle','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this tab?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Toggles','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all toggles?','themeblvd')
		);
		return $labels;
	}

}