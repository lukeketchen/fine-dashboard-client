

<?php
/**
 * Plugin Name: Fine Dashboard - Client
 * Description: Edit the Dashboard to show custom data.
 * Author: Luke Ketchen
 * Version: 0.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
     die;
}

define( 'FINE_DASH_PLUGIN_NAME',               'Fine Dashboard');
define( 'FINE_DASH_FD_FILE',                  __FILE__ );
define( 'FINE_DASH_PLUGIN_FOLDER',             plugin_dir_path( __FILE__ ));
define( 'FINE_DASH_PATH_TO_JSON_FILE' , FINE_DASH_PLUGIN_FOLDER.'/data');

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
			// $this->fine_dashboard_screen_name =
			// 	add_submenu_page(
			// 		'tools.php',
			// 		FINE_DASH_PLUGIN_NAME,
			// 		FINE_DASH_PLUGIN_NAME,
			// 		'manage_options',
			// 		'fine-dashboard-client',
			// 		array($this, 'RenderPage'),
			// );
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

	function fdb_api_settings_section_callback(  ) {

	}


	/*
		fine dashboard admin page
	*/
	public function RenderPage()
	{
		//include_once("modules/admin.php");
	}

	/*
		load custom dashboard styles
	*/
	public function load_custom_dashboard_style( )
	{
    	wp_enqueue_style( 'fine_dashboard_css' , plugin_dir_url( FINE_DASH_FD_FILE ).'assets/css/fine-style.css', array(), null);
		wp_enqueue_script ( 'fine_dashboard_js', plugin_dir_url( FINE_DASH_FD_FILE ).'assets/js/main.js', 'fine-main', true  );
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
		wp_enqueue_script( 'fine_admin_js', plugin_dir_url( FINE_DASH_FD_FILE ).'assets/js/admin.js', 'fine_admin_js', true  );
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
		$alert_data = json_decode(file_get_contents(FINE_DASH_PATH_TO_JSON_FILE."/alert.json"), true);
		$general_data = json_decode(file_get_contents(FINE_DASH_PATH_TO_JSON_FILE."/general_help_widget.json"), true);
		$office_data = json_decode(file_get_contents(FINE_DASH_PATH_TO_JSON_FILE."/office_details_widget.json"), true);
		$helpful_data = json_decode(file_get_contents(FINE_DASH_PATH_TO_JSON_FILE."/helpful_links_widget.json"), true);

		$alert_help_widget = new Widget('Alert', 'alert_widget', 'fdb_alert_widget_id', $alert_data );
		$custom_help_widget = new Widget('Get help to manage your web site', 'general_help_widget', 'fdb_general_widget_id', $general_data);
		$office_details_widget = new Widget('Office Details', 'office_details_widget', 'fdb_office_widget_id', $office_data);
		$helpful_links_widget = new Widget('Helpful Links', 'helpful_links_widget', 'fdb_helpful_widget_id', $helpful_data);
	}
}

$FineDashboard = FineDashboard::GetInstance();
$FineDashboard->InitPlugin();


/*
	use this to get the posts

	will need a host url to connect to

	/wp-json/wp/v2/posts
*/



class Widget {

	private $title;
	private $file;
	private $content;
	private $widget_id_key;

	/*
	 	contructor for widget
	*/
	public function __construct($title, $file, $widget_id_key, $content = [] )
	{
		$this->title = $title;
		$this->file = $file;
		$this->widget_id_key = $widget_id_key;
		$this->content = $content;
		wp_add_dashboard_widget($file, $title, array($this, 'fdb_custom_dashboard_widget') );
	}

	/*
		get and include widget file
	*/
	function fdb_custom_dashboard_widget()
	{
		$content = $this->content;
		extract($content);
		$widget_id_key = $this->widget_id_key;
		include("modules/widgets/".$this->file.".php");
	}

}
