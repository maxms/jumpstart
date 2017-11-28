<?php
/**
 * Template Name: Blank Page
 *
 * WARNING: This template file is a core part of the
 * Theme Blvd WordPress Framework. It is advised
 * that any edits to the way this file displays its
 * content be done with via actions, filters, and
 * template parts.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */
?><!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

	<?php themeblvd_get_template_part( 'head' ); ?>

</head>

<body <?php body_class(); ?>>

	<?php
	/** This action is documented in header.php */
	do_action( 'themeblvd_before' );
	?>

	<div id="blank-page">

		<div class="wrap">

			<div id="content" class="clearfix" role="main">

				<div class="inner">

					<?php themeblvd_content_top(); ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php themeblvd_get_template_part( 'page' ); ?>

					<?php endwhile; ?>

					<?php themeblvd_content_bottom(); ?>

				</div><!-- .inner -->

			</div><!-- #content -->

		</div><!-- .wrap -->

	</div><!-- #blank-page -->

	<?php
	/** This action is documented in footer.php */
	do_action( 'themeblvd_after' );
	?>

	<?php wp_footer(); ?>

</body>

</html>
