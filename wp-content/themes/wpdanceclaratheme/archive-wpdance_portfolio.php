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
				<h1 class="archive-title">
					<?php 
					if (is_tax('wpdance_portfolio_category'))
						echo esc_html(sprintf(_x("Category: %s", 'portfolio title', 'wpdanceclaratheme'), single_term_title('', false)));

					elseif (is_tax('wpdance_portfolio_client'))
						echo esc_html(sprintf(_x("Client: %s", 'portfolio title', 'wpdanceclaratheme'), single_term_title('', false)));

					elseif (is_tax('wpdance_portfolio_skill'))
						echo esc_html(sprintf(_x("Skill: %s", 'portfolio title', 'wpdanceclaratheme'), single_term_title('', false)));

					else
						esc_html_e("Portfolio", 'wpdanceclaratheme');
					?>
				</h1>
				<?php wpdanceclaratheme\Helper\breadcrumb() ?>
			</header><!-- .archive-header -->

			<div class="grid-<?php echo wpdanceclaratheme\Helper\get_loop_col(); ?>">
				<?php /* The loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
					
					<article id="portfolio-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="entry-container">

							<?php if (has_post_thumbnail() && !post_password_required() && !is_attachment()) : ?>
							<div class="entry-thumbnail">
								<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
									<?php the_post_thumbnail(\wpdanceclaratheme\Helper\is_the_post_image_style_landscape() ? 'wpdance-portfolio-thumbnail-landscape' : 'wpdance-portfolio-thumbnail'); ?>
								</a>
							</div>
							<?php endif; ?>

							<header class="entry-header">
								<h3 class="entry-title"><?php the_title(); ?></h3>
							</header><!-- .entry-header -->

							<div class="entry-links">
								<a href="<?php the_post_thumbnail_url('large') ?>" title="<?php echo esc_attr(sprintf(_x("Preview: %s", 'portfolio entry links', 'wpdanceclaratheme'), get_the_title())); ?>" class="preview-link colorbox-link-1"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
								<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>" class="post-link"><i class="fa fa-link" aria-hidden="true"></i></a>
							</div>
							
						</div><!-- .entry-container -->
					</article><!-- #post -->


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
