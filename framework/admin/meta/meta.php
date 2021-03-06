<?php
/**
 * Add framework meta boxes.
 *
 * This file primarily utilizes our framework class
 * Theme_Blvd_Meta_Box to create the default meta
 * boxes of the framework. All data passed when creating
 * each meta box object is filtered.
 *
 * Meta Boxes:
 * 1. Page Options
 * 2. Theme Layout
 * 3. Post Template Options
 * 4. Post Options
 *
 * This function is hooked to:
 * 1. `admin_init` - 10
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

/**
 * Add page and post meta boxes.
 *
 * @since Theme_Blvd 2.0.1
 */
function themeblvd_add_meta_boxes() {

	global $_themeblvd_page_meta_box;

	global $_themeblvd_layout_meta_box;

	global $_themeblvd_post_template_meta_box;

	global $_themeblvd_post_meta_box;

	/*
	 * Meta Box: Post Options
	 *
	 * This meta box is added to the Edit Post screen,
	 * with a generic set of options meant to be used
	 * with all standard posts. Not added to custom
	 * post types by default, but can be filtered to be.
	 */
	if ( themeblvd_supports( 'meta', 'post_options' ) ) {

		$meta = setup_themeblvd_post_meta();

		$_themeblvd_post_meta_box = new Theme_Blvd_Meta_Box(
			$meta['config']['id'],
			$meta['config'],
			$meta['options']
		);

	}

	/*
	 * Meta Box: Page Options
	 *
	 * This meta box is added to the Edit Page screen,
	 * with a generic set of options meant to be used
	 * with all pages.
	 */
	if ( themeblvd_supports( 'meta', 'page_options' ) ) {

		$meta = setup_themeblvd_page_meta();

		$_themeblvd_page_meta_box = new Theme_Blvd_Meta_Box(
			$meta['config']['id'],
			$meta['config'],
			$meta['options']
		);

	}

	/*
	 * Meta Box: Theme Layout
	 *
	 * This meta box is added to the Edit screen for pages
	 * and standard posts. It gives control for displaying
	 * the header and footer of the given post, along with
	 * applying the "transparent header" feature.
	 */
	if ( themeblvd_supports( 'meta', 'layout' ) ) {

		$meta = setup_themeblvd_layout_meta();

		$_themeblvd_layout_meta_box = new Theme_Blvd_Meta_Box(
			$meta['config']['id'],
			$meta['config'],
			$meta['options']
		);

	}

	/*
	 * Meta Box: Post Template Options
	 *
	 * This meta box gets added the Edit Page screen, and is
	 * meant to provide additional customization when using
	 * specifically the post display page templates:
	 *
	 * Blog, Post List, Post Grid, and Post Showcase
	 */
	if ( themeblvd_supports( 'meta', 'pto' ) ) {

		$meta = setup_themeblvd_pto_meta();

		$_themeblvd_post_template_meta_box = new Theme_Blvd_Meta_Box(
			$meta['config']['id'],
			$meta['config'],
			$meta['options']
		);

	}

}

/**
 * Build and filter data for meta box, "Post Options."
 *
 * @since Theme_Blvd 2.0.0
 *
 * @return $setup All data passed to Theme_Blvd_Meta_Box.
 */
function setup_themeblvd_post_meta() {

	$setup = array(
		'config' => array(
			'id'        => 'tb_post_options',                     // Make it unique.
			'title'     => __( 'Post Options', 'jumpstart' ), // Title to show for entire meta box.
			'screen'    => array( 'post' ),                       // Can contain post, page, link, or custom post type's slug.
			'context'   => 'normal',                              // normal, advanced, or side
			'priority'  => 'high',                                // high, core, default, or low
		),
		'options' => array(
			'tb_meta' => array(
				'id'        => '_tb_meta',
				'name'      => __( 'Meta Information (the single post)', 'jumpstart' ),
				'desc'      => __( 'Select if you\'d like the meta information (like date posted, author, etc) to show on this single post. If you\'re going for a non-blog type of setup, you may want to hide the meta info.', 'jumpstart' ),
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', 'jumpstart' ),
					'show'      => __( 'Show meta info', 'jumpstart' ),
					'hide'      => __( 'Hide meta info', 'jumpstart' ),
				),
			),
			'tb_sub_meta' => array(
				'id'        => '_tb_sub_meta',
				'name'      => __( 'Sub Meta Information (the single post)', 'jumpstart' ),
				'desc'      => __( 'Select if you\'d like the sub meta information (like tags, categories, etc) to show on this single post.', 'jumpstart' ),
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', 'jumpstart' ),
					'show'      => __( 'Show sub meta info', 'jumpstart' ),
					'hide'      => __( 'Hide sub meta info', 'jumpstart' ),
				),
			),
			'tb_author_box' => array(
				'id'        => '_tb_author_box',
				'name'      => __( 'Author Box (the single post)', 'jumpstart' ),
				'desc'      => __( 'Select if you\'d like to display a box with information about the post\'s author.', 'jumpstart' ),
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use WordPress user\'s default setting', 'jumpstart' ),
					'1'         => __( 'Show author box', 'jumpstart' ), // Use "1" to match default user checkbox option
					'hide'      => __( 'Hide author box', 'jumpstart' ),
				),
			),
			'tb_related_posts' => array(
				'id'        => '_tb_related_posts',
				'name'      => __( 'Related Posts (the single post)', 'jumpstart' ),
				'desc'      => __( 'Select if you\'d like to show more posts related to the one being viewed.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: This only applies to standard posts.', 'jumpstart' ) . '</em>',
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', 'jumpstart' ),
					'tag'       => __( 'Show related posts by tag', 'jumpstart' ),
					'category'  => __( 'Show related posts by category', 'jumpstart' ),
					'hide'      => __( 'Hide related posts', 'jumpstart' ),
				),
			),
			'tb_comments' => array(
				'id'        => '_tb_comments',
				'name'      => __( 'Comments (the single post)', 'jumpstart' ),
				'desc'      => __( 'This will hide the presence of comments on this single post.', 'jumpstart' ),
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', 'jumpstart' ),
					'show'      => __( 'Show comments', 'jumpstart' ),
					'hide'      => __( 'Hide comments', 'jumpstart' ),
				),
			),
			'tb_breadcrumbs' => array(
				'id'        => '_tb_breadcrumbs',
				'name'      => __( 'Breadcrumbs (the single post)', 'jumpstart' ),
				'desc'      => __( 'Select whether you\'d like breadcrumbs to show on this post or not.', 'jumpstart' ),
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', 'jumpstart' ),
					'show'      => __( 'Yes, show breadcrumbs', 'jumpstart' ),
					'hide'      => __( 'No, hide breadcrumbs', 'jumpstart' ),
				),
			),
			'tb_thumb' => array(
				'id'        => '_tb_thumb',
				'name'      => __( 'Featured Image or Gallery (the single post)', 'jumpstart' ),
				'desc'      => __( 'Select how you\'d like the featured image to show at the top of the post. This option only refers to this single post.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: The full-screen option will work best when a Transparent Header is set.', 'jumpstart' ) . '</em>',
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', 'jumpstart' ),
					'fw'        => __( 'Full width, above content', 'jumpstart' ),
					'fs'        => __( 'Full screen parallax, above content', 'jumpstart' ),
					'full'      => __( 'Standard, with content', 'jumpstart' ),
					'hide'      => __( 'Hide featured image', 'jumpstart' ),
				),
			),
			'section_start' => array(
				'type'      => 'subgroup_start',
				'class'     => 'show-hide-toggle',
			),
			'tb_thumb_link' => array(
				'id'        => '_tb_thumb_link',
				'name'      => __( 'Featured Image Link (everywhere)', 'jumpstart' ),
				'desc'      => __( 'Here you can select how you\'d like this post\'s featured image to react when clicked. This DOES apply to both this single post page and when this post is used in a blog, post list, post grid, or post showcase.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: This does not apply to the single post when the featured image is displayed above the content.', 'jumpstart' ) . '</em>',
				'type'      => 'radio',
				'std'       => 'inactive',
				'options'   => array(
					'inactive'  => __( 'Featured image is not a link', 'jumpstart' ),
					'post'      => __( 'It links to its post', 'jumpstart' ),
					'thumbnail' => __( 'It links to its enlarged lightbox version', 'jumpstart' ),
					'image'     => __( 'It links to a custom lightbox image', 'jumpstart' ),
					'video'     => __( 'It links to a lightbox video', 'jumpstart' ),
					'external'  => __( 'It links to a webpage', 'jumpstart' ),
				),
				'class'     => 'trigger',
			),
			'tb_image_link' => array(
				'id'        => '_tb_image_link',
				'name'      => __( 'Featured Image - Image Link', 'jumpstart' ),
				'desc'      => __( 'Enter the full URL of enlarged image that the featured image will link to.', 'jumpstart' ) . '<br><br>' . __( 'Ex: https://your-site.com/uploads/image.jpg', 'jumpstart' ),
				'type'      => 'text',
				'class'     => 'hide receiver receiver-image',
			),
			'tb_video_link' => array(
				'id'        => '_tb_video_link',
				'name'      => __( 'Featured Image - Video Link', 'jumpstart' ),
				'desc'      => __( 'Enter the full webpage URL to a video from YouTube or Vimeo.', 'jumpstart' ) . '<br><br>' . __( 'Ex', 'jumpstart' ) . ': https://www.youtube.com/watch?v=ginTCwWfGNY<br>' . __( 'Ex', 'jumpstart' ) . ': https://vimeo.com/11178250',
				'type'      => 'text',
				'class'     => 'hide receiver receiver-video',
			),
			'tb_external_link' => array(
				'id'        => '_tb_external_link',
				'name'      => __( 'Featured Image - External Link', 'jumpstart' ),
				'desc'      => __( 'Enter the full URL of where the featured image will link.', 'jumpstart' ) . '<br><br>' . __( 'Ex: https://google.com', 'jumpstart' ),
				'type'      => 'text',
				'class'     => 'hide receiver receiver-external',
			),
			'tb_external_link_target' => array(
				'id'        => '_tb_external_link_target',
				'name'      => __( 'Featured Image - External Link Target', 'jumpstart' ),
				'desc'      => __( 'Select whether you\'d like the external link to open in a new window or not.', 'jumpstart' ),
				'type'      => 'radio',
				'std'       => '_blank',
				'options'   => array(
					'_blank'    => __( 'Open link in new window', 'jumpstart' ),
					'_self'     => __( 'Open link in same window', 'jumpstart' ),
				),
				'class'     => 'hide receiver receiver-external',
			),
			'tb_thumb_link_single' => array(
				'id'        => '_tb_thumb_link_single',
				'name'      => __( 'Featured Image Link (the single post)', 'jumpstart' ),
				'desc'      => __( 'If you\'ve selected a featured image link above, select whether you\'d like the image link to be applied to the featured image on the single post page.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: This does not apply when the featured image is displayed above the content.', 'jumpstart' ) . '</em>',
				'std'       => 'yes',
				'type'      => 'radio',
				'options'   => array(
					'yes'       => __( 'Yes, apply featured image link to single post', 'jumpstart' ),
					'no'        => __( 'No, don\'t apply featured image link to single post', 'jumpstart' ),
					'thumbnail' => __( 'Link it to its enlarged lightbox version', 'jumpstart' ),
				),
				'class'     => 'hide receiver receiver-post receiver-thumbnail receiver-image receiver-video receiver-external',
			),
			'section_end' => array(
				'type'      => 'subgroup_end',
			),
		),
	);

	/**
	 * Filters all data passed to the Theme_Blvd_Meta_Box
	 * class when creating the object for the "Post Options"
	 * meta box.
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @param array $setup {
	 *     All data for meta box.
	 *
	 *     @type array $config {
	 *          @type string $id         Unique ID for meta box.
	 *          @type string $title      Title for meta box.
	 *          @type array  $page       Post types meta box will show for.
	 *          @type string $context    Contex parameter passed to add_meta_box().
	 *          @type string $priority   Priority parameter passed to add_meta_box().
	 *          @type bool   $save_empty Optional. Whether to save empty values to database.
	 *     }
	 *     @type array $options {
	 *          Standard framework array of options.
	 *          @link https://dev.themeblvd.com/tutorial/formatting-options/
	 *     }
	 * }
	 */
	return apply_filters( 'themeblvd_post_meta', $setup );

}

/**
 * Build and filter data for meta box, "Page Options."
 *
 * @since Theme_Blvd 2.0.0
 *
 * @return $setup All data passed to Theme_Blvd_Meta_Box.
 */
function setup_themeblvd_page_meta() {

	$setup = array(
		'config' => array(
			'id'        => 'tb_page_options',                     // Make it unique.
			'title'     => __( 'Page Options', 'jumpstart' ), // Title to show for entire meta box.
			'screen'    => array( 'page' ),                       // Can contain post, page, link, or custom post type's slug.
			'context'   => 'normal',                              // normal, advanced, or side
			'priority'  => 'high',                                // high, core, default, or low
		),
		'options' => array(
			'tb_title' => array(
				'id'        => '_tb_title',
				'name'      => __( 'Page Title', 'jumpstart' ),
				'desc'      => __( 'Select whether to display the page\'s title.', 'jumpstart' ) . '<br><br><em>Note:' . __( 'This option will be ignored if you\'ve applied the "Custom Layout" template; however, if you\'ve set your featured image to display above the content, it will still apply.', 'jumpstart' ) . '</em>',
				'type'      => 'select',
				'options'   => array(
					'show'      => __( 'Yes, show page\'s title', 'jumpstart' ),
					'hide'      => __( 'No, hide page\'s title', 'jumpstart' ),
				),
			),
			'tb_breadcrumbs' => array(
				'id'        => '_tb_breadcrumbs',
				'name'      => __( 'Breadcrumbs', 'jumpstart' ),
				'desc'      => __( 'Select whether you\'d like breadcrumbs to show on this page or not. This option will be ignored if you\'ve applied the "Custom Layout" or "Blank Page" templates.', 'jumpstart' ),
				'type'      => 'select',
				'options'   => array(
					'default'   => __( 'Use default setting', 'jumpstart' ),
					'show'      => __( 'Yes, show breadcrumbs', 'jumpstart' ),
					'hide'      => __( 'No, hide breadcrumbs', 'jumpstart' ),
				),
			),
			'tb_thumb' => array(
				'id'        => '_tb_thumb',
				'name'      => __( 'Featured Image Display', 'jumpstart' ),
				'desc'      => __( 'Select how you\'d like the featured image to show at the top of the page.', 'jumpstart' ) . '<br><br><em>' . __( 'Notes: (1) When not using the default page template, featured image can only displayed above the content. (2) The full-screen option will work best when a Transparent Header is set.', 'jumpstart' ) . '</em>',
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', 'jumpstart' ),
					'fw'        => __( 'Full width, above content', 'jumpstart' ),
					'fs'        => __( 'Full screen parallax, above content', 'jumpstart' ),
					'full'      => __( 'Standard, with content', 'jumpstart' ),
					'hide'      => __( 'Hide featured images', 'jumpstart' ),
				),
			),
			'section_start' => array(
				'type'      => 'subgroup_start',
				'class'     => 'show-hide-toggle',
			),
			'tb_thumb_link' => array(
				'id'        => '_tb_thumb_link',
				'name'      => __( 'Featured Image Link', 'jumpstart' ),
				'desc'      => __( 'Here you can select how you\'d like this page\'s featured image to react when clicked, if you\'ve set one.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: This does not apply when the featured image is displayed above the content.', 'jumpstart' ) . '</em>',
				'type'      => 'radio',
				'std'       => 'inactive',
				'options'   => array(
					'inactive'  => __( 'Featured image is not a link', 'jumpstart' ),
					'thumbnail' => __( 'It links to its enlarged lightbox version', 'jumpstart' ),
					'image'     => __( 'It links to a custom lightbox image', 'jumpstart' ),
					'video'     => __( 'It links to a lightbox video', 'jumpstart' ),
					'external'  => __( 'It links to a webpage', 'jumpstart' ),
				),
				'class'     => 'trigger',
			),
			'tb_image_link' => array(
				'id'        => '_tb_image_link',
				'name'      => __( 'Featured Image - Image Link', 'jumpstart' ),
				'desc'      => __( 'Enter the full URL of enlarged image that the featured image will link to.', 'jumpstart' ) . '<br><br>' . __( 'Ex: https://your-site.com/uploads/image.jpg', 'jumpstart' ),
				'type'      => 'text',
				'class'     => 'hide receiver receiver-image',
			),
			'tb_video_link' => array(
				'id'        => '_tb_video_link',
				'name'      => __( 'Featured Image - Video Link', 'jumpstart' ),
				'desc'      => __( 'Enter the full webpage URL to a video from YouTube or Vimeo.', 'jumpstart' ) . '<br><br>' . __( 'Ex', 'jumpstart' ) . ': https://www.youtube.com/watch?v=ginTCwWfGNY<br>' . __( 'Ex', 'jumpstart' ) . ': https://vimeo.com/11178250',
				'type'      => 'text',
				'class'     => 'hide receiver receiver-video',
			),
			'tb_external_link' => array(
				'id'        => '_tb_external_link',
				'name'      => __( 'Featured Image - External Link', 'jumpstart' ),
				'desc'      => __( 'Enter the full URL of where the featured image will link.', 'jumpstart' ) . '<br><br>' . __( 'Ex: https://google.com', 'jumpstart' ),
				'type'      => 'text',
				'class'     => 'hide receiver receiver-external',
			),
			'tb_external_link_target' => array(
				'id'        => '_tb_external_link_target',
				'name'      => __( 'Featured Image - External Link Target', 'jumpstart' ),
				'desc'      => __( 'Select whether you\'d like the external link to open in a new window or not.', 'jumpstart' ),
				'type'      => 'radio',
				'std'       => '_blank',
				'options'   => array(
					'_blank'    => __( 'Open link in new window', 'jumpstart' ),
					'_self'     => __( 'Open link in same window', 'jumpstart' ),
				),
				'class'     => 'hide receiver receiver-external',
			),
			'section_end' => array(
				'type'      => 'subgroup_end',
			),
		),
	);

	/**
	 * Filters all data passed to the Theme_Blvd_Meta_Box
	 * class when creating the object for the "Page Options"
	 * meta box.
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @param array $setup {
	 *     All data for meta box.
	 *
	 *     @type array $config {
	 *          @type string $id         Unique ID for meta box.
	 *          @type string $title      Title for meta box.
	 *          @type array  $page       Post types meta box will show for.
	 *          @type string $context    Contex parameter passed to add_meta_box().
	 *          @type string $priority   Priority parameter passed to add_meta_box().
	 *          @type bool   $save_empty Optional. Whether to save empty values to database.
	 *     }
	 *     @type array $options {
	 *          Standard framework array of options.
	 *          @link https://dev.themeblvd.com/tutorial/formatting-options/
	 *     }
	 * }
	 */
	return apply_filters( 'themeblvd_page_meta', $setup );

}

/**
 * Build and filter data for meta box, "Theme Layout."
 *
 * @since Theme_Blvd 2.5.0
 *
 * @return $setup All data passed to Theme_Blvd_Meta_Box.
 */
function setup_themeblvd_layout_meta() {

	$sidebar_layouts = array(
		'default' => __( 'Default', 'jumpstart' )
	);

	foreach ( themeblvd_sidebar_layouts() as $layout ) {
		$sidebar_layouts[ $layout['id'] ] = $layout['name'];
	}

	$setup = array(
		'config' => array(
			'id'        => 'tb_layout_options',                   // Make it unique.
			'title'     => __( 'Theme Layout', 'jumpstart' ), // Title to show for entire meta box.
			'screen'    => array( 'page', 'post' ),               // Can contain post, page, link, or custom post type's slug.
			'context'   => 'side',                                // normal, advanced, or side
			'priority'  => 'core',                                // high, core, default, or low
		),
		'options' => array(
			'sidebar_layout' => array(
				'id'      => '_tb_sidebar_layout',
				'name'    => __( 'Sidebar Layout', 'jumpstart' ),
				'desc'    => __( 'Note: The sidebar layout is not applied when using a custom layout.', 'jumpstart' ),
				'type'    => 'select',
				'options' => $sidebar_layouts
			),
			'layout_header' => array(
				'id'        => '_tb_layout_header',
				'name'      => __( 'Header', 'jumpstart' ),
				'desc'      => __( 'Note: The transparent header option will work best when a large graphic is displayed prominently at the top of the page.', 'jumpstart' ) . ' <a href="https://vimeo.com/165052766" target="_blank">' . __( 'Learn More', 'jumpstart' ) . '</a>',
				'type'      => 'select',
				'options'   => array(
					'default'       => __( 'Standard Header', 'jumpstart' ),
					'suck_up'       => __( 'Transparent Header', 'jumpstart' ),
					'hide'          => __( 'Hide Header', 'jumpstart' ),
				),
			),
			'layout_footer' => array(
				'id'        => '_tb_layout_footer',
				'name'      => __( 'Footer', 'jumpstart' ),
				'type'      => 'select',
				'options'   => array(
					'default'       => __( 'Standard Footer', 'jumpstart' ),
					'hide'          => __( 'Hide Footer', 'jumpstart' ),
				),
			),
		),
	);

	if ( ! themeblvd_supports( 'display', 'suck_up' ) && ! themeblvd_supports( 'display', 'hide_top' ) ) {

		unset( $setup['options']['tb_layout_header'] );

	} elseif ( ! themeblvd_supports( 'display', 'suck_up' ) ) {

		unset( $setup['options']['tb_layout_header']['options']['suck_up'] );

		unset( $setup['options']['tb_layout_header']['desc'] );

	} elseif ( ! themeblvd_supports( 'display', 'hide_top' ) ) {

		unset( $setup['options']['tb_layout_header']['options']['hide'] );

	}

	if ( ! themeblvd_supports( 'display', 'hide_bottom' ) ) {

		unset( $setup['options']['tb_layout_footer'] );

	}

	/**
	 * Filters all data passed to the Theme_Blvd_Meta_Box
	 * class when creating the object for the "Theme Layout"
	 * meta box.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array $setup {
	 *     All data for meta box.
	 *
	 *     @type array $config {
	 *          @type string $id         Unique ID for meta box.
	 *          @type string $title      Title for meta box.
	 *          @type array  $page       Post types meta box will show for.
	 *          @type string $context    Contex parameter passed to add_meta_box().
	 *          @type string $priority   Priority parameter passed to add_meta_box().
	 *          @type bool   $save_empty Optional. Whether to save empty values to database.
	 *     }
	 *     @type array $options {
	 *          Standard framework array of options.
	 *          @link https://dev.themeblvd.com/tutorial/formatting-options/
	 *     }
	 * }
	 */
	return apply_filters( 'themeblvd_layout_meta', $setup );

}

/**
 * Build and filter data for meta box, "Post Template Options."
 *
 * @since Theme_Blvd 2.5.0
 *
 * @return $setup All data passed to Theme_Blvd_Meta_Box.
 */
function setup_themeblvd_pto_meta() {

	$setup = array(
		'config' => array(
			'id'         => 'pto',                                          // Make it unique.
			'title'      => __( 'Post Template Options', 'jumpstart' ), // Title to show for entire meta box.
			'screen'     => array( 'page' ),                                // Can contain post, page, link, or custom post type's slug.
			'context'    => 'normal',                                       // normal, advanced, or side
			'priority'   => 'low',                                          // high, core, default, or low
			'save_empty' => false,                                          // Whether to save empty values to custom fields
		),
		'options' => array(
			'desc' => array(
				// translators: 1: link to WP_Query documentation on WordPress Codex
				'desc'      => sprintf( __( 'Below are the custom fields you can use with the Blog, Post List, Post Grid, and Post Showcase page templates. When working with these options, you can find a lot of helpful information by viewing WordPress\'s Codex page on the %s.', 'jumpstart' ), '<a href="https://codex.wordpress.org/Class_Reference/WP_Query" target="_blank">WP Query</a>' ),
				'type'      => 'info',
			),
			'cat' => array(
				'id'        => 'cat',
				'name'      => __( 'cat', 'jumpstart' ),
				'desc'      => __( 'Category ID(s) to include/exclude.', 'jumpstart' ) . '<br>' . __( 'Ex: 1', 'jumpstart' ) . '<br>' . __( 'Ex: 1,2,3', 'jumpstart' ) . '<br>' . __( 'Ex: -1,-2,-3', 'jumpstart' ),
				'type'      => 'text',
			),
			'category_name' => array(
				'id'        => 'category_name',
				'name'      => __( 'category_name', 'jumpstart' ),
				'desc'      => __( 'Category slug(s) to include.', 'jumpstart' ) . '<br>' . __( 'Ex: cat-1', 'jumpstart' ) . '<br>' . __( 'Ex: cat-1,cat-2', 'jumpstart' ),
				'type'      => 'text',
			),
			'tag' => array(
				'id'        => 'tag',
				'name'      => __( 'tag', 'jumpstart' ),
				'desc'      => __( 'Tag(s) to include.', 'jumpstart' ) . '<br>' . __( 'Ex: tag-1', 'jumpstart' ) . '<br>' . __( 'Ex: tag-1,tag-2', 'jumpstart' ),
				'type'      => 'text',
			),
			'posts_per_page' => array(
				'id'        => 'posts_per_page',
				'name'      => __( 'posts_per_page', 'jumpstart' ),
				'desc'      => __( 'Number of posts per page. This option gets used for the Blog template, Post List template, and when using "masonry" style display for the Post Grid and Post Showcase template. Standard grid view for Post Grid and Post Showcase templates use rows*columns.', 'jumpstart' ),
				'type'      => 'text',
			),
			'orderby' => array(
				'id'        => 'orderby',
				'name'      => __( 'orderby', 'jumpstart' ),
				'desc'      => __( 'What to order posts by &mdash; date, title, rand, etc.', 'jumpstart' ) . '<br><a href="https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">' . __( 'Learn More', 'jumpstart' ) . '</a>',
				'type'      => 'text',
			),
			'order' => array(
				'id'        => 'order',
				'name'      => __( 'order', 'jumpstart' ),
				'desc'      => __( 'How to order posts &mdash; ASC or DESC.', 'jumpstart' ) . '<br><a href="https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">' . __( 'Learn More', 'jumpstart' ) . '</a>',
				'type'      => 'text',
			),
			'query' => array(
				'id'        => 'query',
				'name'      => __( 'query', 'jumpstart' ),
				'desc'      => __( 'A custom query string. This will override other options.', 'jumpstart' ) . '<br>' . __( 'Ex: tag=baking', 'jumpstart' ) . '<br>' . __( 'Ex: post_type=my_type&my_tax=my_term', 'jumpstart' ),
				'type'      => 'text',
			),
			'columns' => array(
				'id'        => 'columns',
				'name'      => __( 'columns', 'jumpstart' ),
				'desc'      => __( 'Number of columns for Post Grid or Post Showcase template, which can be 2-5. When empty, default value is used from theme options page.', 'jumpstart' ),
				'type'      => 'text',
			),
			'rows' => array(
				'id'        => 'rows',
				'name'      => __( 'rows', 'jumpstart' ),
				'desc'      => __( 'Number of rows for Post Grid and Post Showcase templates. When empty, default value is used from theme options page.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: This option does not apply when using masonry.', 'jumpstart' ) . '</em>',
				'type'      => 'text',
			),
			'tb_display' => array(
				'id'        => 'tb_display',
				'name'      => __( 'tb_display', 'jumpstart' ),
				'desc'      => __( 'When using Post Grid and Post Showcase template, this custom field allows you to override the default display option.', 'jumpstart' ),
				'type'      => 'select',
				'options'   => array(
					'0'                 => __( 'Use default setting', 'jumpstart' ),
					'paginated'         => __( 'Standard', 'jumpstart' ),
					'masonry_paginated' => __( 'Masonry', 'jumpstart' ),
				),
			),
		),
	);

	/**
	 * Filters all data passed to the Theme_Blvd_Meta_Box
	 * class when creating the object for the "Post Template
	 * Options" meta box.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array $setup {
	 *     All data for meta box.
	 *
	 *     @type array $config {
	 *          @type string $id         Unique ID for meta box.
	 *          @type string $title      Title for meta box.
	 *          @type array  $page       Post types meta box will show for.
	 *          @type string $context    Contex parameter passed to add_meta_box().
	 *          @type string $priority   Priority parameter passed to add_meta_box().
	 *          @type bool   $save_empty Optional. Whether to save empty values to database.
	 *     }
	 *     @type array $options {
	 *          Standard framework array of options.
	 *          @link https://dev.themeblvd.com/tutorial/formatting-options/
	 *     }
	 * }
	 */
	return apply_filters( 'themeblvd_pto_meta', $setup );

}
