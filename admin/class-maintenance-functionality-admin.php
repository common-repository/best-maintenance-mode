<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    maintenance_func
 * @subpackage maintenance_func/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    maintenance_func
 * @subpackage maintenance_func/admin
 * @author     Your Name <email@example.com>
 */
class maintenance_func_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $maintenance_func    The ID of this plugin.
	 */
	private $maintenance_func;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $maintenance_func       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $maintenance_func, $version ) {

		$this->maintenance_func = $maintenance_func;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in maintenance_func_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The maintenance_func_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'maintenance-main-admin-css', plugin_dir_url( __FILE__ ) . 'css/maintenance-main-admin.css', array(), $this->version, 'all' );

		if ( $hook != 'toplevel_page_wp-maintenance' && $hook != 'maintenance_page_maintenancepropro-mode-subscribers' ) {
			return;
		}

		wp_enqueue_style( $this->maintenance_func, plugin_dir_url( __FILE__ ) . 'css/maintenance-functionality-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'main-grid', plugin_dir_url( __FILE__ ) . 'css/grid.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in maintenance_func_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The maintenance_func_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'maintenance-main-admin', plugin_dir_url( __FILE__ ) . 'js/maintenance-main-admin.js', array( 'jquery' ), $this->version, false );

		if ( $hook != 'toplevel_page_wp-maintenance' && $hook != 'maintenance_page_maintenancepropro-mode-subscribers' ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_script( $this->maintenance_func, plugin_dir_url( __FILE__ ) . 'js/maintenance-functionality-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );	
		wp_localize_script( $this->maintenance_func, 'maintenance_main', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'img_folder' => maintenance_PLUGIN_URI . 'public/images/'
		));	
	}

}
