<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, WPDanceClaraTheme
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */

get_header(); ?>

	<div id="primary" class="<?php echo esc_attr(wpdanceclaratheme\Helper\get_primary_div_class()); ?> content-area">
		<?php get_sidebar('before-content') ?>
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php
					if ( is_day() ) :
						printf( esc_html__( 'Daily Archives: %s', 'wpdanceclaratheme' ), get_the_date() );
					elseif ( is_month() ) :
						printf( esc_html__( 'Monthly Archives: %s', 'wpdanceclaratheme' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'wpdanceclaratheme' ) ) );
					elseif ( is_year() ) :
						printf( esc_html__( 'Yearly Archives: %s', 'wpdanceclaratheme' ), get_the_date( _x( 'Y', 'yearly archives date format', 'wpdanceclaratheme' ) ) );
					else :
						esc_html_e( 'Archives', 'wpdanceclaratheme' );
					endif;
				?></h1>
				<?php wpdanceclaratheme\Helper\breadcrumb() ?>
			</header><!-- .archive-header -->

			<div class="grid-<?php echo wpdanceclaratheme\Helper\get_loop_col(); ?>">
				<?php /* The loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', get_post_format() ); ?>
				<?php endwhile; ?>
			</div><!-- .grid-* -->

			<?php wpdanceclaratheme\Helper\paging_nav(); ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
		<?php get_sidebar('after-content'); ?>
	</div><!-- #primary -->

<?php if (wpdanceclaratheme\Helper\should_show_main_sidebar()) get_sidebar(); ?>
<?php get_footer(); ?>
