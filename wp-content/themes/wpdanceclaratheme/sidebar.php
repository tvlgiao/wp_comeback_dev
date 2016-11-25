<?php
/**
 * The sidebar containing the secondary widget area
 *
 * Displays on posts and pages.
 *
 * If no active widgets are in this sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage WPDanceClaraTheme
 * @since WPDanceClaraTheme 1.0
 */

$push = wpdanceclaratheme\Helper\get_main_push_size('previous_layout');

if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
	<div id="tertiary" class="<?php echo esc_attr(wpdanceclaratheme\Helper\get_main_sidebar_class()); ?> sidebar-container" role="complementary">
		<div class="sidebar-inner">
			<div class="widget-area">
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			</div><!-- .widget-area -->
		</div><!-- .sidebar-inner -->
	</div><!-- #tertiary -->
<?php endif; ?>
