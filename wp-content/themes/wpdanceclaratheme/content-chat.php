<?php
/**
 * The template for displaying posts in the Chat post format
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-container">
		
		<header class="entry-header">
			<div class="entry-meta">
				<?php wpdanceclaratheme\Helper\entry_meta(); ?>
				<?php edit_post_link( esc_html__( 'Edit', 'wpdanceclaratheme' ), '<span class="edit-link">', '</span>' ); ?>
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

		
	</div>
</article><!-- #post -->
