<?php
/**
 * Plugin Name.
 *
 * @package   PluginName
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 * Plugin class.
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package PluginName
 * @author  Your Name <email@example.com>
 */
class PostGenerator {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'post-generator';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		add_action( 'TODO', array( $this, 'action_method_name' ) );
		add_filter( 'TODO', array( $this, 'filter_method_name' ) );
		
		
		add_action( 'wp_ajax_pg_create_posts', array( $this, 'pg_create_posts_ajax_callback' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), $this->version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
		}

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), $this->version );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * TODO:
		 *
		 * Change 'Page Title' to the title of your plugin admin page
		 * Change 'Menu Text' to the text for menu item for the plugin settings page
		 * Change 'plugin-name' to the name of your plugin
		 */
		$this->plugin_screen_hook_suffix = add_management_page(
			__( 'Post Generator', $this->plugin_slug ),
			__( 'Post Generator Settings', $this->plugin_slug ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}
	
	
	/* AJAX function to create the posts */
	public function pg_create_posts_ajax_callback() {
		
		
		
		
		$i = 0;
		
		while( $i < 1200 ) {
			
			$post = array(
				'post_content'   => $this->get_lorem_ipsum(),
				'post_name'      => $this->get_random_post_name(),
				'post_title'     => $this->get_random_post_name(),
				'post_status'    => 'publish',
				'post_type'      => 'post'
			); 
			
			wp_insert_post( $post );
			$i++;
		}
		
		echo 'DONE';
		
		die();
	}
	
	public function get_lorem_ipsum() {
		
		$lipsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum posuere nisi nunc, eget pulvinar magna aliquam in. Praesent vel nisl porttitor, bibendum velit ut, aliquet dolor. Quisque pulvinar, purus iaculis consectetur pretium, mi enim dictum tortor, vitae viverra libero enim eu augue. Mauris at ipsum aliquam, tristique libero eget, facilisis lorem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed at lorem sit amet purus iaculis porta sed ac felis. Suspendisse justo neque, viverra at velit ac, aliquet suscipit enim. Etiam elit elit, posuere non est posuere, dignissim interdum metus. Donec elementum tempor ante eget placerat. Sed sodales elit eget arcu aliquet vehicula." .
					"urabitur nec tincidunt nisi. Quisque ornare eu metus non accumsan. Aliquam pellentesque metus ut ultrices hendrerit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Etiam ac aliquet dui. Donec posuere arcu eu lectus congue, et faucibus lorem venenatis. Nam semper suscipit tortor, sit amet interdum felis scelerisque eget. Mauris in tempor tortor. Mauris adipiscing volutpat ipsum, at lacinia metus tristique vel. Donec eu fringilla eros. Sed mollis nulla nec mi pulvinar, eu accumsan nunc adipiscing. Duis venenatis risus sit amet massa feugiat adipiscing eu nec sem. Pellentesque ante felis, ornare nec viverra non, condimentum ac justo." .
					"Quisque vitae fringilla libero. Fusce semper, elit fringilla facilisis lobortis, libero lectus commodo nunc, a laoreet neque velit faucibus augue. Donec vitae pretium tortor. Morbi mattis metus et cursus convallis. Donec convallis neque eros, at molestie quam pellentesque id. Nulla eleifend nulla ac lobortis vestibulum. Praesent non nulla a libero hendrerit varius. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aenean interdum fringilla dolor, sit amet tincidunt leo. Donec egestas nunc sit amet odio elementum facilisis eu et quam. Duis ac purus quis libero hendrerit pharetra nec quis lorem. Praesent sed sollicitudin neque, sit amet ullamcorper augue. Sed ut facilisis ante. Duis auctor tellus id massa imperdiet auctor. Ut dignissim purus ullamcorper leo rhoncus egestas. Sed et rhoncus velit." .
					"Phasellus eu nibh id dui mollis dictum at eu ante. Donec tristique arcu condimentum nibh eleifend, sit amet consequat arcu viverra. Nam facilisis nunc id iaculis interdum. Fusce id turpis accumsan, placerat libero luctus, porta tellus. In eget fermentum nulla, et blandit urna. Aliquam porta dolor lacus, nec ultricies nulla pulvinar ut. Nulla tellus urna, cursus eu quam a, dictum adipiscing ante. Nam viverra sem nec pretium cursus. Aliquam ullamcorper volutpat iaculis." .
					"Integer pretium enim dictum risus rhoncus, non elementum diam eleifend. Fusce ullamcorper congue nisl, vel bibendum sapien aliquam ac. Fusce ac lectus dui. Aenean feugiat dapibus nunc eu tincidunt. Praesent dui metus, tincidunt eu metus in, mollis molestie nibh. Morbi et convallis velit. Ut ligula quam, gravida at dictum sit amet, sollicitudin sit amet libero. Nulla facilisi. Sed aliquet commodo volutpat. Fusce non augue nec libero aliquam dapibus ut congue leo. Duis id lobortis ante. Nunc consectetur, metus sed mollis pulvinar, massa nunc luctus lectus, et pretium tellus nulla id nulla. In at velit nulla. Vestibulum vehicula consequat neque, eget pulvinar tortor elementum sed. Morbi eget vulputate lorem.";
		
		
		$lipsum_length = strlen( $lipsum );
		
		return substr( $lipsum, rand(0, $lipsum_length - 1 ), rand( 0, $lipsum_length - 1 ) );
	}
	
	public function get_random_post_name() {
		
		return 'Post Generator ' . rand();
	}

}