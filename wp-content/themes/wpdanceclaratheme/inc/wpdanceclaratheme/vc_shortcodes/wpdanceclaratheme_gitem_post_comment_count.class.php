<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-gitem-post-data.php' );

class WPBakeryShortCode_wpdanceclaratheme_gitem_post_comment_count extends WPBakeryShortCode_VC_Gitem_Post_Data {
	protected function getFileName() {
		return 'vc_gitem_post_data';
	}
	
	/**
	 * {inheritdoc}
	 */
	public function getDataSource( array $atts ) {
		return 'wpdanceclaratheme_post_comment_count';
	}
}
