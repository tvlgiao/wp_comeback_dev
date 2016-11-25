<?php
/**
 * The Header template Style 01 for our theme
 *
 * This file is included by header.php via function get_template_part()
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */


$header = wpdanceclaratheme\Helper\get_header();

?>

<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
		<header id="masthead" class="site-header">

			<div class="container">
			<?php if ($header !== null): ?>
				<?php echo $header; ?>
			<?php else: ?>
				<div class="header-default">

					<div class="header-section-1">
						<div class="row">
							<div class="col-md-3 col-md-offset-9 text-right">
								<?php wpdanceclaratheme\Helper\user_links(); ?>
								
							</div>
						</div>
					</div><!-- .header-section-1 -->

					<div class="header-section-2">
						<div class="row">
							<div class="col-md-6">
								<?php wpdanceclaratheme\Helper\site_header(); ?>
							</div>
							<div class="col-md-3 col-md-offset-3 text-right">
								<div class="vc_wp_search">
									<?php get_search_form(); ?>
								</div>
							</div>
						</div>
					</div><!-- .header-section-2 -->

					<div class="header-section-3">
						<div class="row">
							<div class="col-md-12">
								<div id="navbar" class="navbar">
									<nav id="site-navigation" class="navigation main-navigation">
										<button class="menu-toggle"><?php esc_html_e( 'Menu', 'wpdanceclaratheme' ); ?></button>
										<a class="screen-reader-text skip-link" href="#content" title="<?php esc_attr_e( 'Skip to content', 'wpdanceclaratheme' ); ?>"><?php esc_html_e( 'Skip to content', 'wpdanceclaratheme' ); ?></a>
										<?php 
										wp_nav_menu(array(
											'theme_location' => wpdanceclaratheme\Config::$default_menu_location, 
											'menu_class' => 'nav-menu', 
											'menu_id' => wpdanceclaratheme\Config::$default_menu_location.'-menu'));
										?>
									</nav><!-- #site-navigation -->
								</div><!-- #navbar -->
							</div><!-- .col-md-12 -->
						</div><!-- .row -->
					</div><!-- .header-section-3 -->
				</div><!-- .header-default -->
			<?php endif; ?>
			</div><!-- .container -->


		</header><!-- #masthead -->
	<div class="container">
			<div id="main" class="row site-main">
