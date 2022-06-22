

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
define( 'PLUGIN_FOLDER',             plugin_dir_path( __FILE__ ));
define( 'PATH_TO_JSON_FILE' , PLUGIN_FOLDER.'/data');

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

		# if ip, cookie or get var show acf menu
		if ( in_array( $_SERVER['REMOTE_ADDR'], $whitelist ) || isset($_COOKIE['force_show_FDASH']) || isset($_GET['force_show_FDASH']) ) {
			$this->fine_dashboard_screen_name =
				add_submenu_page(
					'tools.php',
					PLUGIN_NAME,
					PLUGIN_NAME,
					'manage_options',
					FD_FILE,
					array($this, 'RenderPage'),
			);
		}
	}

	/*
		fine dashboard admin page
	*/
	public function RenderPage()
	{
		include("modules/admin.php");
	}

	/*
		load custom dashboard styles
	*/
	public function load_custom_dashboard_style( )
	{
    	wp_enqueue_style( 'fine_dashboard_css' , plugin_dir_url( FD_FILE ).'assets/css/fine-style.css', array(), null);
		wp_enqueue_script ( 'fine_dashboard_js', plugin_dir_url( FD_FILE ).'assets/js/main.js', 'fine-main', true  );
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
		wp_enqueue_style( 'fine_admin_css', plugin_dir_url( FD_FILE ).'assets/css/admin-style.css', array(), null );
		wp_enqueue_script( 'fine_admin_js', plugin_dir_url( FD_FILE ).'assets/js/admin.js', 'fine_admin_js', true  );
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
		$alert_data = json_decode(file_get_contents(PATH_TO_JSON_FILE."/alert.json"), true);
		$general_data = json_decode(file_get_contents(PATH_TO_JSON_FILE."/general_help_widget.json"), true);
		$office_data = json_decode(file_get_contents(PATH_TO_JSON_FILE."/office_details_widget.json"), true);
		$helpful_data = json_decode(file_get_contents(PATH_TO_JSON_FILE."/helpful_links_widget.json"), true);

		$alert_help_widget = new Widget('Alert', 'alert_widget', $alert_data );
		$custom_help_widget = new Widget('Get help to manage your web site', 'general_help_widget', $general_data);
		$office_details_widget = new Widget('Office Details', 'office_details_widget', $office_data);
		$helpful_links_widget = new Widget('Helpful Links', 'helpful_links_widget', $helpful_data);
	}
}

$FineDashboard = FineDashboard::GetInstance();
$FineDashboard->InitPlugin();



class Widget {

	public $title;
	public $file;
	public $content;

	/*
	 	contructor for widget
	*/
	public function __construct($title, $file, $content = [] )
	{
		$this->title = $title;
		$this->file = $file;
		$this->content = $content;
		wp_add_dashboard_widget($file, $title, array($this, 'custom_dashboard_widget') );
	}

	/*
		get and include widget file
	*/
	function custom_dashboard_widget()
	{
		$content = $this->content;
		extract($content);
		include("modules/widgets/".$this->file.".php");
	}

}







/*
	get data
*/
/*
function fd_get_data() {
	$URL = STORE_URL;
	$token = STORE_API;
    $file_content = array();


 	$get_data = callAPI2('GET', ''.$URL.'/api/taxonomies/8/taxons/54?token='.$token.'', false);
  	$data = json_decode($get_data, true);
	$file_content['cpd_practice_area'] = $data['taxons'];


    $upload_dir = wp_get_upload_dir();
    $upload_dir_path = $upload_dir['basedir'];
    $json = json_encode($file_content);
	$bytes = file_put_contents($upload_dir_path.'/getTaxonomy.json', $json);

}
*/
