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
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Add page and post meta boxes.
 *
 * @since @@name-framework 2.0.1
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
 * @since @@name-framework 2.0.0
 *
 * @return $setup All data passed to Theme_Blvd_Meta_Box.
 */
function setup_themeblvd_post_meta() {

	$imagepath = get_template_directory_uri() . '/framework/admin/assets/img/';

	$sidebar_layouts = array(
		'default' => $imagepath . 'layout-default.png',
	);

	$layouts = themeblvd_sidebar_layouts();

	foreach ( $layouts as $layout ) {

		$sidebar_layouts[ $layout['id'] ] = $imagepath . 'layout-' . $layout['id'] . '.png';

	}

	$setup = array(
		'config' => array(
			'id'        => 'tb_post_options',                     // Make it unique.
			'title'     => __( 'Post Options', '@@text-domain' ), // Title to show for entire meta box.
			'screen'    => array( 'post' ),                       // Can contain post, page, link, or custom post type's slug.
			'context'   => 'normal',                              // normal, advanced, or side
			'priority'  => 'high',                                // high, core, default, or low
		),
		'options' => array(
			'tb_meta' => array(
				'id'        => '_tb_meta',
				'name'      => __( 'Meta Information (the single post)', '@@text-domain' ),
				'desc'      => __( 'Select if you\'d like the meta information (like date posted, author, etc) to show on this single post. If you\'re going for a non-blog type of setup, you may want to hide the meta info.', '@@text-domain' ),
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', '@@text-domain' ),
					'show'      => __( 'Show meta info', '@@text-domain' ),
					'hide'      => __( 'Hide meta info', '@@text-domain' ),
				),
			),
			'tb_sub_meta' => array(
				'id'        => '_tb_sub_meta',
				'name'      => __( 'Sub Meta Information (the single post)', '@@text-domain' ),
				'desc'      => __( 'Select if you\'d like the sub meta information (like tags, categories, etc) to show on this single post.', '@@text-domain' ),
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', '@@text-domain' ),
					'show'      => __( 'Show sub meta info', '@@text-domain' ),
					'hide'      => __( 'Hide sub meta info', '@@text-domain' ),
				),
			),
			'tb_author_box' => array(
				'id'        => '_tb_author_box',
				'name'      => __( 'Author Box (the single post)', '@@text-domain' ),
				'desc'      => __( 'Select if you\'d like to display a box with information about the post\'s author.', '@@text-domain' ),
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use WordPress user\'s default setting', '@@text-domain' ),
					'1'         => __( 'Show author box', '@@text-domain' ), // Use "1" to match default user checkbox option
					'hide'      => __( 'Hide author box', '@@text-domain' ),
				),
			),
			'tb_related_posts' => array(
				'id'        => '_tb_related_posts',
				'name'      => __( 'Related Posts (the single post)', '@@text-domain' ),
				'desc'      => __( 'Select if you\'d like to show more posts related to the one being viewed.', '@@text-domain' ) . '<br><br><em>' . __( 'Note: This only applies to standard posts.', '@@text-domain' ) . '</em>',
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', '@@text-domain' ),
					'tag'       => __( 'Show related posts by tag', '@@text-domain' ),
					'category'  => __( 'Show related posts by category', '@@text-domain' ),
					'hide'      => __( 'Hide related posts', '@@text-domain' ),
				),
			),
			'tb_comments' => array(
				'id'        => '_tb_comments',
				'name'      => __( 'Comments (the single post)', '@@text-domain' ),
				'desc'      => __( 'This will hide the presence of comments on this single post.', '@@text-domain' ),
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', '@@text-domain' ),
					'show'      => __( 'Show comments', '@@text-domain' ),
					'hide'      => __( 'Hide comments', '@@text-domain' ),
				),
			),
			'tb_breadcrumbs' => array(
				'id'        => '_tb_breadcrumbs',
				'name'      => __( 'Breadcrumbs (the single post)', '@@text-domain' ),
				'desc'      => __( 'Select whether you\'d like breadcrumbs to show on this post or not.', '@@text-domain' ),
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', '@@text-domain' ),
					'show'      => __( 'Yes, show breadcrumbs', '@@text-domain' ),
					'hide'      => __( 'No, hide breadcrumbs', '@@text-domain' ),
				),
			),
			'tb_thumb' => array(
				'id'        => '_tb_thumb',
				'name'      => __( 'Featured Image or Gallery (the single post)', '@@text-domain' ),
				'desc'      => __( 'Select how you\'d like the featured image to show at the top of the post. This option only refers to this single post.', '@@text-domain' ) . '<br><br><em>' . __( 'Note: The full-screen option will work best when a Transparent Header is set.', '@@text-domain' ) . '</em>',
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', '@@text-domain' ),
					'fw'        => __( 'Full width, above content', '@@text-domain' ),
					'fs'        => __( 'Full screen parallax, above content', '@@text-domain' ),
					'full'      => __( 'Standard, with content', '@@text-domain' ),
					'hide'      => __( 'Hide featured image', '@@text-domain' ),
				),
			),
			'section_start' => array(
				'type'      => 'subgroup_start',
				'class'     => 'show-hide-toggle',
			),
			'tb_thumb_link' => array(
				'id'        => '_tb_thumb_link',
				'name'      => __( 'Featured Image Link (everywhere)', '@@text-domain' ),
				'desc'      => __( 'Here you can select how you\'d like this post\'s featured image to react when clicked. This DOES apply to both this single post page and when this post is used in a blog, post list, post grid, or post showcase.', '@@text-domain' ) . '<br><br><em>' . __( 'Note: This does not apply to the single post when the featured image is displayed above the content.', '@@text-domain' ) . '</em>',
				'type'      => 'radio',
				'std'       => 'inactive',
				'options'   => array(
					'inactive'  => __( 'Featured image is not a link', '@@text-domain' ),
					'post'      => __( 'It links to its post', '@@text-domain' ),
					'thumbnail' => __( 'It links to its enlarged lightbox version', '@@text-domain' ),
					'image'     => __( 'It links to a custom lightbox image', '@@text-domain' ),
					'video'     => __( 'It links to a lightbox video', '@@text-domain' ),
					'external'  => __( 'It links to a webpage', '@@text-domain' ),
				),
				'class'     => 'trigger',
			),
			'tb_image_link' => array(
				'id'        => '_tb_image_link',
				'name'      => __( 'Featured Image - Image Link', '@@text-domain' ),
				'desc'      => __( 'Enter the full URL of enlarged image that the featured image will link to.', '@@text-domain' ) . '<br><br>' . __( 'Ex: http://your-site.com/uploads/image.jpg', '@@text-domain' ),
				'type'      => 'text',
				'class'     => 'hide receiver receiver-image',
			),
			'tb_video_link' => array(
				'id'        => '_tb_video_link',
				'name'      => __( 'Featured Image - Video Link', '@@text-domain' ),
				'desc'      => __( 'Enter the full webpage URL to a video from YouTube or Vimeo.', '@@text-domain' ) . '<br><br>' . __( 'Ex', '@@text-domain' ) . ': http://www.youtube.com/watch?v=ginTCwWfGNY<br>' . __( 'Ex', '@@text-domain' ) . ': http://vimeo.com/11178250',
				'type'      => 'text',
				'class'     => 'hide receiver receiver-video',
			),
			'tb_external_link' => array(
				'id'        => '_tb_external_link',
				'name'      => __( 'Featured Image - External Link', '@@text-domain' ),
				'desc'      => __( 'Enter the full URL of where the featured image will link.', '@@text-domain' ) . '<br><br>' . __( 'Ex: http://google.com', '@@text-domain' ),
				'type'      => 'text',
				'class'     => 'hide receiver receiver-external',
			),
			'tb_external_link_target' => array(
				'id'        => '_tb_external_link_target',
				'name'      => __( 'Featured Image - External Link Target', '@@text-domain' ),
				'desc'      => __( 'Select whether you\'d like the external link to open in a new window or not.', '@@text-domain' ),
				'type'      => 'radio',
				'std'       => '_blank',
				'options'   => array(
					'_blank'    => __( 'Open link in new window', '@@text-domain' ),
					'_self'     => __( 'Open link in same window', '@@text-domain' ),
				),
				'class'     => 'hide receiver receiver-external',
			),
			'tb_thumb_link_single' => array(
				'id'        => '_tb_thumb_link_single',
				'name'      => __( 'Featured Image Link (the single post)', '@@text-domain' ),
				'desc'      => __( 'If you\'ve selected a featured image link above, select whether you\'d like the image link to be applied to the featured image on the single post page.', '@@text-domain' ) . '<br><br><em>' . __( 'Note: This does not apply when the featured image is displayed above the content.', '@@text-domain' ) . '</em>',
				'std'       => 'yes',
				'type'      => 'radio',
				'options'   => array(
					'yes'       => __( 'Yes, apply featured image link to single post', '@@text-domain' ),
					'no'        => __( 'No, don\'t apply featured image link to single post', '@@text-domain' ),
					'thumbnail' => __( 'Link it to its enlarged lightbox version', '@@text-domain' ),
				),
				'class'     => 'hide receiver receiver-post receiver-thumbnail receiver-image receiver-video receiver-external',
			),
			'section_end' => array(
				'type'      => 'subgroup_end',
			),
			'tb_sidebar_layout' => array(
				'id'        => '_tb_sidebar_layout',
				'name'      => __( 'Sidebar Layout', '@@text-domain' ),
				'desc'      => __( 'Choose the sidebar layout for this specific post. Keeping it set to "Website Default" will allow this post to continue using the sidebar layout selected on the Theme Options page.', '@@text-domain' ),
				'std'       => 'default',
				'type'      => 'images',
				'options'   => $sidebar_layouts,
				'img_width' => '45',
			),
		),
	);

	/**
	 * Filters all data passed to the Theme_Blvd_Meta_Box
	 * class when creating the object for the "Post Options"
	 * meta box.
	 *
	 * @since @@name-framework 2.0.0
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
	 *          @link http://dev.themeblvd.com/tutorial/formatting-options/
	 *     }
	 * }
	 */
	return apply_filters( 'themeblvd_post_meta', $setup );

}

/**
 * Build and filter data for meta box, "Page Options."
 *
 * @since @@name-framework 2.0.0
 *
 * @return $setup All data passed to Theme_Blvd_Meta_Box.
 */
function setup_themeblvd_page_meta() {

	$setup = array(
		'config' => array(
			'id'        => 'tb_page_options',                     // Make it unique.
			'title'     => __( 'Page Options', '@@text-domain' ), // Title to show for entire meta box.
			'screen'    => array( 'page' ),                       // Can contain post, page, link, or custom post type's slug.
			'context'   => 'normal',                              // normal, advanced, or side
			'priority'  => 'high',                                // high, core, default, or low
		),
		'options' => array(
			'tb_title' => array(
				'id'        => '_tb_title',
				'name'      => __( 'Page Title', '@@text-domain' ),
				'desc'      => __( 'Select whether to display the page\'s title.', '@@text-domain' ) . '<br><br><em>Note:' . __( 'This option will be ignored if you\'ve applied the "Custom Layout" template; however, if you\'ve set your featured image to display above the content, it will still apply.', '@@text-domain' ) . '</em>',
				'type'      => 'select',
				'options'   => array(
					'show'      => __( 'Yes, show page\'s title', '@@text-domain' ),
					'hide'      => __( 'No, hide page\'s title', '@@text-domain' ),
				),
			),
			'tb_breadcrumbs' => array(
				'id'        => '_tb_breadcrumbs',
				'name'      => __( 'Breadcrumbs', '@@text-domain' ),
				'desc'      => __( 'Select whether you\'d like breadcrumbs to show on this page or not. This option will be ignored if you\'ve applied the "Custom Layout" or "Blank Page" templates.', '@@text-domain' ),
				'type'      => 'select',
				'options'   => array(
					'default'   => __( 'Use default setting', '@@text-domain' ),
					'show'      => __( 'Yes, show breadcrumbs', '@@text-domain' ),
					'hide'      => __( 'No, hide breadcrumbs', '@@text-domain' ),
				),
			),
			'tb_thumb' => array(
				'id'        => '_tb_thumb',
				'name'      => __( 'Featured Image Display', '@@text-domain' ),
				'desc'      => __( 'Select how you\'d like the featured image to show at the top of the page.', '@@text-domain' ) . '<br><br><em>' . __( 'Notes: (1) When not using the default page template, featured image can only displayed above the content. (2) The full-screen option will work best when a Transparent Header is set.', '@@text-domain' ) . '</em>',
				'std'       => 'default',
				'type'      => 'radio',
				'options'   => array(
					'default'   => __( 'Use default setting', '@@text-domain' ),
					'fw'        => __( 'Full width, above content', '@@text-domain' ),
					'fs'        => __( 'Full screen parallax, above content', '@@text-domain' ),
					'full'      => __( 'Standard, with content', '@@text-domain' ),
					'hide'      => __( 'Hide featured images', '@@text-domain' ),
				),
			),
			'section_start' => array(
				'type'      => 'subgroup_start',
				'class'     => 'show-hide-toggle',
			),
			'tb_thumb_link' => array(
				'id'        => '_tb_thumb_link',
				'name'      => __( 'Featured Image Link', '@@text-domain' ),
				'desc'      => __( 'Here you can select how you\'d like this page\'s featured image to react when clicked, if you\'ve set one.', '@@text-domain' ) . '<br><br><em>' . __( 'Note: This does not apply when the featured image is displayed above the content.', '@@text-domain' ) . '</em>',
				'type'      => 'radio',
				'std'       => 'inactive',
				'options'   => array(
					'inactive'  => __( 'Featured image is not a link', '@@text-domain' ),
					'thumbnail' => __( 'It links to its enlarged lightbox version', '@@text-domain' ),
					'image'     => __( 'It links to a custom lightbox image', '@@text-domain' ),
					'video'     => __( 'It links to a lightbox video', '@@text-domain' ),
					'external'  => __( 'It links to a webpage', '@@text-domain' ),
				),
				'class'     => 'trigger',
			),
			'tb_image_link' => array(
				'id'        => '_tb_image_link',
				'name'      => __( 'Featured Image - Image Link', '@@text-domain' ),
				'desc'      => __( 'Enter the full URL of enlarged image that the featured image will link to.', '@@text-domain' ) . '<br><br>' . __( 'Ex: http://your-site.com/uploads/image.jpg', '@@text-domain' ),
				'type'      => 'text',
				'class'     => 'hide receiver receiver-image',
			),
			'tb_video_link' => array(
				'id'        => '_tb_video_link',
				'name'      => __( 'Featured Image - Video Link', '@@text-domain' ),
				'desc'      => __( 'Enter the full webpage URL to a video from YouTube or Vimeo.', '@@text-domain' ) . '<br><br>' . __( 'Ex', '@@text-domain' ) . ': http://www.youtube.com/watch?v=ginTCwWfGNY<br>' . __( 'Ex', '@@text-domain' ) . ': http://vimeo.com/11178250',
				'type'      => 'text',
				'class'     => 'hide receiver receiver-video',
			),
			'tb_external_link' => array(
				'id'        => '_tb_external_link',
				'name'      => __( 'Featured Image - External Link', '@@text-domain' ),
				'desc'      => __( 'Enter the full URL of where the featured image will link.', '@@text-domain' ) . '<br><br>' . __( 'Ex: http://google.com', '@@text-domain' ),
				'type'      => 'text',
				'class'     => 'hide receiver receiver-external',
			),
			'tb_external_link_target' => array(
				'id'        => '_tb_external_link_target',
				'name'      => __( 'Featured Image - External Link Target', '@@text-domain' ),
				'desc'      => __( 'Select whether you\'d like the external link to open in a new window or not.', '@@text-domain' ),
				'type'      => 'radio',
				'std'       => '_blank',
				'options'   => array(
					'_blank'    => __( 'Open link in new window', '@@text-domain' ),
					'_self'     => __( 'Open link in same window', '@@text-domain' ),
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
	 * @since @@name-framework 2.0.0
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
	 *          @link http://dev.themeblvd.com/tutorial/formatting-options/
	 *     }
	 * }
	 */
	return apply_filters( 'themeblvd_page_meta', $setup );

}

/**
 * Build and filter data for meta box, "Theme Layout."
 *
 * @since @@name-framework 2.5.0
 *
 * @return $setup All data passed to Theme_Blvd_Meta_Box.
 */
function setup_themeblvd_layout_meta() {

	$setup = array(
		'config' => array(
			'id'        => 'tb_layout_options',                   // Make it unique.
			'title'     => __( 'Theme Layout', '@@text-domain' ), // Title to show for entire meta box.
			'screen'    => array( 'page', 'post' ),               // Can contain post, page, link, or custom post type's slug.
			'context'   => 'side',                                // normal, advanced, or side
			'priority'  => 'core',                                // high, core, default, or low
		),
		'options' => array(
			'layout_header' => array(
				'id'        => '_tb_layout_header',
				'name'      => __( 'Header', '@@text-domain' ),
				'desc'      => __( 'Note: The transparent header option will work best when a large graphic is displayed prominently at the top of the page.', '@@text-domain' ) . ' <a href="https://vimeo.com/165052766" target="_blank">' . __( 'Learn More', '@@text-domain' ) . '</a>',
				'type'      => 'select',
				'options'   => array(
					'default'       => __( 'Standard Header', '@@text-domain' ),
					'suck_up'       => __( 'Transparent Header', '@@text-domain' ),
					'hide'          => __( 'Hide Header', '@@text-domain' ),
				),
			),
			'layout_footer' => array(
				'id'        => '_tb_layout_footer',
				'name'      => __( 'Footer', '@@text-domain' ),
				'type'      => 'select',
				'options'   => array(
					'default'       => __( 'Standard Footer', '@@text-domain' ),
					'hide'          => __( 'Hide Footer', '@@text-domain' ),
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
	 * @since @@name-framework 2.5.0
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
	 *          @link http://dev.themeblvd.com/tutorial/formatting-options/
	 *     }
	 * }
	 */
	return apply_filters( 'themeblvd_layout_meta', $setup );

}

/**
 * Build and filter data for meta box, "Post Template Options."
 *
 * @since @@name-framework 2.5.0
 *
 * @return $setup All data passed to Theme_Blvd_Meta_Box.
 */
function setup_themeblvd_pto_meta() {

	$setup = array(
		'config' => array(
			'id'         => 'pto',                                          // Make it unique.
			'title'      => __( 'Post Template Options', '@@text-domain' ), // Title to show for entire meta box.
			'screen'     => array( 'page' ),                                // Can contain post, page, link, or custom post type's slug.
			'context'    => 'normal',                                       // normal, advanced, or side
			'priority'   => 'low',                                          // high, core, default, or low
			'save_empty' => false,                                          // Whether to save empty values to custom fields
		),
		'options' => array(
			'desc' => array(
				// translators: 1: link to WP_Query documentation on WordPress Codex
				'desc'      => sprintf( __( 'Below are the custom fields you can use with the Blog, Post List, Post Grid, and Post Showcase page templates. When working with these options, you can find a lot of helpful information by viewing WordPress\'s Codex page on the %s.', '@@text-domain' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query" target="_blank">WP Query</a>' ),
				'type'      => 'info',
			),
			'cat' => array(
				'id'        => 'cat',
				'name'      => __( 'cat', '@@text-domain' ),
				'desc'      => __( 'Category ID(s) to include/exclude.', '@@text-domain' ) . '<br>' . __( 'Ex: 1', '@@text-domain' ) . '<br>' . __( 'Ex: 1,2,3', '@@text-domain' ) . '<br>' . __( 'Ex: -1,-2,-3', '@@text-domain' ),
				'type'      => 'text',
			),
			'category_name' => array(
				'id'        => 'category_name',
				'name'      => __( 'category_name', '@@text-domain' ),
				'desc'      => __( 'Category slug(s) to include.', '@@text-domain' ) . '<br>' . __( 'Ex: cat-1', '@@text-domain' ) . '<br>' . __( 'Ex: cat-1,cat-2', '@@text-domain' ),
				'type'      => 'text',
			),
			'tag' => array(
				'id'        => 'tag',
				'name'      => __( 'tag', '@@text-domain' ),
				'desc'      => __( 'Tag(s) to include.', '@@text-domain' ) . '<br>' . __( 'Ex: tag-1', '@@text-domain' ) . '<br>' . __( 'Ex: tag-1,tag-2', '@@text-domain' ),
				'type'      => 'text',
			),
			'posts_per_page' => array(
				'id'        => 'posts_per_page',
				'name'      => __( 'posts_per_page', '@@text-domain' ),
				'desc'      => __( 'Number of posts per page. This option gets used for the Blog template, Post List template, and when using "masonry" style display for the Post Grid and Post Showcase template. Standard grid view for Post Grid and Post Showcase templates use rows*columns.', '@@text-domain' ),
				'type'      => 'text',
			),
			'orderby' => array(
				'id'        => 'orderby',
				'name'      => __( 'orderby', '@@text-domain' ),
				'desc'      => __( 'What to order posts by &mdash; date, title, rand, etc.', '@@text-domain' ) . '<br><a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">' . __( 'Learn More', '@@text-domain' ) . '</a>',
				'type'      => 'text',
			),
			'order' => array(
				'id'        => 'order',
				'name'      => __( 'order', '@@text-domain' ),
				'desc'      => __( 'How to order posts &mdash; ASC or DESC.', '@@text-domain' ) . '<br><a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">' . __( 'Learn More', '@@text-domain' ) . '</a>',
				'type'      => 'text',
			),
			'query' => array(
				'id'        => 'query',
				'name'      => __( 'query', '@@text-domain' ),
				'desc'      => __( 'A custom query string. This will override other options.', '@@text-domain' ) . '<br>' . __( 'Ex: tag=baking', '@@text-domain' ) . '<br>' . __( 'Ex: post_type=my_type&my_tax=my_term', '@@text-domain' ),
				'type'      => 'text',
			),
			'columns' => array(
				'id'        => 'columns',
				'name'      => __( 'columns', '@@text-domain' ),
				'desc'      => __( 'Number of columns for Post Grid or Post Showcase template, which can be 2-5. When empty, default value is used from theme options page.', '@@text-domain' ),
				'type'      => 'text',
			),
			'rows' => array(
				'id'        => 'rows',
				'name'      => __( 'rows', '@@text-domain' ),
				'desc'      => __( 'Number of rows for Post Grid and Post Showcase templates. When empty, default value is used from theme options page.', '@@text-domain' ) . '<br><br><em>' . __( 'Note: This option does not apply when using masonry.', '@@text-domain' ) . '</em>',
				'type'      => 'text',
			),
			'tb_display' => array(
				'id'        => 'tb_display',
				'name'      => __( 'tb_display', '@@text-domain' ),
				'desc'      => __( 'When using Post Grid and Post Showcase template, this custom field allows you to override the default display option.', '@@text-domain' ),
				'type'      => 'select',
				'options'   => array(
					'0'                 => __( 'Use default setting', '@@text-domain' ),
					'paginated'         => __( 'Standard', '@@text-domain' ),
					'masonry_paginated' => __( 'Masonry', '@@text-domain' ),
				),
			),
		),
	);

	/**
	 * Filters all data passed to the Theme_Blvd_Meta_Box
	 * class when creating the object for the "Post Template
	 * Options" meta box.
	 *
	 * @since @@name-framework 2.5.0
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
	 *          @link http://dev.themeblvd.com/tutorial/formatting-options/
	 *     }
	 * }
	 */
	return apply_filters( 'themeblvd_pto_meta', $setup );

}