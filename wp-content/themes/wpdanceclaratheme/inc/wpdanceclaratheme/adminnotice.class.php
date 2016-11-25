<?php
namespace wpdanceclaratheme;

if (!is_admin()) return;

AdminNotice::register_hooks();


/**
 * Class handle notice messages on backend
 */
class AdminNotice {


	###########################################################################
	# STATIC VARIABLES
	###########################################################################

	private static $instance = null;



	###########################################################################
	# OBJECT VARIABLES
	###########################################################################
	
	/**
	 * Array contains all messages
	 */
	public $messages = array(
		'update-nag' => array(),
		'updated'    => array(),
		'error'      => array(),
	);

	


	###########################################################################
	# STATIC METHODS
	###########################################################################

	public static function instance() {
		if (!isset(static::$instance))
			static::$instance = new static;
		return static::$instance;
	}

	public static function register_hooks() {
		add_action('admin_init', __NAMESPACE__.'\\AdminNotice::admin_init');
		add_action('admin_notices', __NAMESPACE__.'\\AdminNotice::admin_notices');
		add_action('wp_ajax_wpdanceclaratheme_adminnotice_dismiss', __NAMESPACE__.'\\AdminNotice::action_dismiss');
	}

	public static function admin_init() {
		$instance = self::instance();
		foreach ($instance->messages as $msgs) {
			if (!empty($msgs)) {
				wp_enqueue_style('wpdanceclaratheme-adminnotice-style', get_template_directory_uri().'/css/adminnotice.css', array(), \wpdanceclaratheme\Config::VERSION);
				break;
			}
		}
	}

	/**
	 * Callback function for hook 'admin_notices'
	 */
	public static function admin_notices() {
		self::instance()->print_notices();
	}

	/**
	 * Show warning message
	 */
	public static function nag($msg, $show_once = true, $dismissable = true, $exclude_pages = '', $msg_id = '') {
		self::instance()->notice($msg, 'update-nag', $show_once, $dismissable, $exclude_pages, $msg_id);
	}

	/**
	 * Show success message
	 */
	public static function updated($msg, $show_once = true, $dismissable = true, $exclude_pages = '', $msg_id = '') {
		self::instance()->notice($msg, 'updated', $show_once, $dismissable, $exclude_pages, $msg_id);
	}


	/**
	 * Show error message
	 */
	public static function error($msg, $show_once = true, $dismissable = true, $exclude_pages = '', $msg_id = '') {
		self::instance()->notice($msg, 'error', $show_once, $dismissable, $exclude_pages, $msg_id);
	}


	public static function action_dismiss() {
		if (!isset($_REQUEST['id']) || empty($_REQUEST['id']) || !isset($_REQUEST['nonce']) || empty($_REQUEST['nonce']) || !wp_verify_nonce($_GET['nonce'], 'wpdanceclaratheme_adminnotice_dismiss'))
			die('-1');

		self::instance()->dismiss($_REQUEST['id']);
	}


	###########################################################################
	# OBJECT METHODS
	###########################################################################


	protected function __construct() {

		$this->messages = get_theme_mod('wpdanceclaratheme_adminnotice_messages');

		if (empty($this->messages)) {
			$this->messages =  array(
				'update-nag' => array(),
				'updated'    => array(),
				'error'      => array(),
			);
		}
	}


	/**
	 * Show notice messages
	 */
	public function notice($msg, $type = 'updated', $show_once = true, $dismissable = true, $exclude_pages = '', $msg_id = '') {

		$this->messages[$type][] = array(
			'message'       => $msg,
			'show_once'     => $show_once,
			'dismissable'   => $dismissable,
			'msg_id'        => $msg_id ? $msg_id : ($dismissable ? uniqid() : ''),
			'exclude_pages' => (array)$exclude_pages
		);

		set_theme_mod('wpdanceclaratheme_adminnotice_messages', $this->messages);
	}

	/**
	 * Print out all messages
	 */
	public function print_notices() {
		$changed = false;

		$nonce = '';

		$dismiss = false;
		
		foreach ($this->messages as $type => &$msgs) {
			foreach ($msgs as $i => &$msg) {
				if (isset($_REQUEST['page']) && !empty($_REQUEST['page']) && in_array($_REQUEST['page'], $msg['exclude_pages']))
					continue;

				?>
				<div class="wpdanceclaratheme-adminnotice <?php echo esc_attr($type); ?>">
					<?php
						if ($msg['dismissable']) {
							if (!$nonce) $nonce = wp_create_nonce('wpdanceclaratheme_adminnotice_dismiss');
							printf('<a href="%s" class="wpdanceclaratheme-adminnotice-dismiss"><span class="dashicons dashicons-dismiss"></span></a>', admin_url("admin-ajax.php?action=wpdanceclaratheme_adminnotice_dismiss&id={$msg['msg_id']}&nonce=$nonce"));
							$dismiss = true;
						}
					?>
					<p><?php echo $msg['message'] ?></p>
				</div>
				<?php

				if ($msg['show_once']) {
					unset($msgs[$i]);
					$changed = true;
				}
			}
			$msgs = array_values($msgs);
		}

		if ($dismiss):
		?>
			<script type="text/javascript">
			// <![CDATA[
			jQuery(function($) {
				$('.wpdanceclaratheme-adminnotice-dismiss').on('click', function(e) {
					e.preventDefault();
					$.get($(this).attr('href'));
					$(this).closest('.wpdanceclaratheme-adminnotice').hide();
				});

			});
			// ]]
			</script>
		<?php 
		endif;

		if ($changed)
			set_theme_mod('wpdanceclaratheme_adminnotice_messages', $this->messages);
	}


	public function dismiss($msg_id) {
		$changed = false;
		
		foreach ($this->messages as $type => &$msgs) {
			foreach ($msgs as $i => &$msg) {
				if ($msg['msg_id'] == $msg_id) {
					unset($msgs[$i]);
					$changed = true;
				}
			}
			$msgs = array_values($msgs);
		}
		if ($changed)
			set_theme_mod('wpdanceclaratheme_adminnotice_messages', $this->messages);	
	}

	public function message_exists($msg_id) {
		foreach ($this->messages as $type => $msgs)
			foreach ($msgs as $i => $msg)
				if ($msg['msg_id'] == $msg_id)
					return true;
		return false;
	}

}





