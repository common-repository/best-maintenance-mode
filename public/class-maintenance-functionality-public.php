<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    maintenance_func
 * @subpackage maintenance_func/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    maintenance_func
 * @subpackage maintenance_func/public
 * @author     Your Name <email@example.com>
 */
class maintenance_func_Public {

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
	 * @param      string    $maintenance_func       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $maintenance_func, $version ) {

		$this->maintenance_func = $maintenance_func;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->maintenance_func, plugin_dir_url( __FILE__ ) . 'css/maintenance-functionality-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->maintenance_func, plugin_dir_url( __FILE__ ) . 'js/maintenance-functionality-public.js', array( 'jquery' ), $this->version, false );

	}

}
