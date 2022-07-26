<?php
/*
 * Plugin Name: Fine Dashboard - Client
 * Description: Edit the Dashboard to show custom data.
 * Tags: dashboard, notice, admin, logged in, alert
 * Version: 1.0
 * Author: Ketchlabs
 * Author URI: https://lukeketchen.com/
 * Text Domain: fine-dashboard-client
 * License GPL2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
     die;
}

define( 'FINE_DASH_PLUGIN_NAME',               'Fine Dashboard');
define( 'FINE_DASH_FD_FILE',                  __FILE__ );
define( 'FINE_DASH_PLUGIN_FOLDER',             plugin_dir_path( __FILE__ ));

class FineDashboard{

	private $fine_dashboard_screen_name;
	private static $instance;

	static function GetInstance()
	{

		if (!isset(self::$instance))
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function InitPlugin()
	{
		# Set cookie for force show acf option
		if(isset($_GET['force_show_FDASH'])){
			setcookie('force_show_FDASH','true',time()+600,'/');
		}

		add_action('admin_menu', array($this, 'PluginMenu'));
		add_action( 'admin_init', array($this, 'fdb_api_settings_init'));
		add_action('wp_dashboard_setup', array($this, 'load_custom_dashboard_style'), 9999 );
		add_action('wp_dashboard_setup', array($this, 'fine_dashboard_remove_all_dashboard_meta_boxes'), 9999 );
		add_action( 'admin_enqueue_scripts', array($this, 'load_custom_wp_admin_style') );
	}

	public function PluginMenu()
	{
		$whitelist = array(
			'127.0.0.1',
			'::1',
		);

		# if ip, cookie or get var show menu
		if ( in_array( $_SERVER['REMOTE_ADDR'], $whitelist ) || isset($_COOKIE['force_show_FDASH']) || isset($_GET['force_show_FDASH']) ) {
			add_options_page(
				FINE_DASH_PLUGIN_NAME,
				FINE_DASH_PLUGIN_NAME,
				'manage_options',
				'fine-dashboard-client-page',
				array($this, 'fdb_options_page'),
			);
		}
	}

	function fdb_options_page(  ) {
    ?>
		<form action='options.php' method='post'>

			<h2>Fine Dashboard - Client</h2>
			<p>Set the api endpoint to get the data for the widgets. </p>

			<?php
			settings_fields( 'fdbPlugin' );
			do_settings_sections( 'fdbPlugin' );
			submit_button();
			?>

		</form>
		<?php
	}


	function fdb_api_settings_init(  ) {
		register_setting( 'fdbPlugin', 'fdb_api_settings' );
		add_settings_section(
			'fdb_api_fdbPlugin_section',
			__( '', 'wordpress' ),
			array($this, 'fdb_api_settings_section_callback'),
			'fdbPlugin'
		);

		add_settings_field(
			'fdb_source_widget',
			__( 'Source address', 'wordpress' ),
			array($this, 'fdb_source_widget_render'),
			'fdbPlugin',
			'fdb_api_fdbPlugin_section'
		);

		add_settings_field(
			'fdb_alert_widget_id',
			__( 'Alert Widget id', 'wordpress' ),
			array($this, 'fdb_alert_widget_id_render'),
			'fdbPlugin',
			'fdb_api_fdbPlugin_section'
		);

		add_settings_field(
			'fdb_general_widget_id',
			__( 'General Widget id', 'wordpress' ),
			array($this, 'fdb_general_widget_id_render'),
			'fdbPlugin',
			'fdb_api_fdbPlugin_section'
		);

		add_settings_field(
			'fdb_office_widget_id',
			__( 'Office Widget id', 'wordpress' ),
			array($this, 'fdb_office_widget_id_render'),
			'fdbPlugin',
			'fdb_api_fdbPlugin_section'
		);

		add_settings_field(
			'fdb_helpful_widget_id',
			__( 'Helpful Widget id', 'wordpress' ),
			array($this, 'fdb_helpful_widget_id_render'),
			'fdbPlugin',
			'fdb_api_fdbPlugin_section'
		);

		// to add the ability to add more widgets
		// add_settings_field(
		// 	'fdb_add_widget_id',
		// 	__( 'Add More Widgets', 'wordpress' ),
		// 	array($this, 'fdb_add_widget_id_render'),
		// 	'fdbPlugin',
		// 	'fdb_api_fdbPlugin_section'
		// );

	}

	function fdb_source_widget_render(  ) {
		$options = get_option( 'fdb_api_settings' );
		?>
		<input type='text' name='fdb_api_settings[fdb_source_widget]' value='<?= !empty($options['fdb_source_widget']) ? $options['fdb_source_widget'] : ''; ?>'>
		<?php
	}

	function fdb_alert_widget_id_render(  ) {
		$options = get_option( 'fdb_api_settings' );
		?>
		<input type='number' name='fdb_api_settings[fdb_alert_widget_id]' value='<?= !empty($options['fdb_alert_widget_id']) ? $options['fdb_alert_widget_id'] : ''; ?>'>
		<?php
	}

	function fdb_general_widget_id_render(  ) {
		$options = get_option( 'fdb_api_settings' );
		?>
		<input type='number' name='fdb_api_settings[fdb_general_widget_id]' value='<?= !empty($options['fdb_general_widget_id']) ? $options['fdb_general_widget_id'] : ''; ?>'>
		<!-- to delete the widget -->
		<!-- <input type="checkbox" name="delete_fdb_general_widget_id" id="delete_fdb_general_widget_id">
		<label for="delete_fdb_general_widget_id">Delete Widget</label> -->
		<?php
	}

	function fdb_office_widget_id_render(  ) {
		$options = get_option( 'fdb_api_settings' );
		?>
		<input type='number' name='fdb_api_settings[fdb_office_widget_id]' value='<?= !empty($options['fdb_office_widget_id']) ? $options['fdb_office_widget_id'] : ''; ?>'>
		<?php
	}

	function fdb_helpful_widget_id_render(  ) {
		$options = get_option( 'fdb_api_settings' );
		?>
		<input type='number' name='fdb_api_settings[fdb_helpful_widget_id]' value='<?= !empty($options['fdb_helpful_widget_id']) ? $options['fdb_helpful_widget_id'] : ''; ?>'>
		<?php
	}

	function fdb_add_widget_id_render(  ) {
		$options = get_option( 'fdb_api_settings' );
		?>
		<input type='text' name='fdb_api_settings[fdb_add_widget_id]' value='<?= !empty($options['fdb_add_widget_id']) ? $options['fdb_add_widget_id'] : ''; ?>'>
		<?php
	}

	function fdb_api_settings_section_callback(  ) {

	}

	/*
		load custom dashboard styles
	*/
	public function load_custom_dashboard_style( )
	{
    	wp_enqueue_style( 'fine_dashboard_css' , plugin_dir_url( FINE_DASH_FD_FILE ).'assets/css/fine-style.css', array(), null);
	}

	/*
		load custom admin page styles
	*/
	function load_custom_wp_admin_style($hook)
	{
		// Load only on ?page=fine-dashboard
		if( $hook != 'tools_page_fine-dashboard/fine-dashboard' ) {
			return;
		}
		wp_enqueue_style( 'fine_admin_css', plugin_dir_url( FINE_DASH_FD_FILE ).'assets/css/admin-style.css', array(), null );
	}

	/*
		clean dashboard
	*/
	public function fine_dashboard_remove_all_dashboard_meta_boxes()
	{
		// remove all metaboxes from dashboard
		global $wp_meta_boxes;
		$wp_meta_boxes['dashboard']['normal']['core'] = array();
		$wp_meta_boxes['dashboard']['side']['core'] = array();

		$this->add_new_widgets();
	}

	/*
		Add New widgets
	*/
	private function add_new_widgets()
	{
		$alert_help_widget = new Widget('Alert', 'fdb_alert_widget_id');
		$custom_help_widget = new Widget('Get help to manage your web site', 'fdb_general_widget_id');
		$office_details_widget = new Widget('Office Details', 'fdb_office_widget_id');
		$helpful_links_widget = new Widget('Helpful Links', 'fdb_helpful_widget_id');
	}
}

$FineDashboard = FineDashboard::GetInstance();
$FineDashboard->InitPlugin();


/*
	set up widget
*/
class Widget {

	private $title;
	private $widget_id_key;

	/*
	 	contructor for widget
	*/
	public function __construct($title, $widget_id_key)
	{
		$this->title = $title;
		$this->widget_id_key = $widget_id_key;
		wp_add_dashboard_widget($widget_id_key, $title, array($this, 'fdb_custom_dashboard_widget') );
	}

	/*
		get and include widget file
	*/
	function fdb_custom_dashboard_widget()
	{
		$widget_id_key = $this->widget_id_key;

		if(!empty(get_option('fdb_api_settings')[$widget_id_key])):
			$options = get_option( 'fdb_api_settings' );
			$widget_id = $options[$widget_id_key];
			$source_address = $options['fdb_source_widget'];
			if(!empty($widget_id) && !empty($source_address)):
				$connected_url = $source_address.'/wp-json/wp/v2/fdcpt_widget/'.$widget_id;
			endif;
			$response = wp_remote_get($connected_url);
			$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
		endif;

		/*
			hide if not set to show
		*/
		if(empty($api_response['show_widget'])):
			echo
			'
			<style>
				#'.$widget_id_key.'{
					display: none;
				}
			</style>
			';
		endif;

		if(!empty($api_response)) :
			echo $api_response['content']['rendered'];
		endif;
	}
}
