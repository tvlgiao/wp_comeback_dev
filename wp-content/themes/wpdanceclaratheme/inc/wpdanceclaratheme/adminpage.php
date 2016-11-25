<?php
namespace wpdanceclaratheme\AdminPage;

# Stop if not admin page
if (!is_admin() || defined('DOING_AJAX')) return;


add_action('admin_init', __NAMESPACE__.'\\admin_init');
add_action('admin_menu', __NAMESPACE__.'\\admin_menu');


function admin_init() {
	wp_register_style('wpdanceclaratheme-adminpage-style', get_template_directory_uri().'/css/adminpage.css', array(), \wpdanceclaratheme\Config::VERSION);
	wp_register_script('wpdanceclaratheme-adminpage-script', get_template_directory_uri().'/js/adminpage.js', array('jquery', 'jquery'), \wpdanceclaratheme\Config::VERSION, true);
}

function admin_menu() {

	$submenu = add_theme_page(
		esc_html__("WPDanceClaraTheme: Walkthrough", 'wpdanceclaratheme'),
		esc_html__("WPDanceClaraTheme", 'wpdanceclaratheme'),
		'manage_options',
		'wpdanceclaratheme-walkthrough-page',
		__NAMESPACE__.'\\walkthrough_page'
	);

	add_action('admin_print_styles-'.$submenu, __NAMESPACE__.'\\admin_styles');
	add_action('admin_print_scripts-'.$submenu, __NAMESPACE__.'\\admin_scripts');
}



function admin_styles() {
	wp_enqueue_style('wpdanceclaratheme-adminpage-style');
}

function admin_scripts() {
	wp_enqueue_script('wpdanceclaratheme-adminpage-script');
}


function this_url($params = '') {
	return add_query_arg($params, '', network_admin_url().'themes.php?page=wpdanceclaratheme-walkthrough-page');
}

function install_plugins_url($params = '') {
	return add_query_arg($params, '', network_admin_url().'themes.php?page=tgmpa-install-plugins');	
}

function themes_url($params = '') {
	return add_query_arg($params, '', network_admin_url().'themes.php');	
}

function revslider_url($params = '') {
	return add_query_arg($params, '', network_admin_url().'admin.php?page=revslider');	
}

function import_url($params = '') {
	return add_query_arg($params, '', network_admin_url().'import.php');	
}

function customizer_url($params = '') {
	return add_query_arg($params, '', network_admin_url().'customize.php?return='.esc_url(this_url()));	
}

function userguide_url() {
	return \wpdanceclaratheme\Config::$userguide_url;
}

function support_url() {
	return \wpdanceclaratheme\Config::$support_url;
}

function rating_url() {
	return \wpdanceclaratheme\Config::$rating_url;
}

function raise_walkthrough_notice_after_activated() {
	$msg_id = 'wpdanceclaratheme_walkthrough_start';
	$notice = \wpdanceclaratheme\AdminNotice::instance();
	\wpdanceclaratheme\AdminNotice::updated(sprintf(wp_kses(__("Theme WPDanceClaraTheme is activated. We highly recommend to check out the Quickstart guide now! Go to <strong>Appearance</strong> &gt; <strong>WPDanceClaraTheme</strong> or click button <a href='%s' class='button button-primary'>Theme Quickstart</a>", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html), this_url()), false, true, 'wpdanceclaratheme-walkthrough-page', $msg_id);
}

function walkthrough_page() {


	# Default walkthrough values
	$walkthrough = array(
		'get_started'             => array(
			'complete'            => false,   // TRUE if user clicked get started button
			'skip'                => false,
		),
		'install_plugins'         => array(
			'complete'            => false,   // TRUE if all recommended plugins are activated exclude paid plugin (UberMenu)
			'missing'             => array(   // contains name of any of recommanded plugins is not activated include paid plugin (UberMenu)
				'htmlblocks'      => array(),
				'gridbuilder'     => array(),
				'ubermenu'        => array(),
			),
			'skip'                => false,   // TRUE if user clicked skip button
		),

		'import_data'             => array(
			'complete'            => false,   // TRUE if all data come with the theme are imported
			'missing'             => array(), // contains array of missing data due to user removed
			'skip'                => false,   // TRUE if user click skip button
		),

		'import_sliders'          => array(
			'complete'            => false,   // TRUE if all sliders exist
			'missing'             => array(), // contains array of theme sample's slider slug name which has not been imported yet
			'skip'                => false,   // TRUE if user click skip button
		),

		'import_samples'          => array(
			'complete'            => false,   // TRUE if user click Confirm button
			'skip'                => false,   // TRUE if user click skip button
		),

		'config_header_footer'    => array(
			'complete'            => false,   // TRUE if HTML Block header & footer is set in theme mod
			'skip'                => false,   // TRUE if user click skip button
			'header'              => '',      // header slug
			'footer'              => '',      // footer slug
		),

		'config_home'   => array(
			'complete'            => false,   // TRUE front page is a page
			'skip'                => false,   // TRUE if user click skip button
		),

		'learn_customizer'        => array(
			'complete'            => false,   // TRUE if user click Confirm button
			'skip'                => false,   // TRUE if user click skip button
		),

		'learn_single_config'     => array(
			'complete'            => false,   // TRUE if user click Confirm button
			'skip'                => false,   // TRUE if user click skip button
		),

	);


	# Read walkthrough value from theme mod
	if (!@$_REQUEST['restart'])
		$walkthrough = get_theme_mod('wpdanceclaratheme_walkthrough', $walkthrough);



	# -------------------------------------------------------------------------
	# Step: Get Started
	# -------------------------------------------------------------------------

	if (@$_REQUEST['confirm'] == 'get_started')
		$walkthrough['get_started']['complete'] = true;

	if (@$_REQUEST['skip'] == 'get_started')
		$walkthrough['get_started']['skip'] = true;


	# -------------------------------------------------------------------------
	# Step: Check Install Plugins
	# -------------------------------------------------------------------------

	/**
	 * Check recommended plugins has not been activated
	 *
	 * @var array $walkthrough['install_plugins']['missing']
	 * @var boolean $walkthrough['install_plugins']['complete']
	 */
	$walkthrough['install_plugins']['missing'] = array();
	$walkthrough['install_plugins']['complete'] = true;
	if (@$_REQUEST['skip'] == 'install_plugins')
		$walkthrough['install_plugins']['skip'] = true;
	
	$tgmpa = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
	
	$plugins = \wpdanceclaratheme\TGMPA\get_plugins();
	foreach ($plugins as $plugin) {
		if (!$tgmpa->is_plugin_active($plugin['slug'])) {
			$walkthrough['install_plugins']['missing'][] = $plugin['name'];

			if (!isset($plugin['walkthrough_complete_skip']) || !$plugin['walkthrough_complete_skip'])
				$walkthrough['install_plugins']['complete'] = false;
		}
	}


	# -------------------------------------------------------------------------
	# Step: Check Import Theme Data
	# -------------------------------------------------------------------------


	/**
	 * Check if required data come with the theme missing
	 *
	 * @var array $walkthrough['import_data']['missing']
	 * @var boolean $walkthrough['import_data']['complete']
	 */
	if (@$_REQUEST['action'] == 'import_data') {
		\wpdanceclaratheme\Setup\import_all();
		wp_redirect(this_url());
		exit;
	}

	$walkthrough['import_data']['missing'] = \wpdanceclaratheme\Setup\get_missing_data();
	$walkthrough['import_data']['complete'] = true;
	if (@$_REQUEST['skip'] == 'import_data')
		$walkthrough['import_data']['skip'] = true;

	foreach ($walkthrough['import_data']['missing'] as $k => $a) {
		if (!empty($a)) {
			$walkthrough['import_data']['complete'] = false;
			break;
		}
	}


	# -------------------------------------------------------------------------
	# Step: Check Revolution Sliders Imported
	# -------------------------------------------------------------------------


	/**
	 * Check Revolution sliders come with the theme are imported
	 *
	 * @var array $walkthrough['import_sliders']['missing']
	 * @var boolean $walkthrough['import_sliders']['complete']
	 */
	if (\wpdanceclaratheme\Setup\is_revslider_active()) {
		$walkthrough['import_sliders']['missing'] = \wpdanceclaratheme\Setup\get_missing_revsliders();
		$walkthrough['import_sliders']['complete'] = empty($walkthrough['import_sliders']['missing']);
	}
	else {
		$walkthrough['import_sliders']['missing'] = array();
		$walkthrough['import_sliders']['complete'] = false;	
	}
	if (@$_REQUEST['skip'] == 'import_sliders')
		$walkthrough['import_sliders']['skip'] = true;


	# -------------------------------------------------------------------------
	# Step: Notify user about importing sample data
	# -------------------------------------------------------------------------

	if (@$_REQUEST['confirm'] == 'import_samples')
		$walkthrough['import_samples']['complete'] = true;

	if (@$_REQUEST['skip'] == 'import_samples')
		$walkthrough['import_samples']['skip'] = true;


	# -------------------------------------------------------------------------
	# Step: Check Custom Header & Footer is set in Customizer
	# -------------------------------------------------------------------------

	$walkthrough['config_header_footer']['header'] = get_theme_mod('wpdanceclaratheme_header_htmlblock', '');
	$walkthrough['config_header_footer']['footer'] = get_theme_mod('wpdanceclaratheme_footer_htmlblock', '');
	$walkthrough['config_header_footer']['complete'] = $walkthrough['config_header_footer']['header'] && $walkthrough['config_header_footer']['footer'];
	if (@$_REQUEST['skip'] == 'config_header_footer')
		$walkthrough['config_header_footer']['skip'] = true;


	# -------------------------------------------------------------------------
	# Step: Check if home is set as a page
	# -------------------------------------------------------------------------

	$walkthrough['config_home']['complete'] = get_option('show_on_front') == 'page';
	if (@$_REQUEST['skip'] == 'config_home')
		$walkthrough['config_home']['skip'] = true;


	# -------------------------------------------------------------------------
	# Step: Learn about Customizer
	# -------------------------------------------------------------------------

	if (@$_REQUEST['confirm'] == 'learn_customizer')
		$walkthrough['learn_customizer']['complete'] = true;

	if (@$_REQUEST['skip'] == 'learn_customizer')
		$walkthrough['learn_customizer']['skip'] = true;


	# -------------------------------------------------------------------------
	# Step: Learn about Customizer
	# -------------------------------------------------------------------------

	if (@$_REQUEST['confirm'] == 'learn_single_config')
		$walkthrough['learn_single_config']['complete'] = true;

	if (@$_REQUEST['skip'] == 'learn_single_config')
		$walkthrough['learn_single_config']['skip'] = true;
	

	# -------------------------------------------------------------------------


	# Store current walkthrough into theme mod so that we know where user are learning
	set_theme_mod('wpdanceclaratheme_walkthrough', $walkthrough);


	/**
	 * Determine whether all steps are done or skip not
	 * @var boolean
	 */
	$done = true;
	foreach ($walkthrough as $k => $a) {
		if (!$a['complete'] && !$a['skip']) {
			$done = false;
			break;
		}
	}


	#
	# Show admin notice message to come back the Walkthough page if need.
	# 
	$msg_id = 'wpdanceclaratheme_walkthrough_continue';
	$notice = \wpdanceclaratheme\AdminNotice::instance();
	if ($done) 
		$notice->dismiss($msg_id);
	elseif (($walkthrough['get_started']['complete'] || $walkthrough['get_started']['skip']) && !$notice->message_exists($msg_id)) {
		$notice->dismiss('wpdanceclaratheme_walkthrough_start');
		\wpdanceclaratheme\AdminNotice::nag(sprintf(wp_kses(__("Theme Walkthough is in progress. <a href='%s' class='button button-primary'>Return Theme Walkthrough</a>", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html), this_url()), false, true, 'wpdanceclaratheme-walkthrough-page', $msg_id);
	}
	unset($msg_id, $notice);

	/**
	 * Mark the rest sections to hide
	 * @var boolean
	 */
	$hide_the_rest = false;
	?>

	<div class="wrap wpdanceclaratheme_walkthrough_page" id="wpdanceclaratheme_walkthrough_page">
		<h2><?php esc_html_e("WPDanceClaraTheme - Get Started", 'wpdanceclaratheme'); ?></h2>
		<?php settings_errors(); ?>
		<br class="clear" />


		<?php
		# ---------------------------------------------------------------------
		# GET STARTED
		# ---------------------------------------------------------------------

		if (!$walkthrough['get_started']['complete']):
		?>
		<div class="card get-started <?php
				echo $walkthrough['get_started']['complete'] ? ' complete' : '';
				echo $walkthrough['get_started']['skip'] ? ' skip' : '';
			?>">
			<span class="dashicons dashicons-welcome-learn-more main-icon"></span>
			<p><strong><?php esc_html_e("This short guide will help you get started and quickly familiar with the theme. Teach you step by step how to build site like your demo site in few minutes without technical knowledge. Help you elimite too much time to read the complete user guide.", 'wpdanceclaratheme'); ?></strong></p>

			<p><a href="<?php echo esc_attr(this_url('confirm=get_started')); ?>" class="button button-primary"><?php esc_html_e("Let's get started now", 'wpdanceclaratheme'); ?></a></p>
		</div>
		<?php 
			# Hide the rest section if this step is not complete
			$hide_the_rest = true; 
		endif; /* get_started complete */ 
		?>



		<?php
		# ---------------------------------------------------------------------
		# COMPLETE WALKTHROUGH
		# ---------------------------------------------------------------------
		
		if ($done):
		?>
		<div class="card done">
			<span class="dashicons dashicons-awards main-icon"></span>
			<h3><?php esc_html_e("Congraturation! You have completed the tutorial.", 'wpdanceclaratheme'); ?></h3>
			<p><a id="wpdanceclaratheme_walkthrough_show_check_list" href="#" class="button button-secondary"><?php esc_html_e("Show check list", 'wpdanceclaratheme') ?></a>
		</div>
		<?php endif; /* done */ ?>




		<?php
		# ---------------------------------------------------------------------
		# CHECK RECOMMENDED PLUGINS INSTALLED
		# ---------------------------------------------------------------------
		
		if (!$hide_the_rest):
		?>
		<div class="card install-plugins <?php
				echo $walkthrough['install_plugins']['complete'] ? ' complete' : '';
				echo $walkthrough['install_plugins']['skip'] ? ' skip' : '';
				echo $done ? ' hide-done' : '';
			?>">

			<span class="dashicons dashicons-<?php echo $walkthrough['install_plugins']['complete'] ? 'yes' : 'no'; ?> main-icon"></span>

			<?php 
			# All plugins is activated ?
			if (empty($walkthrough['install_plugins']['missing'])):
			?>
			
			<h3><?php esc_html_e("All recommended plugins are already activated", 'wpdanceclaratheme'); ?></h3>

			<?php
			# There is plugin not activated:
			else:
			?>
			
			<h3><?php esc_html_e("Install and activate all recommended plugins", 'wpdanceclaratheme'); ?></h3>
			<div class="inside <?php 
					# Hide inside content if user click Skip button
					if ($walkthrough['install_plugins']['skip']) echo ' hide'; 
				?>">
				<p><?php echo sprintf(wp_kses(__("Some plugins are still not activated: <strong>%s</strong>", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html), implode(', ', $walkthrough['install_plugins']['missing'])); ?></p>

				<p class="main-instruction"><?php printf(wp_kses(__("Please go to <strong><a href=\"%s\">Install Plugins</a></strong> page to start install and activate the missing plugins.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html), esc_attr(install_plugins_url())); ?></p>

				<p><span class="dashicons dashicons-lightbulb"></span> <?php echo wp_kses(__("If plugin is <strong>commercial</strong> and <strong>not included</strong> in the theme you can ignore it.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>
				<p>
					<a class="button button-primary" href="<?php echo esc_attr(install_plugins_url()); ?>"><?php esc_html_e("Go to Install Plugins", 'wpdanceclaratheme'); ?></a>
					&nbsp;
					<a href="<?php echo esc_attr(this_url('skip=install_plugins')); ?>" class="button button-secondary"><?php esc_html_e("Skip this step", 'wpdanceclaratheme'); ?></a>
				</p>
			</div>

			<?php endif; /* install_plugins missing */ ?>

		</div>
		<?php
			# Hide the rest section if this step is not complete & is not skipped 
			if (!$walkthrough['install_plugins']['complete'] && !$walkthrough['install_plugins']['skip'])
				$hide_the_rest = true;
		endif; /* hide_the_rest */ 
		?>



		<?php
		# ---------------------------------------------------------------------
		# CHECK DEFAULT DATA IMPORTED
		# ---------------------------------------------------------------------

		if (!$hide_the_rest):
		?>
		<div class="card import-data <?php
				echo $walkthrough['import_data']['complete'] ? ' complete' : '';
				echo $walkthrough['import_data']['skip'] ? ' skip' : '';
				echo $done ? ' hide-done' : '';
			?>">

			<span class="dashicons dashicons-<?php echo $walkthrough['import_data']['complete'] ? 'yes' : 'no'; ?> main-icon"></span>

			<?php
			# All default data come with theme have already been imported?
			if (empty($walkthrough['import_data']['missing']['htmlblocks']) 
				&& empty($walkthrough['import_data']['missing']['gridbuilder']) 
				&& empty($walkthrough['import_data']['missing']['ubermenu'])):
			?>

			<h3><?php esc_html_e("All default data come with the theme are already imported", 'wpdanceclaratheme'); ?></h3>

			<?php
			# There is missing data have to be imported when activate the theme:
			else:
			?>

			<h3><?php esc_html_e("Import default data come with the theme", 'wpdanceclaratheme'); ?></h3>
			<div class="inside <?php
					# Hide inside content if user clicked Skip button
					if ($walkthrough['import_data']['skip']) echo ' hide';
				?>">
				<p><?php esc_html_e("The follow content is missing:", 'wpdanceclaratheme'); ?></p>

				<ul>
					<?php if (!empty($walkthrough['import_data']['missing']['htmlblocks'])): ?>
					<li><?php printf(wp_kses(__("Header / Footer Items: <code>%s</code>", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html), esc_html(implode(', ', $walkthrough['import_data']['missing']['htmlblocks']))); ?></li>
					<?php endif; ?>

					<?php if (!empty($walkthrough['import_data']['missing']['gridbuilder'])): ?>
					<li><?php printf(wp_kses(__("Visual Composer Grid Types: <code>%s</code>", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html), esc_html(implode(', ', $walkthrough['import_data']['missing']['gridbuilder']))); ?></li>
					<?php endif; ?>

					<?php if (!empty($walkthrough['import_data']['missing']['ubermenu'])): ?>
					<li><?php printf(wp_kses(__("Menu Configurations: <code>%s</code>", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html), esc_html(implode(', ', $walkthrough['import_data']['missing']['ubermenu']))); ?></li>
					<?php endif; ?>
				</ul>
				
				<p class="main-instruction"><?php printf(wp_kses(__("Please click button <strong>Import Now</strong> to start importing theme data.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html), esc_attr(themes_url())); ?></p>
				<p>
					<a href="<?php echo esc_attr(this_url('action=import_data')); ?>" class="button button-primary"><?php esc_html_e("Import Now", 'wpdanceclaratheme'); ?></a>
					&nbsp;
					<a href="<?php echo esc_attr(this_url('skip=import_data')); ?>" class="button button-secondary"><?php esc_html_e("Skip this step", 'wpdanceclaratheme'); ?></a>
				</p>
			</div>			

			<?php endif; /* import_data missing */ ?>

		</div>
		<?php
			# Hide the rest section if this step is not complete & is not skipped 
			if (!$walkthrough['import_data']['complete'] && !$walkthrough['import_data']['skip'])
				$hide_the_rest = true; 
		endif; /* hide_the_rest */
		?>



		<?php
		# ---------------------------------------------------------------------
		# CHECK SAMPLE SLIDER EXIST
		# ---------------------------------------------------------------------

		if (!$hide_the_rest):
		?>
		<div class="card import-sliders <?php
				echo $walkthrough['import_sliders']['complete'] ? ' complete' : '';
				echo $walkthrough['import_sliders']['skip'] ? ' skip' : '';
				echo $done ? ' hide-done' : '';
			?>">

			<span class="dashicons dashicons-<?php echo $walkthrough['import_sliders']['complete'] ? 'yes' : 'no'; ?> main-icon"></span>

			<?php
			# Is Slider Revolution plugin active?
			if (!\wpdanceclaratheme\Setup\is_revslider_active()):
			?>

			<h3><?php esc_html_e("Slider Revolution plugin is not activated", 'wpdanceclaratheme'); ?></h3>

			<div class="inside <?php
					# Hide inside content if user clicked Skip button
					if ($walkthrough['import_sliders']['skip']) echo ' hide';
				?>">
				<p><?php echo wp_kses(__("<strong>Slider Revolution</strong> plugin must be installed and activated before checking sliders come with the theme.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>
				<p class="main-instruction"><?php printf(wp_kses(__("Please go to <strong><a href=\"%s\">Install Plugins</a></strong> page to start install and activate the missing plugins.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html), esc_attr(install_plugins_url())); ?></p>
				<p>
					<a href="<?php echo esc_attr(install_plugins_url()); ?>" class="button button-primary"><?php esc_html_e("Go to Install Plugins", 'wpdanceclaratheme'); ?></a>
					&nbsp;
					<a href="<?php echo esc_attr(this_url('skip=import_sliders')); ?>" class="button button-secondary"><?php esc_html_e("Skip this step", 'wpdanceclaratheme'); ?></a>
				</p>
			</div>

			<?php
			# All sliders come with the theme already imported:
			elseif (empty($walkthrough['import_sliders']['missing'])):
			?>

			<h3><?php esc_html_e("All sample Revolution sliders are already imported", 'wpdanceclaratheme'); ?></h3>

			<?php
			# There are missing sliders not imported yet
			else:
			?>

			<h3><?php esc_html_e("Import sample Revolution sliders come with the theme", 'wpdanceclaratheme'); ?></h3>
			<div class="inside <?php
					# Hide inside content if user clicked Skip button
					if ($walkthrough['import_sliders']['skip']) echo ' hide';
				?>">
				<p><?php echo sprintf(wp_kses(__("Some sample sliders are missing: <code>%s</code>.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html), implode(', ', $walkthrough['import_sliders']['missing'])); ?></p>
				<p class="main-instruction"><?php printf(wp_kses(__("Please go to <strong><a href=\"%s\">Slider Revolution</a></strong>, choose <strong>Import Slider</strong> to start importing sample sliders come with the theme.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html), esc_attr(revslider_url())); ?></p>
				<p><?php _e("Each sample slider is a zip file in the directory <code>sample-data/revslider/</code> inside the zip file <strong>\"All files and documentation\"</strong>, you should have downloaded it when you purchased the theme.", 'wpdanceclaratheme'); ?></p>
				<p>
					<a href="<?php echo esc_attr(revslider_url()); ?>" class="button button-primary"><?php esc_html_e("Go to Slider Revolution", 'wpdanceclaratheme'); ?></a>
					&nbsp;
					<a href="<?php echo esc_attr(this_url('skip=import_sliders')); ?>" class="button button-secondary"><?php esc_html_e("Skip this step", 'wpdanceclaratheme'); ?></a>
				</p>
			</div>

			<?php endif; ?>
		</div>
		<?php
			# Hide the rest section if this step is not complete & is not skipped 
			if (!$walkthrough['import_sliders']['complete'] && !$walkthrough['import_sliders']['skip'])
				$hide_the_rest = true; 
		endif; /* hide_the_rest */
		?>



		<?php
		# ---------------------------------------------------------------------
		# CHECK SAMPLE DATA IMPORTED
		# ---------------------------------------------------------------------

		if (!$hide_the_rest):
		?>
		<div class="card import-samples <?php
				echo $walkthrough['import_samples']['complete'] ? ' complete' : '';
				echo $walkthrough['import_samples']['skip'] ? ' skip' : '';
				echo $done ? ' hide-done' : '';
			?>">

			<span class="dashicons dashicons-<?php echo $walkthrough['import_samples']['complete'] ? 'yes' : 'warning'; ?> main-icon"></span>

			<?php
			# User clicked Confirm button to acknowledge how to import sample data
			if ($walkthrough['import_samples']['complete']):
			?>
			
			<h3><?php esc_html_e("You have completed learning about importing sample data", 'wpdanceclaratheme'); ?></h3>
			
			<?php
			# Show instruction for importing sample data
			else:
			?>

			<h3><?php esc_html_e("Instruction for importing sample data", 'wpdanceclaratheme'); ?></h3>

			<?php endif; ?>

			<div class="inside <?php
					# Hide inside content if user clicked Skip button or clicked Confirm button to get this step completed
					if ($walkthrough['import_samples']['skip'] || $walkthrough['import_samples']['complete']) echo ' hide';
				?>">
				<p><?php _e("Sample data are provided inside the directory <code>sample-data/</code> of the zip file \"All files and documentation\" which you should have downloaded when purchased the theme. Sample data include:", 'wpdanceclaratheme'); ?></p>
				<ul>
					<li><?php echo wp_kses(__("<code>pages.xml</code> for sample pages include various homepages.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></li>
					<li><?php echo wp_kses(__("<code>contact-forms.xml</code> for sample contact form.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></li>
					<li><?php echo wp_kses(__("<code>testimonials.xml</code> for sample testimonial posts.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></li>
					<li><?php echo wp_kses(__("<code>portfolios.xml</code> for sample portfolio posts.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></li>
					<li><?php echo wp_kses(__("<code>products.xml</code> for sample WooCommerce products.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></li>
					

				</ul>
				<p class="main-instruction"><?php echo wp_kses(__("To import the sample data, go to <strong>Tools</strong> &gt; <strong>Import</strong> &gt; and choose <strong>WordPress</strong> &gt; select the XML file which you want to import.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>
				<p>
					<a href="<?php echo esc_attr(import_url()); ?>" class="button button-primary"><?php esc_html_e("Go to WordPress Import Tool", 'wpdanceclaratheme'); ?></a>
					&nbsp;
					<a href="<?php echo esc_attr(this_url('confirm=import_samples')); ?>" class="button button-primary"><?php esc_html_e("Confirm you got it", 'wpdanceclaratheme'); ?></a>
					&nbsp;
					<a href="<?php echo esc_attr(this_url('skip=import_samples')); ?>" class="button button-secondary"><?php esc_html_e("Skip this step", 'wpdanceclaratheme'); ?></a>
				</p>
			</div>
		</div>
		<?php
			# Hide the rest section if this step is not complete & is not skipped 
			if (!$walkthrough['import_samples']['complete'] && !$walkthrough['import_samples']['skip'])
				$hide_the_rest = true; 
		endif; /* hide_the_rest */
		?>



		<?php
		# ---------------------------------------------------------------------
		# CHECK HEADER & FOOTER HTML BLOCK IS SET IN CUSTOMIZER
		# ---------------------------------------------------------------------

		if (!$hide_the_rest):
		?>
		<div class="card config-header-footer <?php
				echo $walkthrough['config_header_footer']['complete'] ? ' complete' : '';
				echo $walkthrough['config_header_footer']['skip'] ? ' skip' : '';
				echo $done ? ' hide-done' : '';
			?>">

			<span class="dashicons dashicons-<?php echo $walkthrough['config_header_footer']['complete'] ? 'yes' : 'no'; ?> main-icon"></span>

			<?php
			# Already specified header and footer html block in Customizer
			if ($walkthrough['config_header_footer']['complete']): 
			?>

			<h3><?php esc_html_e("Custom Header & Footer are already configured in Customizer", 'wpdanceclaratheme'); ?></h3>

			<?php
			# Show how to configure header & footer in Customizer
			else:
			?>

			<h3><?php esc_html_e("Configure a default Header & Footer style in Customizer", 'wpdanceclaratheme'); ?></h3>

			<?php endif; ?>

			<div class="inside <?php
					# Hide inside content if user clicked Skip button or this step is already completed
					if ($walkthrough['config_header_footer']['skip'] || $walkthrough['config_header_footer']['complete']) echo ' hide';
				?>">
				<p><?php esc_html_e("Below instruction will teach you how to set a header and footer for your site, pick from our headers and footers' styles packed with the theme.", 'wpdanceclaratheme'); ?></p>

				<?php 
				# Show if header is not set OR step is already completed
				if (!$walkthrough['config_header_footer']['header'] || $walkthrough['config_header_footer']['complete']):
				?>
				<p class="main-instruction"><?php echo wp_kses(__("Go to <strong>Appearance</strong> &gt; <strong>Customize</strong> &gt; <strong>Theme - Header</strong> &gt; select a header in option <strong>Header HTML Block</strong>. Click <strong>Save</strong> button to save.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>
				<?php endif; ?>

				<?php
				# Show if footer is not set OR step is already completed
				if (!$walkthrough['config_header_footer']['footer'] || $walkthrough['config_header_footer']['complete']):
				?>
				<p class="main-instruction"><?php echo wp_kses(__("Go to <strong>Appearance</strong> &gt; <strong>Customize</strong> &gt; <strong>Theme - Footer</strong> &gt; select a footer in option <strong>Footer HTML Block</strong>. Click <strong>Save</strong> button to save.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>
				<?php endif; ?>

				<p>
					<a href="<?php echo esc_attr(customizer_url()); ?>" class="button button-primary"><?php esc_html_e("Go to Customizer", 'wpdanceclaratheme'); ?></a>
					&nbsp;
					<a href="<?php echo esc_attr(this_url('skip=config_header_footer')); ?>" class="button button-secondary"><?php esc_html_e("Skip this step", 'wpdanceclaratheme'); ?></a>
				</p>
			</div>
		</div>
		<?php
			# Hide the rest section if this step is not complete & is not skipped 
			if (!$walkthrough['config_header_footer']['complete'] && !$walkthrough['config_header_footer']['skip'])
				$hide_the_rest = true; 
		endif; /* hide_the_rest */
		?>



		<?php
		# ---------------------------------------------------------------------
		# CHECK IF FRONT PAGE IS A STATIC PAGE
		# ---------------------------------------------------------------------

		if (!$hide_the_rest):
		?>
		<div class="card config-home <?php
				echo $walkthrough['config_home']['complete'] ? ' complete' : '';
				echo $walkthrough['config_home']['skip'] ? ' skip' : '';
				echo $done ? ' hide-done' : '';
			?>">

			<span class="dashicons dashicons-<?php echo $walkthrough['config_home']['complete'] ? 'yes' : 'no'; ?> main-icon"></span>

			<?php
			# User already set a static page as homepage
			if ($walkthrough['config_home']['complete']):
			?>

			<h3><?php esc_html_e("The homepage is a static page already", 'wpdanceclaratheme'); ?></h3>

			<?php
			# Show intruction how to set a static page as homepage
			else:
			?>

			<h3><?php esc_html_e("Set a static page as homepage", 'wpdanceclaratheme'); ?></h3>

			<?php endif; ?>

			<div class="inside <?php
					# Hide inside content if user clicked Skip button or this step complete
					if ($walkthrough['config_home']['skip'] || $walkthrough['config_home']['complete']) echo ' hide';
				?>">
				<p><?php esc_html_e("Below instruction will teach you configure a static page as homepage:", 'wpdanceclaratheme'); ?></p>

				<p class="main-instruction"><?php echo wp_kses(__("Go to <strong>Appearance</strong> &gt; <strong>Customize</strong> &gt; <strong>Static Front Page</strong> &gt; choose <strong>Front page displays</strong> option is <strong>A static page</strong>. Then select a page in the dropdown option <strong>Front page</strong>. Click <strong>Save</strong> button to save.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>

				<p><img src="<?php echo esc_attr(get_template_directory_uri().__('/images/walkthrough/set-homepage.jpg', 'wpdanceclaratheme')); ?>" alt="<?php echo esc_attr("Set a static page as homepage", 'wpdanceclaratheme'); ?>" /></p>

				<p>
					<a href="<?php echo esc_attr(customizer_url()); ?>" class="button button-primary"><?php esc_html_e("Go to Customizer", 'wpdanceclaratheme'); ?></a>
					&nbsp;
					<a href="<?php echo esc_attr(this_url('skip=config_home')); ?>" class="button button-secondary"><?php esc_html_e("Skip this step", 'wpdanceclaratheme'); ?></a>
				</p>
			</div>
		</div>
		<?php
			# Hide the rest section if this step is not complete & is not skipped 
			if (!$walkthrough['config_home']['complete'] && !$walkthrough['config_home']['skip'])
				$hide_the_rest = true; 
		endif; /* hide_the_rest */
		?>



		<?php
		# ---------------------------------------------------------------------
		# LEARN ABOUT CUSTOMIZER
		# ---------------------------------------------------------------------

		if (!$hide_the_rest):
		?>
		<div class="card learn-customizer <?php
				echo $walkthrough['learn_customizer']['complete'] ? ' complete' : '';
				echo $walkthrough['learn_customizer']['skip'] ? ' skip' : '';
				echo $done ? ' hide-done' : '';
			?>">

			<span class="dashicons dashicons-<?php echo $walkthrough['learn_customizer']['complete'] ? 'yes' : 'warning'; ?> main-icon"></span>

			<?php
			# User click Confirm button to acknowledge how to customize the theme via Customizer
			if ($walkthrough['learn_customizer']['complete']):
			?>

			<h3><?php esc_html_e("You have completed learning about theme settings in Customizer", 'wpdanceclaratheme'); ?></h3>

			<?php
			# Show instruction about configuring the theme via Customizer
			else:
			?>

			<h3><?php esc_html_e("Instruction about configuring theme in Customizer", 'wpdanceclaratheme'); ?></h3>

			<?php endif; ?>

			<div class="inside <?php
					# Hide inside content if user clicked Skip button or clicked Confirm button to complete this step
					if ($walkthrough['learn_customizer']['skip'] || $walkthrough['learn_customizer']['complete']) echo ' hide';
				?>">
				<p><?php echo wp_kses(__("The theme provides many options to utilize. Almost theme options can be found in the native <strong>Customizer</strong> panels.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>

				<p><img src="<?php echo esc_attr(get_template_directory_uri().__('/images/walkthrough/customize-typo.jpg', 'wpdanceclaratheme')); ?>" alt="<?php echo esc_attr("Customize typography and colors", 'wpdanceclaratheme'); ?>" /></p>

				<p><?php echo wp_kses(__("Section <strong>Theme - Typography &amp; Color</strong>: Let you change typography, fonts, colors of each elements of your site. The theme also supplies a few presets to quickly change typography &amp; colors of all elements.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>

				<p><?php echo wp_kses(__("Section <strong>Theme - Header</strong>: Let you pick a site logo, choose a default page header from dozens pre-made headers come with the theme.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>

				<p><?php echo wp_kses(__("Section <strong>Theme - Footer</strong>: Let you choose a default page footer from dozens pre-made footers.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>

				<p><?php echo wp_kses(__("Section <strong>Theme - Layout</strong>: Let you change page layout of default page as well as specific pages. Decide the main sidebar showing on left or right column, for just showing full width. Assign different sidebar for each page, etc...", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>

				<p><?php echo wp_kses(__("Section <strong>Theme - Advance</strong>: Let you Import/Export theme settings as well as reset all theme settings to factory default. ", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>

				<p>
					<a href="<?php echo esc_attr(this_url('confirm=learn_customizer')); ?>" class="button button-primary"><?php esc_html_e("Confirm you got it", 'wpdanceclaratheme'); ?></a>
					&nbsp;
					<a href="<?php echo esc_attr(this_url('skip=learn_customizer')); ?>" class="button button-secondary"><?php esc_html_e("Skip this step", 'wpdanceclaratheme'); ?></a>
				</p>
			</div>	
		</div>
		<?php
			# Hide the rest section if this step is not complete & is not skipped 
			if (!$walkthrough['learn_customizer']['complete'] && !$walkthrough['learn_customizer']['skip'])
				$hide_the_rest = true; 
		endif; /* hide_the_rest */ 
		?>



		<?php
		# ---------------------------------------------------------------------
		# LEARN ABOUT SINGLE POST / TAX CONFIG
		# ---------------------------------------------------------------------

		if (!$hide_the_rest):
		?>
		<div class="card learn-single-config <?php
				echo $walkthrough['learn_single_config']['complete'] ? ' complete' : '';
				echo $walkthrough['learn_single_config']['skip'] ? ' skip' : '';
				echo $done ? ' hide-done' : '';
			?>">

			<span class="dashicons dashicons-<?php echo $walkthrough['learn_single_config']['complete'] ? 'yes' : 'warning'; ?> main-icon"></span>

			<?php
			# User click Confirm button to acknowledge about extra options for posts / tax
			if ($walkthrough['learn_single_config']['complete']):
			?>

			<h3><?php echo wp_kses(__("Completed learning about extra options for <em>post, page, category, tag, taxonomy, term</em>", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></h3>

			<?php
			# Show instruction about extra options for posts / tax
			else:
			?>

			<h3><?php echo wp_kses(__("Theme adds extra options for <em>post, page, category, tag, taxonomy, term</em> you should know", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></h3>

			<?php endif; ?>

			<div class="inside <?php
					# Hide inside content if user clicked Skip button or clicked Confirm button to complete this step
					if ($walkthrough['learn_single_config']['skip'] || $walkthrough['learn_single_config']['complete']) echo ' hide';
				?>">
				<p><?php echo wp_kses(__("Theme add new extra options for customizing <strong>layout, header, footer</strong>... of a specific post, page, category, tag, taxonomy, term, product.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>

				<figure>
					<img src="<?php echo esc_attr(get_template_directory_uri().__('/images/walkthrough/extra-options-for-post.png', 'wpdanceclaratheme')); ?>" alt="<?php echo $s = esc_attr("Extra options for single post, page, product, portfolio...", 'wpdanceclaratheme'); ?>" />
					<figcaption><?php echo $s; ?></figcaption>
				</figure>

				<figure>
					<img src="<?php echo esc_attr(get_template_directory_uri().__('/images/walkthrough/extra-options-for-category.png', 'wpdanceclaratheme')); ?>" alt="<?php echo $s = esc_attr("Extra options for single category, tag, product category, taxonomy, term pages...", 'wpdanceclaratheme'); ?>" />
					<figcaption><?php echo $s; ?></figcaption>
				</figure>

				<p>
					<a href="<?php echo esc_attr(this_url('confirm=learn_single_config')); ?>" class="button button-primary"><?php esc_html_e("Confirm you got it", 'wpdanceclaratheme'); ?></a>
					&nbsp;
					<a href="<?php echo esc_attr(this_url('skip=learn_single_config')); ?>" class="button button-secondary"><?php esc_html_e("Skip this step", 'wpdanceclaratheme'); ?></a>
				</p>
			</div>	
		</div>
		<?php
			# Hide the rest section if this step is not complete & is not skipped 
			if (!$walkthrough['learn_single_config']['complete'] && !$walkthrough['learn_single_config']['skip'])
				$hide_the_rest = true; 
		endif; /* hide_the_rest */ 
		?>



		<?php
		# ---------------------------------------------------------------------
		# SHOW AFTER 'GET STARTED' STEP COMPLETED
		# ---------------------------------------------------------------------

		if ($walkthrough['get_started']['complete']):
		?>

		<p><a class="button button-secondary" href="<?php echo esc_attr(this_url('restart=1')); ?>"><?php esc_html_e("Start over this tutorial", 'wpdanceclaratheme'); ?></a></p>
		<hr />

		<!--
			READ THE COMPLETE USER GUIDE
		-->
		<div class="card read-userguide">
			<span class="dashicons dashicons-book main-icon"></span>
			<h3><?php esc_html_e("Read the complete user guide", 'wpdanceclaratheme'); ?><a href="<?php echo esc_attr(userguide_url()); ?>" target="_blank" class="button button-primary alignright"><?php esc_html_e("Read user guide", 'wpdanceclaratheme'); ?></a></h3>
		</div>

		<!--
			CONTACT SUPPORT
		-->
		<div class="card support">
			<span class="dashicons dashicons-sos main-icon"></span>
			<h3><?php esc_html_e("Got trouble? Contact us for support here", 'wpdanceclaratheme'); ?><a href="<?php echo esc_attr(support_url()); ?>" target="_blank" class="button button-primary alignright"><?php esc_html_e("Submit a ticket", 'wpdanceclaratheme'); ?></a></h3>
			<div class="inside">
				<p><?php echo wp_kses(__("Please make sure you add our email <strong>support@wpdance.com</strong> to whitelist so that our response email does not drop into your julk mailbox", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>

				<p><?php echo wp_kses(__("If the ticket system somehow doesn't work, please contact us via direct email <a href=\"mailto:tvlgiao@gmail.com?subject=[wpdanceclaratheme-support]\">tvlgiao@gmail.com</a>.", 'wpdanceclaratheme'), \wpdanceclaratheme\Config::$allowed_html); ?></p>
			</div>
		</div>

		<!-- 
			RATE FOR US
		-->
		<div class="card read-userguide">
			<span class="dashicons dashicons-thumbs-up main-icon"></span>
			<h3><?php esc_html_e("Please review & rate our theme", 'wpdanceclaratheme'); ?><a href="<?php echo esc_attr(rating_url()); ?>" target="_blank" class="button button-primary alignright"><?php esc_html_e("Rate for us", 'wpdanceclaratheme'); ?></a></h3>
			<p><?php esc_html_e("Thank you for choosing our theme. Please you like this theme please rate for us.", 'wpdanceclaratheme'); ?></p>
			<p class="rating">
				<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>
			</p>
			<p><?php esc_html_e("Your feedback will help us improve product better!", 'wpdanceclaratheme'); ?></p>
		</div>

		<?php endif; /* get_started complete */ ?>

	</div>
	<?php
}

