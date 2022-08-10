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

define( 'FINE_DASH_CLIENT_PLUGIN_NAME',               'Fine Dashboard - Client');
define( 'FINE_DASH_CLIENT_FD_FILE',                  __FILE__ );
define( 'FINE_DASH_CLIENT_PLUGIN_FOLDER',             plugin_dir_path( __FILE__ ));

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

		add_action('admin_menu', array($this, 'fdbc_PluginMenu'));
		add_action('admin_init', array($this, 'fdbc_api_settings_init'));
		add_action('wp_dashboard_setup', array($this, 'fdbc_load_custom_dashboard_style'), 9999 );
		add_action('wp_dashboard_setup', array($this, 'fdbc_remove_all_dashboard_meta_boxes'), 9999 );
		add_action('admin_enqueue_scripts', array($this, 'fdbc_load_custom_wp_admin_style') );
	}

	public function fdbc_PluginMenu()
	{
		$whitelist = array(
			'127.0.0.1',
			'::1',
		);

		# if ip, cookie or get var show menu
		if ( in_array( $_SERVER['REMOTE_ADDR'], $whitelist ) || isset($_COOKIE['force_show_FDASH']) || isset($_GET['force_show_FDASH']) ) {
			add_options_page(
				FINE_DASH_CLIENT_PLUGIN_NAME,
				__(FINE_DASH_CLIENT_PLUGIN_NAME, 'wordpress'),
				'manage_options',
				'fine-dashboard-client-page',
				array($this, 'fdbc_options_page'),
			);
		}
	}

	function fdbc_options_page(  ) {
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


	function fdbc_api_settings_init(  ) {
		register_setting( 'fdbPlugin', 'fdbc_api_settings' );
		add_settings_section(
			'fdbc_api_fdbPlugin_section',
			__( '', 'wordpress' ),
			array($this, 'fdbc_api_settings_section_callback'),
			'fdbPlugin'
		);

		add_settings_field(
			'fdbc_source_widget',
			__( 'Source address', 'wordpress' ),
			array($this, 'fdbc_source_widget_render'),
			'fdbPlugin',
			'fdbc_api_fdbPlugin_section'
		);

		add_settings_field(
			'fdbc_alert_widget_id',
			__( 'Alert Widget id', 'wordpress' ),
			array($this, 'fdbc_alert_widget_id_render'),
			'fdbPlugin',
			'fdbc_api_fdbPlugin_section'
		);

		add_settings_field(
			'fdbc_general_widget_id',
			__( 'General Widget id', 'wordpress' ),
			array($this, 'fdbc_general_widget_id_render'),
			'fdbPlugin',
			'fdbc_api_fdbPlugin_section'
		);

		add_settings_field(
			'fdbc_office_widget_id',
			__( 'Office Widget id', 'wordpress' ),
			array($this, 'fdbc_office_widget_id_render'),
			'fdbPlugin',
			'fdbc_api_fdbPlugin_section'
		);

		add_settings_field(
			'fdbc_helpful_widget_id',
			__( 'Helpful Widget id', 'wordpress' ),
			array($this, 'fdbc_helpful_widget_id_render'),
			'fdbPlugin',
			'fdbc_api_fdbPlugin_section'
		);

		// to add the ability to add more widgets
		// add_settings_field(
		// 	'fdbc_add_widget_id',
		// 	__( 'Add More Widgets', 'wordpress' ),
		// 	array($this, 'fdbc_add_widget_id_render'),
		// 	'fdbPlugin',
		// 	'fdbc_api_fdbPlugin_section'
		// );

	}

	function fdbc_source_widget_render(  ) {
		$options = get_option( 'fdbc_api_settings' );
		?>
		<input type='text' name='fdbc_api_settings[fdbc_source_widget]' value='<?php echo !empty($options['fdbc_source_widget']) ? esc_attr($options['fdbc_source_widget']) : ''; ?>'>
		<?php
	}

	function fdbc_alert_widget_id_render(  ) {
		$options = get_option( 'fdbc_api_settings' );
		?>
		<input type='number' name='fdbc_api_settings[fdbc_alert_widget_id]' value='<?php echo !empty($options['fdbc_alert_widget_id']) ? esc_attr($options['fdbc_alert_widget_id']) : ''; ?>'>
		<?php
	}

	function fdbc_general_widget_id_render(  ) {
		$options = get_option( 'fdbc_api_settings' );
		?>
		<input type='number' name='fdbc_api_settings[fdbc_general_widget_id]' value='<?php echo !empty($options['fdbc_general_widget_id']) ? esc_attr($options['fdbc_general_widget_id']) : ''; ?>'>
		<!-- to delete the widget -->
		<!-- <input type="checkbox" name="delete_fdbc_general_widget_id" id="delete_fdbc_general_widget_id">
		<label for="delete_fdbc_general_widget_id">Delete Widget</label> -->
		<?php
	}

	function fdbc_office_widget_id_render(  ) {
		$options = get_option( 'fdbc_api_settings' );
		?>
		<input type='number' name='fdbc_api_settings[fdbc_office_widget_id]' value='<?php echo !empty($options['fdbc_office_widget_id']) ? esc_attr($options['fdbc_office_widget_id']) : ''; ?>'>
		<?php
	}

	function fdbc_helpful_widget_id_render(  ) {
		$options = get_option( 'fdbc_api_settings' );
		?>
		<input type='number' name='fdbc_api_settings[fdbc_helpful_widget_id]' value='<?php echo !empty($options['fdbc_helpful_widget_id']) ? esc_attr($options['fdbc_helpful_widget_id']) : ''; ?>'>
		<?php
	}

	function fdbc_add_widget_id_render(  ) {
		$options = get_option( 'fdbc_api_settings' );
		?>
		<input type='text' name='fdbc_api_settings[fdbc_add_widget_id]' value='<?php echo !empty($options['fdbc_add_widget_id']) ? esc_attr($options['fdbc_add_widget_id']) : ''; ?>'>
		<?php
	}

	function fdbc_api_settings_section_callback(  ) {

	}

	/*
		load custom dashboard styles
	*/
	public function fdbc_load_custom_dashboard_style( )
	{
    	wp_enqueue_style( 'fine_dashboard_css' , plugin_dir_url( FINE_DASH_CLIENT_FD_FILE ).'assets/css/fine-style.css', array(), null);
	}

	/*
		load custom admin page styles
	*/
	function fdbc_load_custom_wp_admin_style($hook)
	{
		// Load only on settings_page_fine-dashboard-client-page
		if( $hook != 'settings_page_fine-dashboard-client-page' ) {
			return;
		}
		wp_enqueue_style( 'fine_admin_css', plugin_dir_url( FINE_DASH_CLIENT_FD_FILE ).'assets/css/admin-style.css', array(), null );
	}

	/*
		clean dashboard
	*/
	public function fdbc_remove_all_dashboard_meta_boxes()
	{
		// remove all metaboxes from dashboard
		global $wp_meta_boxes;
		$wp_meta_boxes['dashboard']['normal']['core'] = array();
		$wp_meta_boxes['dashboard']['side']['core'] = array();

		$this->fdbc_add_new_widgets();
	}

	/*
		Add New widgets
	*/
	private function fdbc_add_new_widgets()
	{
		$alert_help_widget = new fdbc_Widget('Alert', 'fdbc_alert_widget_id');
		$custom_help_widget = new fdbc_Widget('Get help to manage your web site', 'fdbc_general_widget_id');
		$office_details_widget = new fdbc_Widget('Office Details', 'fdbc_office_widget_id');
		$helpful_links_widget = new fdbc_Widget('Helpful Links', 'fdbc_helpful_widget_id');
	}
}

$fdbc_FineDashboard = FineDashboard::GetInstance();
$fdbc_FineDashboard->InitPlugin();


/*
	set up widget
*/
class fdbc_Widget {

	private $title;
	private $widget_id_key;

	/*
	 	contructor for widget
	*/
	public function __construct($title, $widget_id_key)
	{
		$this->title = $title;
		$this->widget_id_key = $widget_id_key;
		wp_add_dashboard_widget($widget_id_key, $title, array($this, 'fdbc_custom_dashboard_widget') );
	}

	/*
		get and include widget file
	*/
	function fdbc_custom_dashboard_widget()
	{
		$widget_id_key = $this->widget_id_key;
		$arr = wp_kses_allowed_html( 'post' );

		if(!empty(get_option('fdbc_api_settings')[$widget_id_key])):
			$options = get_option( 'fdbc_api_settings' );
			$widget_id = $options[$widget_id_key];
			$source_address = $options['fdbc_source_widget'];
			if(!empty($widget_id) && !empty($source_address)):
				$connected_url = $source_address.'/wp-json/wp/v2/fdbscpt_widget/'.$widget_id;
			endif;
			$response = wp_remote_get($connected_url);
			$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
		endif;

		/*
			hide if not set to show
		*/
		if(empty($api_response['show_widget'][0])):
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
			echo wp_kses($api_response['content']['rendered'], $arr);
		endif;
	}
}
