<?php
/**
 * Sidebar wpdanceclaratheme-before-content
 */


if ( is_active_sidebar( 'wpdanceclaratheme-before-content' ) ) : ?>
	<div id="sidebar_wpdanceclaratheme_before_content" class="sidebar-container row" role="complementary">
		<div class="sidebar-inner">
			<div class="widget-area">
				<?php dynamic_sidebar( 'wpdanceclaratheme-before-content' ); ?>
			</div><!-- .widget-area -->
		</div><!-- .sidebar-inner -->
	</div><!-- #sidebar_wpdanceclaratheme_before_content -->
<?php endif; ?>
