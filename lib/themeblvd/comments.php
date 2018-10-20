<?php
/**
 * The template for displaying Comments.
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
?>

<?php if ( themeblvd_show_comments() ) : ?>

	<?php if ( post_password_required() ) : ?>

		<div id="comments" class="nopassword">

			<p>
				<?php echo themeblvd_get_local( 'comments_no_password' ); ?>
			</p>

		</div><!-- #comments -->

		<?php return; ?>

	<?php endif; ?>

	<div id="comments">

		<?php if ( have_comments() ) : ?>

			<!-- COMMENTS (start) -->

			<h2 id="comments-title">
				<?php themeblvd_comments_title(); ?>
			</h2>

			<ol class="commentlist">

				<?php wp_list_comments( themeblvd_get_comment_list_args() ); ?>

			</ol>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>

				<nav id="comment-nav-below">

					<h1 class="assistive-text">
						<?php echo themeblvd_get_local( 'comment_navigation' ); ?>
					</h1>

					<div class="nav-previous">

						<?php previous_comments_link( themeblvd_get_local( 'comments_older' ) ); ?>

					</div>

					<div class="nav-next">

						<?php next_comments_link( themeblvd_get_local( 'comments_newer' ) ); ?>

					</div>

				</nav><!-- #comment-nav-below -->

			<?php endif; ?>

			<!-- COMMENTS (end) -->

		<?php endif; // End if has_comments(). ?>

		<?php if ( comments_open() ) : ?>

			<!-- COMMENT FORM (start) -->

			<div class="comment-form-wrapper">

				<div class="comment-form-inner">

					<?php comment_form( themeblvd_get_comment_form_args() ); ?>

				</div><!-- .comment-form-inner -->

			</div><!-- .comment-form-wrapper -->

			<!-- COMMENT FORM (end) -->

		<?php else : ?>

			<p class="nocomments">
				<?php echo themeblvd_get_local( 'comments_closed' ); ?>
			</p>

		<?php endif; ?>

	</div><!-- #comments -->

<?php endif; ?>
