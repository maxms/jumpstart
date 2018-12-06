<?php
/**
 * The template used for displaying posts in
 * a grid.
 *
 * @link https://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */
?>
<div class="grid-item <?php echo esc_attr( themeblvd_get_att( 'class' ) ); ?>">

	<article <?php post_class(); ?>>

		<?php if ( has_post_format( 'gallery' ) ) : ?>

			<div class="featured-item featured-gallery">

				<?php
				themeblvd_mini_gallery_slider(
					get_the_content(),
					array(
						'size' => themeblvd_get_att( 'crop' ),
					)
				);
				?>

			</div><!-- .featured-gallery -->

		<?php elseif ( has_post_format( 'video' ) ) : ?>

			<div class="featured-item featured-video">

				<?php themeblvd_content_video( true ); ?>

			</div><!-- .featured-video -->

		<?php elseif ( has_post_format( 'audio' ) ) : ?>

			<div class="featured-item featured-audio">

				<?php themeblvd_content_audio( true ); ?>

			</div><!-- .featured-audio -->

		<?php elseif ( themeblvd_get_att( 'thumbs' ) ) : ?>

			<div class="featured-item featured-image">

				<?php
				themeblvd_the_post_thumbnail(
					themeblvd_get_att( 'crop' ),
					array(
						'placeholder' => true,
					)
				);
				?>

			</div><!-- .featured-item -->

		<?php endif; ?>

		<h2 class="entry-title"><?php themeblvd_the_title(); ?></h2>

		<?php if ( themeblvd_get_att( 'show_meta' ) ) : ?>

			<?php themeblvd_grid_meta(); ?>

		<?php endif; ?>

		<?php if ( themeblvd_get_att( 'excerpt' ) ) : ?>

			<div class="entry-content">

				<?php the_excerpt(); ?>

			</div><!-- .entry-content -->

		<?php endif; ?>

		<?php if ( 'button' === themeblvd_get_att( 'more' ) ) : ?>

			<?php
			echo themeblvd_button(
				themeblvd_get_att( 'more_text' ),
				get_permalink( get_the_ID() ),
				'default',
				'_self',
				'small',
				'read-more',
				get_the_title( get_the_ID() )
			);
			?>

		<?php elseif ( 'text' === themeblvd_get_att( 'more' ) ) : ?>

			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<?php echo themeblvd_get_att( 'more_text' ); ?>
			</a>

		<?php endif; ?>

	</article><!-- #post-<?php the_ID(); ?> -->

</div><!-- .grid-item -->
