<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-container">
		<header class="entry-header">
			<?php if ( has_post_thumbnail() && ! post_password_required() && ! is_attachment() ) : ?>
			<div class="entry-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div>
			<?php endif; ?>

			<div class="entry-meta">
				<?php wpdanceclaratheme\Helper\entry_meta(); ?>
				<?php edit_post_link( esc_html__( 'Edit', 'wpdanceclaratheme' ), '<span class="edit-link">', '</span>' ); ?>
			
				<?php if ( comments_open() && ! is_single() ) : ?>
					<div class="comments-link">
						<?php comments_popup_link( '<span class="leave-reply">' . esc_html__( 'Leave a comment', 'wpdanceclaratheme' ) . '</span>', esc_html__( 'One comment so far', 'wpdanceclaratheme' ), esc_html__( 'View all % comments', 'wpdanceclaratheme' ) ); ?>
					</div><!-- .comments-link -->
				<?php endif; // comments_open() ?>
			
			</div><!-- .entry-meta -->

			<?php if ( is_single() ) : ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php wpdanceclaratheme\Helper\breadcrumb() ?>
			<?php else : ?>
			<h3 class="entry-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h3>
			<?php endif; // is_single() ?>
		</header><!-- .entry-header -->

		<?php if ( is_search() || wpdanceclaratheme\Helper\show_excerpt()) : ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
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
		<?php endif; ?>

		<footer class="entry-meta">
			<?php if (is_single()) wpdanceclaratheme\Helper\post_tag_list(); ?>
			<?php if ( is_single() && get_the_author_meta( 'description' ) && is_multi_author() ) : ?>
				<?php get_template_part( 'author-bio' ); ?>
			<?php endif; ?>
		</footer><!-- .entry-meta -->
	</div>
</article><!-- #post -->
