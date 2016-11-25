<?php
/**
 * The template for displaying posts in the Link post format
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */

$heading_pos = get_theme_mod('wpdanceclaratheme_layout_general_heading_position', 'standard');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-container">
		<header class="entry-header">
			<div class="entry-meta">
				<?php wpdanceclaratheme\Helper\entry_date(); ?>
				<?php edit_post_link( esc_html__( 'Edit', 'wpdanceclaratheme' ), '<span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-meta -->

			<h1 class="entry-title">
				<a href="<?php echo esc_url( wpdanceclaratheme\Helper\get_link_url() ); ?>"><?php the_title(); ?></a>
			</h1>
		</header><!-- .entry-header -->

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

		<?php if ( is_single() ) : ?>
		<footer class="entry-meta">
			<?php wpdanceclaratheme\Helper\entry_meta(); ?>
			<?php if ( get_the_author_meta( 'description' ) && is_multi_author() ) : ?>
				<?php get_template_part( 'author-bio' ); ?>
			<?php endif; ?>
		</footer><!-- .entry-meta -->
		<?php endif; // is_single() ?>
	</div>
</article><!-- #post -->
