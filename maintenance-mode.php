<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://cohhe.com
 * @since             1.0
 * @package           maintenance_func
 *
 * @wordpress-plugin
 * Plugin Name:       Maintenance mode
 * Plugin URI:        https://cohhe.com/
 * Description:       This plugin adds maintenance mode functionality to your website
 * Version:           1.3
 * Author:            Cohhe
 * Author URI:        https://cohhe.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       maintenance-mode
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-maintenance-functionality-activator.php
 */
function maintenance_activate_maintenance_func() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-maintenance-functionality-activator.php';
	maintenance_func_Activator::maintenance_activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-maintenance-functionality-deactivator.php
 */
function maintenance_deactivate_maintenance_func() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-maintenance-functionality-deactivator.php';
	maintenance_func_Deactivator::maintenance_deactivate();
}

register_activation_hook( __FILE__, 'maintenance_activate_maintenance_func' );
register_deactivation_hook( __FILE__, 'maintenance_deactivate_maintenance_func' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
define('maintenance_PLUGIN', plugin_dir_path( __FILE__ ));
define('maintenance_PLUGIN_URI', plugin_dir_url( __FILE__ ));
define('maintenance_PLUGIN_MENU_PAGE', 'wp-maintenance');
define('maintenance_PLUGIN_MENU_PAGE_URL', get_admin_url() . 'admin.php?page=' . maintenance_PLUGIN_MENU_PAGE);
require plugin_dir_path( __FILE__ ) . 'includes/class-maintenance-functionality.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_maintenance_func() {

	$plugin = new maintenance_func();
	$plugin->maintenance_run();

}
run_maintenance_func();

function main_register_maintenance_menu_page() {
	add_menu_page(
		__( 'Maintenance', 'maintenance-mode' ),
		__( 'Maintenance', 'maintenance-mode' ),
		'manage_options',
		maintenance_PLUGIN_MENU_PAGE,
		'',
		'dashicons-lock',
		6
	);

	add_submenu_page(
		maintenance_PLUGIN_MENU_PAGE,
		__('Settings', 'maintenance-mode'),
		__('Settings', 'maintenance-mode'),
		'manage_options',
		maintenance_PLUGIN_MENU_PAGE,
		'main_maintenance_settings'
	);
}
add_action( 'admin_menu', 'main_register_maintenance_menu_page' );

function main_get_settings() {
	$settings = array(
		'maintenance-status' => 'false',
		'background-image' => plugin_dir_url( __FILE__ ).'public/images/default-bg.jpg',
		'background-blur' => 'false',
		'maintenance-logo' => '',
		'maintenance-retina' => 'false',
		'robots' => 'noindex',
		'google-analytics' => 'false',
		'google-analytics-code' => '',
		'exclude' => '',
		'page-title' => 'Maintenance',
		'page-headline' => 'We\'re under maintenance',
		'page-headline-style' => '',
		'page-description' => 'We are currently working on our website. Stay tuned for more information.<br>Subscribe to our newsletter to stay updated on our progress.',
		'page-description-style' => '',
		'social-networks' => 'false',
		'social-target' => 'new',
		'social-github' => '',
		'social-dribbble' => '',
		'social-twitter' => '',
		'social-facebook' => '',
		'social-pinterest' => '',
		'social-gplus' => '',
		'social-linkedin' => '',
		);

	return json_encode($settings);
}

function main_maintenance_settings() {
	if ( !current_user_can('manage_options') )  {
		wp_die( __('You do not have sufficient permissions to access this page.', 'maintenance-mode') );
	}

	$main_maintenance_settings = (array)json_decode(get_option('main_maintenance_settings'));

	?>
		<div class="main-maintenance-wrapper">
			<div class="container-fluid">
				<a href="javascript:void(0)" class="btn btn-primary btn-lg save-main-maintenance">Save settings</a>
				<div class="row">
					<div class="col-sm-6">
						<div class="white-box">
							<h2>Maintenance configuration</h2>
							<p class="text-muted m-b-30 font-13">You'll be able to configure all your needed maintenance settings here.</p>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Maintenance status</label>
								<div class="input-wrapper col-md-9">
									<div class="main-maintenance-checkbox">
										<span></span>
										<input type="checkbox" id="main-maintenance-status" <?php echo ( isset($main_maintenance_settings['maintenance-status']) && $main_maintenance_settings['maintenance-status'] == 'true' ? 'checked' : '' ); ?>>
									</div>
								</div>
							</div>
							<?php do_action('main_maintenance_config_top'); ?>
							<?php if ( !function_exists('run_maintenancepro_func') ) { ?>
								<div class="form-group clearfix">
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Login form</label>
										<div class="input-wrapper col-md-9">
											<div class="main-maintenance-checkbox disabled">
												<span></span>
												<input type="checkbox" id="main-profeature9">
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Robots Meta Tag</label>
								<div class="input-wrapper col-md-9">
									<select id="main-robots" class="form-control">
										<option value="index" <?php echo ( isset($main_maintenance_settings['robots']) && $main_maintenance_settings['robots'] == 'index' ? 'selected' : '' ); ?>>index,follow</option>
										<option value="noindex" <?php echo ( isset($main_maintenance_settings['robots']) && $main_maintenance_settings['robots'] == 'noindex' ? 'selected' : '' ); ?>>noindex/nofollow</option>
									</select>
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Exclude</label>
								<div class="input-wrapper col-md-9">
									<textarea id="main-exclude" class="form-control"><?php echo (isset($main_maintenance_settings['exclude']) && $main_maintenance_settings['exclude'] != ''?str_replace('|', PHP_EOL, $main_maintenance_settings['exclude']):'feed'.PHP_EOL.'wp-login'.PHP_EOL.'login'); ?></textarea>
									<p class="text-muted font-13">You're able to exclude feeds, pages or IPs from maintenance mode. Add one exclude option per line!</p>
								</div>
							</div>
							<div class="">
								<div class="form-group clearfix">
									<label for="pm-image-to-url" class="control-label col-md-3">Enable google analytics?</label>
									<div class="input-wrapper col-md-9">
										<div class="main-maintenance-checkbox">
											<span></span>
											<input type="checkbox" id="main-google-analytics" <?php echo ( isset($main_maintenance_settings['google-analytics']) && $main_maintenance_settings['google-analytics'] == 'true' ? 'checked' : '' ); ?>>
										</div>
									</div>
								</div>
								<div class="form-group clearfix">
									<label for="pm-image-to-url" class="control-label col-md-3">Analytics tracking code</label>
									<div class="input-wrapper col-md-9">
										<textarea id="main-google-analytics-code" class="form-control"><?php echo (isset($main_maintenance_settings['google-analytics-code']) && $main_maintenance_settings['google-analytics-code'] != '' ? $main_maintenance_settings['google-analytics-code'] : ''); ?></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="white-box <?php echo ($main_maintenance_settings['page-headline-style']==''&&$main_maintenance_settings['page-description-style']==''?'reset-default-hidden':''); ?>">
							<h2 style="display:inline-block;">Maintenance texts</h2><a href="javascript:void(0)" id="main-reset-defaults">Reset defaults</a>
							<p class="text-muted m-b-30 font-13">Here you can style and change your maintenance texts.</p>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Page headline</label>
								<div class="input-wrapper col-md-9">
									<div id="main-page-headline" class="form-control" spellcheck="false" contenteditable="true" style="<?php echo (isset($main_maintenance_settings['page-headline-style'])?$main_maintenance_settings['page-headline-style']:''); ?>"><?php echo (isset($main_maintenance_settings['page-headline'])?$main_maintenance_settings['page-headline']:''); ?></div>
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Page description</label>
								<div class="input-wrapper col-md-9">
									<div id="main-page-description" class="form-control textarea" spellcheck="false" contenteditable="true" style="<?php echo (isset($main_maintenance_settings['page-description-style'])?$main_maintenance_settings['page-description-style']:''); ?>"><?php echo (isset($main_maintenance_settings['page-description'])?$main_maintenance_settings['page-description']:''); ?></div>
								</div>
							</div>
							<?php do_action('main_maintenance_text_style_bottom'); ?>
							<div class="form-group text-styling-row">
								<input type="hidden" id="main-edited-text" value="">
								<div class="col-md-3">
									<h4>Text color</h4>
									<input type="text" id="main-color" class="text-styling wp-colorpicker" value="">
								</div>
								<div class="col-md-3">
									<h4>Text size</h4>
									<div class="main-number-field">
										<span class="main-number-minus">-</span>
										<input type="number" id="main-font-size" class="form-control text-styling" value="">
										<span class="main-number-plus">+</span>
									</div>
								</div>
								<div class="col-md-3">
									<h4>Text line height</h4>
									<div class="main-number-field">
										<span class="main-number-minus">-</span>
										<input type="number" id="main-line-height" class="form-control text-styling" value="">
										<span class="main-number-plus">+</span>
									</div>
								</div>
								<div class="col-md-3">
									<h4>Text style</h4>
									<select id="main-font-weight" class="form-control text-styling">
										<option value="400">Normal</option>
										<option value="300">Light</option>
										<option value="bold">Bold</option>
									</select>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<?php do_action('main_maintenance_first_column_bottom'); ?>
						<?php if ( !function_exists('run_maintenancepro_func') ) { ?>
								<div class="white-box">
									<h2>Contact us section</h2>
									<p class="text-muted m-b-30 font-13">Here you can control what's shown at the contact us section.</p>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Contact us title</label>
										<div class="input-wrapper col-md-9">
											<input type="text" id="main-profeature36" class="form-control" value="Contact us">
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Contact us description</label>
										<div class="input-wrapper col-md-9">
											<input type="text" id="main-profeature37" class="form-control" value="In need of our assistance? Feel free to use our contact details to reach us, we'll fladly find a way to help you!">
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Address text</label>
										<div class="input-wrapper col-md-9">
											<input type="text" id="main-profeature38" class="form-control" value="66 Grand central, New York">
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Call us text</label>
										<div class="input-wrapper col-md-9">
											<input type="text" id="main-profeature39" class="form-control" value="+(999) 999 999 999">
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Write us text</label>
										<div class="input-wrapper col-md-9">
											<input type="text" id="main-profeature40" class="form-control" value="email@example.com">
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Contact form email</label>
										<div class="input-wrapper col-md-9">
											<input type="text" id="main-profeature41" class="form-control" value="">
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Enable reCAPTCHA?</label>
										<div class="input-wrapper col-md-9">
											<div class="main-maintenance-checkbox">
												<span></span>
												<input type="checkbox" id="main-profeature42">
											</div>
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">reCAPTCHA site key</label>
										<div class="input-wrapper col-md-9">
											<input type="text" id="main-profeature43" class="form-control" value="">
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">reCAPTCHA site secret</label>
										<div class="input-wrapper col-md-9">
											<input type="text" id="main-profeature44" class="form-control" value="">
										</div>
									</div>
								</div>
								<div class="white-box">
									<h2>Access controls</h2>
									<p class="text-muted m-b-30 font-13">Here you can control who can access your site.</p>
									<div class="form-group bypass-url clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Bypass url</label>
										<div class="input-wrapper col-md-9">
											<?php echo '<b>'.get_home_url().'/</b>'; ?><input type="text" id="main-profeature1" class="form-control profeature" value="" disabled>
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Bypass expires</label>
										<div class="input-wrapper col-md-9">
											<input type="text" id="main-profeature2" class="form-control profeature" value="" disabled>
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Access by IP</label>
										<div class="input-wrapper col-md-9">
											<textarea id="main-profeature3" class="form-control profeature" disabled></textarea>
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Access by Role</label>
										<div class="input-wrapper col-md-9">
											<select id="main-profeature4" class="form-control profeature" multiple disabled>
												<option value="1">Anyone logged in</option>
												<option value="2">Administrator</option>
												<option value="3">Editor</option>
												<option value="4">Author</option>
												<option value="5">Contributor</option>
												<option value="6">Subscriber</option>
											</select>
										</div>
									</div>
								</div>
						<?php } ?>
					</div>
					<div class="col-sm-6">
						<div class="white-box">
							<h2>Maintenance mode style</h2>
							<p class="text-muted m-b-30 font-13">Here you'll be able to change what appears on your front page.</p>
							<?php do_action('main_maintenance_looks_top'); ?>
							<?php if ( !function_exists('run_maintenancepro_func') ) { ?>
								<div class="form-group clearfix main-grayed">
									<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Maintenance template</label>
									<div class="input-wrapper file-upload col-md-9">
										<div class="main-fake-select" data-selected="default">
											<ul>
												<li data-value="default" data-image="" class="selected">Default</li>
												<li data-value="style2" data-image="" class="cant-select">Style 2 - PRO version only</li>
												<li data-value="style3" data-image="" class="cant-select">Style 3 - PRO version only</li>
												<li data-value="style4" data-image="" class="cant-select">Style 4 - PRO version only</li>
												<li data-value="style5" data-image="" class="cant-select">Style 5 - PRO version only</li>
												<li data-value="style6" data-image="" class="cant-select">Style 6 - PRO version only</li>
												<li data-value="style7" data-image="" class="cant-select">Style 7 - PRO version only</li>
												<li data-value="style8" data-image="" class="cant-select">Style 8 - PRO version only</li>
												<li data-value="style9" data-image="" class="cant-select">Style 9 - PRO version only</li>
												<li data-value="style10" data-image="" class="cant-select">Style 10 - PRO version only</li>
												<li data-value="style11" data-image="" class="cant-select">Style 11 - PRO version only</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="form-group clearfix main-grayed">
									<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Background video</label>
									<div class="input-wrapper file-upload col-md-9">
										<a href="javascript:void(0)" class="choose-image">Choose video</a>
										<input type="text" id="main-profeature5" class="form-control" value="" disabled>
										<p class="text-muted font-13">If a video is added, the background image is going to be overwritten with a video.</p>
									</div>
								</div>
								<div class="form-group clearfix main-grayed">
									<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Background animation</label>
									<div class="input-wrapper file-upload col-md-9">
										<select id="main-profeature6" class="form-control">
											<option value="none" selected>None</option>
											<option value="profeature1">Interactive lines</option>
											<option value="profeature2">Raising bubbles</option>
											<option value="profeature3">Spewing triangles</option>
											<option value="profeature4">Rotating lines</option>
										</select>
									</div>
								</div>
							<?php } ?>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Background image</label>
								<div class="input-wrapper file-upload <?php echo (isset($main_maintenance_settings['background-image'])&&$main_maintenance_settings['background-image']!=''?'remove-active':''); ?> col-md-9">
									<a href="javascript:void(0)" class="choose-image">Choose image</a>
									<input type="text" id="main-background-image" class="form-control" value="<?php echo (isset($main_maintenance_settings['background-image'])?$main_maintenance_settings['background-image']:''); ?>" disabled>
									<a href="javascript:void(0)" class="delete-image">Remove</a>
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Background image blur effect</label>
								<div class="input-wrapper col-md-9">
									<div class="main-maintenance-checkbox">
										<span></span>
										<input type="checkbox" id="main-background-blur" <?php echo ( isset($main_maintenance_settings['background-blur']) && $main_maintenance_settings['background-blur'] == 'true' ? 'checked' : '' ); ?>>
									</div>
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Logo</label>
								<div class="input-wrapper file-upload <?php echo (isset($main_maintenance_settings['maintenance-logo'])&&$main_maintenance_settings['maintenance-logo']!=''?'remove-active':''); ?> col-md-9">
									<a href="javascript:void(0)" class="choose-image">Choose image</a>
									<input type="text" id="main-maintenance-logo" class="form-control" value="<?php echo (isset($main_maintenance_settings['maintenance-logo'])?$main_maintenance_settings['maintenance-logo']:''); ?>">
									<a href="javascript:void(0)" class="delete-image">Remove</a>
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Is logo retina ready?</label>
								<div class="input-wrapper col-md-9">
									<div class="main-maintenance-checkbox">
										<span></span>
										<input type="checkbox" id="main-maintenance-retina" <?php echo ( isset($main_maintenance_settings['maintenance-retina']) && $main_maintenance_settings['maintenance-retina'] == 'true' ? 'checked' : '' ); ?>>
									</div>
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Page title</label>
								<div class="input-wrapper col-md-9">
									<input type="text" id="main-page-title" class="form-control" value="<?php echo (isset($main_maintenance_settings['page-title'])?$main_maintenance_settings['page-title']:''); ?>">
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Custom CSS</label>
								<div class="input-wrapper col-md-9">
									<textarea id="main-maintenance-css" class="form-control"><?php echo (isset($main_maintenance_settings['maintenance-css']) && $main_maintenance_settings['maintenance-css'] != '' ? $main_maintenance_settings['maintenance-css'] : ''); ?></textarea>
								</div>
							</div>
							<?php do_action('main_maintenance_looks_bottom'); ?>
							<?php if ( !function_exists('run_maintenancepro_func') ) { ?>
								<div class="form-group clearfix">
									<div class="form-group clearfix">
										<div class="form-group clearfix main-grayed">
											<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Countdown till</label>
											<div class="input-wrapper col-md-9">
												<input type="text" id="main-profeature8" class="form-control" value="" disabled>
												<p class="text-muted font-13">Want your visitors to know when your site is going to be back? Add a countdown to your page!</p>
											</div>
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">After Countdown expires</label>
										<div class="col-md-9">
											<select id="main-profeature30" class="form-control">
												<option value="disable" selected>Disable coming soon page</option>
												<option value="remove">Remove countdown</option>
											</select>
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Translate countdown</label>
										<div class="input-wrapper countdown-translate col-md-9" style="padding-left: 0;">
											<div class="col-md-3">
												<label for="pm-image-to-url" class="control-label col-md-3">Hours</label>
												<input type="text" id="main-profeature31" class="form-control" value="Hours">
											</div>
											<div class="col-md-3">
												<label for="pm-image-to-url" class="control-label col-md-3">Minutes</label>
												<input type="text" id="main-profeature32" class="form-control" value="Min">
											</div>
											<div class="col-md-3">
												<label for="pm-image-to-url" class="control-label col-md-3">Seconds</label>
												<input type="text" id="main-profeature33" class="form-control" value="Sec">
											</div>
											<div class="col-md-3">
												<label for="pm-image-to-url" class="control-label col-md-3">Days</label>
												<input type="text" id="main-profeature34" class="form-control" value="Days">
											</div>
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Subscription form</label>
										<div class="input-wrapper col-md-9">
											<textarea id="main-profeature7" class="form-control" disabled></textarea>
											<p class="text-muted font-13">Want to display your MailChimp signup form at the front page? Add it here!</p>
										</div>
									</div>
									<div class="form-group clearfix main-grayed">
										<label for="pm-image-to-url" class="control-label col-md-3 main-maintenance-locked">Collect subscribers at dashboard</label>
										<div class="input-wrapper col-md-9">
											<div class="main-maintenance-checkbox">
												<span></span>
												<input type="checkbox" id="main-profeature35">
											</div>
											<p class="text-muted font-13">If this is enabled then your "Subscription form" will be replaced with built-in form that collects data at the dashboard!</p>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="white-box clearfix">
							<h2>Social networks</h2>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Enable social networks?</label>
								<div class="input-wrapper col-md-9">
									<div class="main-maintenance-checkbox">
										<span></span>
										<input type="checkbox" id="main-social-networks" <?php echo ( isset($main_maintenance_settings['social-networks']) && $main_maintenance_settings['social-networks'] == 'true' ? 'checked' : '' ); ?>>
									</div>
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Link targets</label>
								<div class="input-wrapper col-md-9">
									<select id="main-social-target" class="form-control">
										<option value="new" <?php echo ( isset($main_maintenance_settings['social-target']) && $main_maintenance_settings['social-target'] == 'new' ? 'selected' : '' ); ?>>New page</option>
										<option value="same" <?php echo ( isset($main_maintenance_settings['social-target']) && $main_maintenance_settings['social-target'] == 'same' ? 'selected' : '' ); ?>>Same page</option>
									</select>
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Github</label>
								<div class="input-wrapper col-md-9">
									<input type="text" id="main-social-github" class="form-control" value="<?php echo (isset($main_maintenance_settings['social-github'])?$main_maintenance_settings['social-github']:''); ?>">
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Dribbble</label>
								<div class="input-wrapper col-md-9">
									<input type="text" id="main-social-dribbble" class="form-control" value="<?php echo (isset($main_maintenance_settings['social-dribbble'])?$main_maintenance_settings['social-dribbble']:''); ?>">
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Twitter</label>
								<div class="input-wrapper col-md-9">
									<input type="text" id="main-social-twitter" class="form-control" value="<?php echo (isset($main_maintenance_settings['social-twitter'])?$main_maintenance_settings['social-twitter']:''); ?>">
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Facebook</label>
								<div class="input-wrapper col-md-9">
									<input type="text" id="main-social-facebook" class="form-control" value="<?php echo (isset($main_maintenance_settings['social-facebook'])?$main_maintenance_settings['social-facebook']:''); ?>">
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Pinterest</label>
								<div class="input-wrapper col-md-9">
									<input type="text" id="main-social-pinterest" class="form-control" value="<?php echo (isset($main_maintenance_settings['social-pinterest'])?$main_maintenance_settings['social-pinterest']:''); ?>">
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">Google+</label>
								<div class="input-wrapper col-md-9">
									<input type="text" id="main-social-gplus" class="form-control" value="<?php echo (isset($main_maintenance_settings['social-gplus'])?$main_maintenance_settings['social-gplus']:''); ?>">
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="pm-image-to-url" class="control-label col-md-3">LinkedIn</label>
								<div class="input-wrapper col-md-9">
									<input type="text" id="main-social-linkedin" class="form-control" value="<?php echo (isset($main_maintenance_settings['social-linkedin'])?$main_maintenance_settings['social-linkedin']:''); ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	<?php
}

function main_get_content() {
	global $wpdb;

	// SETTINGS
	$main_maintenance_settings = (array)json_decode(get_option('main_maintenance_settings'));
	$main_template = (isset($main_maintenance_settings['template'])?$main_maintenance_settings['template']:'default');

	$demo = false;
	$current_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if ( strpos($current_link, '.cohhe.') !== false && strpos($current_link, 'default') !== false ) {
		$demo = true;
		$main_maintenance_settings['template'] = $main_template = 'default';
		$main_maintenance_settings['background-image'] = maintenance_PLUGIN_URI . '/public/images/default-bg.jpg';
	} else if ( strpos($current_link, '.cohhe.') !== false && strpos($current_link, 'style2') !== false ) {
		$demo = true;
		$main_maintenance_settings['template'] = $main_template = 'style2';
		$main_maintenance_settings['background-image'] = maintenance_PLUGIN_URI . '/public/images/style2-bg.jpg';
	} else if ( strpos($current_link, '.cohhe.') !== false && strpos($current_link, 'style3') !== false ) {
		$demo = true;
		$main_maintenance_settings['template'] = $main_template = 'style3';
		$main_maintenance_settings['background-image'] = maintenance_PLUGIN_URI . '/public/images/style3-bg.jpg';
	} else if ( strpos($current_link, '.cohhe.') !== false && strpos($current_link, 'style4') !== false ) {
		$demo = true;
		$main_maintenance_settings['template'] = $main_template = 'style4';
		$main_maintenance_settings['background-image'] = maintenance_PLUGIN_URI . '/public/images/style4-bg.jpg';
	} else if ( strpos($current_link, '.cohhe.') !== false && strpos($current_link, 'style5') !== false ) {
		$demo = true;
		$main_maintenance_settings['template'] = $main_template = 'style5';
		$main_maintenance_settings['background-image'] = maintenance_PLUGIN_URI . '/public/images/style5-bg.jpg';
	} else if ( strpos($current_link, '.cohhe.') !== false && strpos($current_link, 'style6') !== false ) {
		$demo = true;
		$main_maintenance_settings['template'] = $main_template = 'style6';
		$main_maintenance_settings['background-image'] = maintenance_PLUGIN_URI . '/public/images/style6-bg.jpg';
	} else if ( strpos($current_link, '.cohhe.') !== false && strpos($current_link, 'style7') !== false ) {
		$demo = true;
		$main_maintenance_settings['template'] = $main_template = 'style7';
		$main_maintenance_settings['background-image'] = maintenance_PLUGIN_URI . '/public/images/style7-bg.jpg';
	} else if ( strpos($current_link, '.cohhe.') !== false && strpos($current_link, 'style8') !== false ) {
		$demo = true;
		$main_maintenance_settings['template'] = $main_template = 'style8';
		$main_maintenance_settings['background-image'] = maintenance_PLUGIN_URI . '/public/images/style8-bg.jpg';
	} else if ( strpos($current_link, '.cohhe.') !== false && strpos($current_link, 'style9') !== false ) {
		$demo = true;
		$main_maintenance_settings['template'] = $main_template = 'style9';
		$main_maintenance_settings['background-image'] = maintenance_PLUGIN_URI . '/public/images/style9-bg.jpg';
	} else if ( strpos($current_link, '.cohhe.') !== false && strpos($current_link, 'style10') !== false ) {
		$demo = true;
		$main_maintenance_settings['template'] = $main_template = 'style10';
		$main_maintenance_settings['background-image'] = maintenance_PLUGIN_URI . '/public/images/style10-bg.jpg';
	} else if ( strpos($current_link, '.cohhe.') !== false && strpos($current_link, 'style11') !== false ) {
		$demo = true;
		$main_maintenance_settings['template'] = $main_template = 'style11';
		$main_maintenance_settings['background-image'] = maintenance_PLUGIN_URI . '/public/images/style11-bg.jpg';
	}

	$template = $wpdb->get_results('SELECT template_html FROM '.$wpdb->prefix.'maintenance_plugin_templates WHERE template="' . $main_template . '"');

	$wp_scripts = new WP_Scripts();
	$jquery_src = ( !empty($wp_scripts->registered['jquery-core']) ? home_url($wp_scripts->registered['jquery-core']->src) : '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js' );

	if ( isset($_GET['contact-form']) && $_GET['contact-form'] == 'sent' ) {
		$subject = ( isset($_POST['your-subject']) && $_POST['your-subject'] != '' ? sanitize_text_field($_POST['your-subject']) : 'No subject' );
		$message = 'Sender name: ' . sanitize_text_field($_POST['your-name']) . PHP_EOL . 'Sender e-mail: ' . sanitize_text_field($_POST['your-email']) . PHP_EOL . 'Sender wrote:' . PHP_EOL . sanitize_text_field($_POST['your-message']);

		$maintenance_email = wp_mail( $main_maintenance_settings['contact-email'], $subject, $message );
		
		if ( $maintenance_email ) {
			$_GET['main-email-notice'] = '<span class="contact-us-notice success">Your email was sent successfully!</span>';
		} else {
			$_GET['main-email-notice'] = '<span class="contact-us-notice error">Something went wrong!</span>';
		}
	}

	if (
		(
			$main_maintenance_settings['maintenance-status'] == 'true' &&
			!strstr($_SERVER['PHP_SELF'], 'wp-cron.php') &&
			!strstr($_SERVER['PHP_SELF'], 'wp-login.php') &&
			!strstr($_SERVER['PHP_SELF'], 'wp-admin/') &&
			!strstr($_SERVER['PHP_SELF'], 'async-upload.php') &&
			!strstr($_SERVER['PHP_SELF'], 'upgrade.php') &&
			!strstr($_SERVER['PHP_SELF'], '/plugins/') &&
			!strstr($_SERVER['PHP_SELF'], '/xmlrpc.php') &&
			!main_check_user_role() &&
			!main_check_exclude()
		) || $demo
	) {
		// HEADER STUFF
		$protocol = !empty($_SERVER['SERVER_PROTOCOL']) && in_array($_SERVER['SERVER_PROTOCOL'], array('HTTP/1.1', 'HTTP/1.0')) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
		$charset = get_bloginfo('charset') ? get_bloginfo('charset') : 'UTF-8';

		nocache_headers();
		ob_start();
		header("Content-type: text/html; charset=$charset");
		header("$protocol 503 Service Unavailable", TRUE, 503);

		$default_themes = array( 'default', 'style2', 'style3', 'style4', 'style5', 'style6', 'style7', 'style8', 'style9', 'style10', 'style11' );
		?>

		
		<link rel="stylesheet" id="maintenance-css" href="<?php echo plugin_dir_url( __FILE__ ).'/public/css/maintenance-functionality-public.css'; ?>" type="text/css" media="all">
		<link href='https://fonts.googleapis.com/css?family=Merriweather:300,400,700|Montserrat:300,400,700|Open+Sans:300,400,700|Roboto:300,400,700|Lato:300,400,700|Aldrich:400|Raleway:300:400:600|Iceberg:400' rel='stylesheet' type='text/css'>
		<script src="<?php echo $jquery_src; ?>" type="text/javascript"></script>
		<?php do_action('main_maintenance_head'); ?>
		<?php do_action('main_maintenance_footer'); ?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				<?php if ( $main_maintenance_settings['google-analytics'] == 'true' ) {
					echo $main_maintenance_settings['google-analytics-code'];
				} ?>
				<?php if ( isset($_GET['contact-form']) && $_GET['contact-form'] == 'sent' ) { ?>
					jQuery('body').addClass('contact-opened');
				<?php } ?>
			});
		</script>
		<script src="<?php echo plugin_dir_url( __FILE__ ).'/public/js' ?>/maintenance-functionality-public.js" type="text/javascript"></script>
		
		<script type="text/preloaded" id="main-template-data"><?php main_get_template( $main_template ); ?></script>
		<title><?php echo $main_maintenance_settings['page-title']; ?></title>
		<?php if ( isset($main_maintenance_settings['maintenance-css']) && $main_maintenance_settings['maintenance-css'] != '' ) { ?>
			<style type="text/css"><?php echo $main_maintenance_settings['maintenance-css']; ?></style>
		<?php } ?>
		<meta name="robots" content="<?php echo ($main_maintenance_settings['robots']=='noindex'?'noindex, nofollow':'index, follow'); ?>">
		<div class="maintenance-wrapper template-<?php echo (isset($main_maintenance_settings['template'])?$main_maintenance_settings['template']:'default')?> <?php echo ( ( ( isset($main_maintenance_settings['recaptcha-key']) && $main_maintenance_settings['recaptcha-key'] != '' ) && ( isset($main_maintenance_settings['recaptcha-secret']) && $main_maintenance_settings['recaptcha-secret'] != '' ) )?'recaptcha':'' ) ?>">
			<?php if ( in_array($main_maintenance_settings['template'], $default_themes) ) { ?>
				<?php if ( isset($main_maintenance_settings['animation']) && $main_maintenance_settings['animation'] != 'none' ) { ?>
					<canvas id="main-animation-canvas"></canvas>
				<?php } ?>
				<?php main_prepare_html( $template['0'], $main_maintenance_settings ); ?>
				<?php if ( isset($main_maintenance_settings['maintenance-login']) && $main_maintenance_settings['maintenance-login'] == 'true' && !is_user_logged_in() ) {
					do_action('main_maintenance_login_form');
				} ?>
				<?php if ( function_exists('run_maintenancepro_func') ) {
					// && isset($main_maintenance_settings['template']) && $main_maintenance_settings['template'] != 'style5'
					do_action('main_maintenance_contact_us');
				} ?>
			<?php } else {
				$theme_html = get_template_directory().'/maintenance-template/'.$main_maintenance_settings['template'].'/theme-html.php';
				$theme_style = get_template_directory().'/maintenance-template/'.$main_maintenance_settings['template'].'/theme-style.css';
				if ( file_exists($theme_html) ) {
					include $theme_html;
				}
				if ( file_exists($theme_style) ) {
					echo "<link href='".get_template_directory_uri().'/maintenance-template/'.$main_maintenance_settings['template'].'/theme-style.css'."' rel='stylesheet' type='text/css'>";
				}
			} ?>
		</div>
		<?php if ( $main_maintenance_settings['background-image'] != '' ) { ?>
			<style type="text/css">#main-maintenance-bg div{background: url(<?php echo $main_maintenance_settings['background-image']; ?>) no-repeat;background-size:cover;background-position:center;}</style>
			<div id="main-maintenance-bg" class="<?php echo (isset($main_maintenance_settings['background-blur'])&&$main_maintenance_settings['background-blur'] == 'true'?'blurred':'');?>"><div></div></div>
		<?php } ?>
		<?php do_action('main_maintenance_video'); ?>

		<?php
		ob_flush();
		?>
		
		
		<?php
		exit();
	}	
}
add_action('init', 'main_get_content');

function main_check_search_bots() {
	$is_search_bots = false;

	$bots = apply_filters('wpmm_search_bots', array(
		'Abacho' => 'AbachoBOT',
		'Accoona' => 'Acoon',
		'AcoiRobot' => 'AcoiRobot',
		'Adidxbot' => 'adidxbot',
		'AltaVista robot' => 'Altavista',
		'Altavista robot' => 'Scooter',
		'ASPSeek' => 'ASPSeek',
		'Atomz' => 'Atomz',
		'Bing' => 'bingbot',
		'BingPreview' => 'BingPreview',
		'CrocCrawler' => 'CrocCrawler',
		'Dumbot' => 'Dumbot',
		'eStyle Bot' => 'eStyle',
		'FAST-WebCrawler' => 'FAST-WebCrawler',
		'GeonaBot' => 'GeonaBot',
		'Gigabot' => 'Gigabot',
		'Google' => 'Googlebot',
		'ID-Search Bot' => 'IDBot',
		'Lycos spider' => 'Lycos',
		'MSN' => 'msnbot',
		'MSRBOT' => 'MSRBOT',
		'Rambler' => 'Rambler',
		'Scrubby robot' => 'Scrubby',
		'Yahoo' => 'Yahoo'
	));

	$is_search_bots = (bool) preg_match('~(' . implode('|', array_values($bots)) . ')~i', $_SERVER['HTTP_USER_AGENT']);

	return $is_search_bots;
}

function main_check_user_role() {
	$user = wp_get_current_user();
	$is_allowed = false;
	$main_maintenance_settings = (array)json_decode(get_option('main_maintenance_settings'));

	// if ( is_super_admin() ) {
	// 	$is_allowed = true;
	// }

	if ( isset($main_maintenance_settings['access-by-role']) ) {
		$allowed = explode(',', $main_maintenance_settings['access-by-role']);
		if ( array_intersect($allowed, $user->roles ) ) {
			$is_allowed = true;
		}

		if ( $main_maintenance_settings['access-by-role'] == 'logged-in' && is_user_logged_in() ) {
			$is_allowed = true;
		}
	}

	return $is_allowed;
}

function main_check_exclude() {
	$is_excluded = false;
	$main_maintenance_settings = (array)json_decode(get_option('main_maintenance_settings'));

	if ( $main_maintenance_settings['exclude'] != '' ) {
		$excludes = explode('|', $main_maintenance_settings['exclude']);
		if ( isset($main_maintenance_settings['bypass-url']) && $main_maintenance_settings['bypass-url'] != '' ) {
			$excludes[] = $main_maintenance_settings['bypass-url'];
		}
		if ( !empty($excludes) ) {
			foreach ($excludes as $exclude_item) {
				if ((!empty($_SERVER['REMOTE_ADDR']) && strstr($_SERVER['REMOTE_ADDR'], $exclude_item)) || (!empty($_SERVER['REQUEST_URI']) && strstr($_SERVER['REQUEST_URI'], $exclude_item))) {
					$is_excluded = true;
					$main_maintenance_settings = (array)json_decode(get_option('main_maintenance_settings'));
					if ( $exclude_item == $main_maintenance_settings['bypass-url'] ) {
						$bypass_expire = ( isset($main_maintenance_settings['bypass-expires']) ? $main_maintenance_settings['bypass-expires'] : '172800' );
						setcookie('main_maintenance_bypass', 'bypass', time() + (int)$bypass_expire, '/', false);
					}
					break;
				}
			}
		}
	}
	if ( isset($main_maintenance_settings['bypass-url']) && $main_maintenance_settings['bypass-url'] != '' ) {
		$excludes = array( $main_maintenance_settings['bypass-url'] );
		if ( !empty($excludes) ) {
			foreach ($excludes as $exclude_item) {
				if ((!empty($_SERVER['REMOTE_ADDR']) && strstr($_SERVER['REMOTE_ADDR'], $exclude_item)) || (!empty($_SERVER['REQUEST_URI']) && strstr($_SERVER['REQUEST_URI'], $exclude_item))) {
					$is_excluded = true;
					$main_maintenance_settings = (array)json_decode(get_option('main_maintenance_settings'));
					$bypass_expire = ( isset($main_maintenance_settings['bypass-expires']) ? $main_maintenance_settings['bypass-expires'] : '172800' );
					setcookie('main_maintenance_bypass', 'bypass', time() + (int)$bypass_expire, '/', false);
					break;
				}
			}
		}
		if ( isset($_COOKIE['main_maintenance_bypass']) && $_COOKIE['main_maintenance_bypass'] == 'bypass' ) {
			$is_excluded = true;
		}
	}
	if ( isset($main_maintenance_settings['access-by-ip']) && $main_maintenance_settings['access-by-ip'] != '' ) {
		$exclude_ip = explode('|', $main_maintenance_settings['access-by-ip']);
		if ( !empty($exclude_ip) ) {
			foreach ($exclude_ip as $ip_value) {
				if ( !empty($_SERVER['REMOTE_ADDR']) && strstr($_SERVER['REMOTE_ADDR'], $ip_value) ) {
					$is_excluded = true;
					break;
				}
			}
		}
	}

	return $is_excluded;
}

function main_save_maintenance() {
	$main_settings = ( isset($_POST['main_settings']) ? str_replace('\\', '', $_POST['main_settings']) : '' );
	update_option( 'main_maintenance_settings', $main_settings );

	die(0);
}
add_action( 'wp_ajax_main_save_maintenance_settings', 'main_save_maintenance' );

function sample_admin_notice__success() {
	$user = wp_get_current_user();
	if ( get_option('main_maintenance_rating_notice') != 'hide' && time() - get_option('main_maintenance_rating_notice') > 432000 ) {
	?>
	<div class="main-maintenance-notice">
		<span class="main-notice-left">
			<img src="<?php echo maintenance_PLUGIN_URI; ?>admin/images/logo-square.png" alt="">
		</span>
		<div class="main-notice-center">
			<p>Hi there, <?php echo $user->data->display_name; ?>, we noticed that you've been using our Maintenance mode plugin for a while now.</p>
			<p>We spent many hours developing this free plugin for you and we would appriciate if you supported us by rating our plugin!</p>
		</div>
		<div class="main-notice-right">
			<a href="#" class="button button-primary button-large main-maintenance-rate">Rate at WordPress</a>
			<a href="javascript:void(0)" class="button button-large preview main-maintenance-dismiss">No, thanks</a>
		</div>
		<div class="clearfix"></div>
	</div>
	<?php }
}
add_action( 'admin_notices', 'sample_admin_notice__success' );

function main_dismiss_maintenance_notice() {
	update_option('main_maintenance_rating_notice', 'hide');

	die(0);
}
add_action( 'wp_ajax_nopriv_main_dismiss_notice', 'main_dismiss_maintenance_notice' );
add_action( 'wp_ajax_main_dismiss_notice', 'main_dismiss_maintenance_notice' );

function main_get_template( $template ) {
	// SETTINGS
	$main_maintenance_settings = (array)json_decode(get_option('main_maintenance_settings'));

	$output = array(
		'texts' => array(
			'title' => $main_maintenance_settings['page-headline'],
			'description' => $main_maintenance_settings['page-description']
			),
		'styles' => array(
			'title' => $main_maintenance_settings['page-headline-style'],
			'description' => $main_maintenance_settings['page-description-style']
			)
	);

	echo json_encode( $output );
}

function main_get_logo( $main_maintenance_settings ) {
	$output = '';
	if ( $main_maintenance_settings['maintenance-logo'] ) {
		$logo_size_html = '';
		if ( $main_maintenance_settings['maintenance-retina'] == 'true' ) {
			$logo_size = getimagesize( $main_maintenance_settings['maintenance-logo'] );
			$logo_size_html = ' style="height: ' . ($logo_size[1] / 2) . 'px;" height="' . ($logo_size[1] / 2) . '"';
		}

		$output .= '
		<div class="maintenance-logo-wrapper">
			<img src="' . $main_maintenance_settings['maintenance-logo'] . '" alt="logo" id="maintenance-logo" ' . $logo_size_html . '>
		</div>';
	}
	return $output;
}

function main_get_social( $main_maintenance_settings ) {
	$output = '';
	if ( $main_maintenance_settings['social-networks'] == 'true' ) {
	$output .= '
	<div class="maintenance-buttons">';
		if ( $main_maintenance_settings['social-github'] ) {
			$output .= '<a href="' . $main_maintenance_settings['social-github'] . '" class="social-icons mmicon-github" ' . ($main_maintenance_settings['social-target']=='new'?'target="_blank"':'') . '></a>';
		}
		if ( $main_maintenance_settings['social-dribbble'] ) {
			$output .= '<a href="' . $main_maintenance_settings['social-dribbble'] . '" class="social-icons mmicon-dribbble" ' . ($main_maintenance_settings['social-target']=='new'?'target="_blank"':'') . '></a>';
		}
		if ( $main_maintenance_settings['social-twitter'] ) {
			$output .= '<a href="' . $main_maintenance_settings['social-twitter'] . '" class="social-icons mmicon-twitter" ' . ($main_maintenance_settings['social-target']=='new'?'target="_blank"':'') . '></a>';
		}
		if ( $main_maintenance_settings['social-facebook'] ) {
			$output .= '<a href="' . $main_maintenance_settings['social-facebook'] . '" class="social-icons mmicon-facebook" ' . ($main_maintenance_settings['social-target']=='new'?'target="_blank"':'') . '></a>';
		}
		if ( $main_maintenance_settings['social-pinterest'] ) {
			$output .= '<a href="' . $main_maintenance_settings['social-pinterest'] . '" class="social-icons mmicon-pinterest-circled" ' . ($main_maintenance_settings['social-target']=='new'?'target="_blank"':'') . '></a>';
		}
		if ( $main_maintenance_settings['social-gplus'] ) {
			$output .= '<a href="' . $main_maintenance_settings['social-gplus'] . '" class="social-icons mmicon-gplus" ' . ($main_maintenance_settings['social-target']=='new'?'target="_blank"':'') . '></a>';
		}
		if ( $main_maintenance_settings['social-linkedin'] ) {
			$output .= '<a href="' . $main_maintenance_settings['social-linkedin'] . '" class="social-icons mmicon-linkedin-squared" ' . ($main_maintenance_settings['social-target']=='new'?'target="_blank"':'') . '></a>';
		}
	$output .= '</div>';
	}
	return $output;
}

function main_get_countdown( $main_maintenance_settings ) {
	$output = '';
	if ( isset($main_maintenance_settings['countdown']) && $main_maintenance_settings['countdown'] != '' ) {
		$output .= '<div id="main-clock"></div>';
	}
	return $output;
}

function main_get_mailchimp( $main_maintenance_settings ) {
	$main_maintenance_settings = (array)json_decode(get_option('main_maintenance_settings'));
	$output = '';

	if ( isset($main_maintenance_settings['our-subscribe']) && $main_maintenance_settings['our-subscribe'] == 'true' ) {
		$output .= '<div class="main-mailchimp-wrapper">';
		$output .= '
		<form class="mm-our-subscribe">
			<input type="email" class="mm-our-subscribe-email">
			<input type="hidden" class="mm-subscribe-action" value="add-subscriber">
			<button type="submit" class="mm-our-subscribe-submit">Subscribe</button>
		</form>
		<div class="mm-our-subscribe-info"></div>';
		if ( ( isset($main_maintenance_settings['recaptcha-key']) && $main_maintenance_settings['recaptcha-key'] != '' ) && ( isset($main_maintenance_settings['recaptcha-secret']) && $main_maintenance_settings['recaptcha-secret'] != '' ) ) {
			 $output .= '<div id="mm-subscribe-form-recaptcha"></div>';
		}
		$output .= '</div>';
	} else if ( isset($main_maintenance_settings['mailchimp']) && $main_maintenance_settings['mailchimp'] != '' ) {
		$output .= '<div class="main-mailchimp-wrapper">';
		$output .= $main_maintenance_settings['mailchimp'];
		if ( ( isset($main_maintenance_settings['recaptcha-key']) && $main_maintenance_settings['recaptcha-key'] != '' ) && ( isset($main_maintenance_settings['recaptcha-secret']) && $main_maintenance_settings['recaptcha-secret'] != '' ) ) {
			 $output .= '<div id="mm-subscribe-form-recaptcha"></div>';
		}
		$output .= '</div>';
	}
	return $output;
}

function main_prepare_html( $template, $settings ) {
	$output = str_replace('{main_maintenance_logo}', main_get_logo( $settings ), $template->template_html);
	$output = str_replace('{main_maintenance_countdown}', main_get_countdown( $settings ), $output);
	$output = str_replace('{main_maintenance_social}', main_get_social( $settings ), $output);
	$output = str_replace('{main_maintenance_mailchimp}', main_get_mailchimp( $settings ), $output);
	if ( function_exists('maintenancepro_contact_left') ) {
		$output = str_replace('{main_maintenance_contact_left}', maintenancepro_contact_left( $settings ), $output);
	} else {
		$output = str_replace('{main_maintenance_contact_left}', '', $output);
	}
	if ( function_exists('maintenancepro_contact_right') ) {
		$output = str_replace('{main_maintenance_contact_right}', maintenancepro_contact_right( $settings ), $output);
	} else {
		$output = str_replace('{main_maintenance_contact_right}', '', $output);
	}
	
	echo $output;
}