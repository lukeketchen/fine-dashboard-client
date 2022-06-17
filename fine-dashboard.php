

<?php
/**
 * Plugin Name: Fine Dashboard
 * Description: Edit the Dashboard to show custom data.
 * Author: Luke Ketchen
 * Version: 0.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
     die;
}

define( 'PLUGIN_NAME',               'Fine Dashboard');
define( 'FD_FILE',                  __FILE__ );
define( 'FD_PATH',                  realpath( plugin_dir_path( FD_FILE ) ) . '/' );
define( 'FD_INC_PATH',              realpath( FD_PATH . 'inc/' ) . '/' );
define( 'FD_FUNCTIONS_PATH',        realpath( FD_INC_PATH . 'functions' ) . '/' );

/*
	Reference - https://clivern.com/adding-menus-and-submenus-for-wordpress-plugins/
 */
class FineDashboard{

	private $my_plugin_screen_name;
	private static $instance;
	/*......*/

	static function GetInstance()
	{

		if (!isset(self::$instance))
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function PluginMenu()
	{
	$this->my_plugin_screen_name = add_menu_page(
										'Fine Dashboard',
										'Fine Dashboard',
										'manage_options',
										FD_FILE,
										array($this, 'RenderPage'),
										FD_PATH.'/assets/img/icon.png',
									);
	}

	protected function RenderPage(){
	?>
		<div class='wrap'>
			<h2>Fine Dashboard</h2>
		</div>
	<?php
	}

	public function InitPlugin()
	{
		add_action('admin_menu', array($this, 'PluginMenu'));
		add_action('wp_enqueue_scripts', 'add_custom_css_file');
		add_action('wp_dashboard_setup', 'wpse_73561_remove_all_dashboard_meta_boxes', 9999 );
	}

	protected function add_custom_css_file( $hook ) {
		wp_enqueue_style( plugins_url( 'assets/css/style.css', FD_FILE ) );
		wp_enqueue_script ( 'fine-main', plugin_dir_url( FD_FILE ).'assets/js/main.js', 'fine-main', true  );
	}

	protected function wpse_73561_remove_all_dashboard_meta_boxes(){
		// remove all metaboxes from dashboard
		global $wp_meta_boxes;
		$wp_meta_boxes['dashboard']['normal']['core'] = array();
		$wp_meta_boxes['dashboard']['side']['core'] = array();

		// add new metabox
		wp_add_dashboard_widget('custom_help_widget', 'Get help to manage your web site', 'custom_dashboard_help');
	}

	/*
	New Meta box
	*/
	protected function custom_dashboard_help() {
	?>
		<p style="color: green;font-size: 18px;"><strong>Welcome to the backend of your WordPress web site!</strong></p>
		<p>Some helpful advice is located here:</p>
		<h2>
			<a href="https://www.efront.com.au/" target="_blank" style="text-decoration: underline; font-weight:strong;">Link to the help page</a>
		</h2>
		<p>Contact <a href="mailto:paaljoachim@hotmail.com">Paal Joachim</a> when questions arise.</p>
	<?php
	}

}

$FineDashboard = FineDashboard::GetInstance();
$FineDashboard->InitPlugin();
