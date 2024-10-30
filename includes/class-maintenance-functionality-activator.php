<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    maintenance_func
 * @subpackage maintenance_func/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    maintenance_func
 * @subpackage maintenance_func/includes
 * @author     Your Name <email@example.com>
 */
class maintenance_func_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function maintenance_activate() {
		global $wpdb;
		$settings = get_option('main_maintenance_settings');
		
		if ( !$settings ) {
			update_option('main_maintenance_settings', main_get_settings());
		}

		$main_notice = get_option('main_maintenance_rating_notice');
		
		if ( !$main_notice ) {
			update_option('main_maintenance_rating_notice', time());
		}

		$template_table  = $wpdb->prefix . 'maintenance_plugin_templates';
		$charset_collate = $wpdb->get_charset_collate();

		$template_sql = "CREATE TABLE IF NOT EXISTS $template_table (
				  `ID` int(9) NOT NULL AUTO_INCREMENT,
				  `template` text NOT NULL,
				  `template_html` text NOT NULL,
				  PRIMARY KEY (`ID`)
				) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $template_sql );

		// Default template
		$default_html = '{main_maintenance_logo}<div class=\"maintenance-inner\"><div class=\"maintenance-title\" data-content=\"title\"></div>{main_maintenance_countdown}<p class=\"maintenance-description\" data-content=\"description\"></p>{main_maintenance_social}{main_maintenance_mailchimp}</div><div class=\"main-maintenance-contact-wrapper\"><div class=\"main-maintenance-contact-us\"><span class=\"contact-us-open mmicon-paper-plane\"></span><div class=\"contact-us-inner\"><div class=\"contact-us-left\">{main_maintenance_contact_left}</div><div class=\"contact-us-right\">{main_maintenance_contact_right}</div><div class=\"clearfix\"></div></div></div></div>';
		if ( !$wpdb->query('SELECT * FROM ' . $template_table . ' WHERE template="default"') ) {
			$wpdb->query('INSERT INTO ' . $template_table . ' (template,template_html) VALUES ("default","'.$default_html.'")');
		} else {
			$wpdb->query('UPDATE ' . $template_table . ' SET template_html="'.$default_html.'" WHERE template="default"');
		}

	}

}
