<?php
/**
 * The template for displaying posts in the Quote post format
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-container">
		<div class="entry-meta">
			<?php wpdanceclaratheme\Helper\entry_meta(); ?>

			<?php if ( comments_open() && ! is_single() ) : ?>
			<span class="comments-link">
				<?php comments_popup_link( '<span class="leave-reply">' . esc_html__( 'Leave a comment', 'wpdanceclaratheme' ) . '</span>', esc_html__( 'One comment so far', 'wpdanceclaratheme' ), esc_html__( 'View all % comments', 'wpdanceclaratheme' ) ); ?>
			</span><!-- .comments-link -->
			<?php endif; // comments_open() ?>
			<?php edit_post_link( esc_html__( 'Edit', 'wpdanceclaratheme' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->

		<div class="entry-content">
			<?php
				/* translators: %s: Name of current post */
				the_content( sprintf(
					wp_kses(__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'wpdanceclaratheme' ), \wpdanceclaratheme\Config::$allowed_html),
					the_title( '<span class="screen-reader-text">', '</span>', false )
				) );

				wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'wpdanceclaratheme' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
			?>
		</div><!-- .entry-content -->
	</div>
</article><!-- #post -->
