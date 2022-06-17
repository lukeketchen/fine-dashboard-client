

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
		add_action('admin_menu', array($this, 'PluginMenu'));
		add_action('wp_dashboard_setup', array($this, 'load_custom_dashboard_style'), 9999 );
		add_action('wp_dashboard_setup', array($this, 'fine_dashboard_remove_all_dashboard_meta_boxes'), 9999 );
		add_action( 'admin_enqueue_scripts', array($this, 'load_custom_wp_admin_style') );
	}

	public function PluginMenu()
	{
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

		// add new metabox
		$custom_help_widget = new Widget('Get help to manage your web site', 'custom_dashboard_help', array('url' => 'url to api goes here', 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5));
		$office_details_widget = new Widget('Office Details', 'custom_office_details', array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5));
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
		include("modules/".$this->file.".php");
	}

}
