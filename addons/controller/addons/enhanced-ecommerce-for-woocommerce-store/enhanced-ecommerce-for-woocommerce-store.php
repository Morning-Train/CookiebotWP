<?php

	namespace cookiebot_addons\controller\addons\enhanced_ecommerce_for_woocommerce_store;

	use cookiebot_addons\controller\addons\Cookiebot_Addons_Interface;
	use cookiebot_addons\lib\Cookie_Consent_Interface;
	use cookiebot_addons\lib\Settings_Service_Interface;
	use cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
	use cookiebot_addons\lib\buffer\Buffer_Output_Interface;

	class Enhanced_Ecommerce_For_WooCommerce_Store implements Cookiebot_Addons_Interface {

		/**
		 * @var Settings_Service_Interface
		 *
		 * @since 1.3.0
		 */
		protected $settings;

		/**
		 * @var Script_Loader_Tag_Interface
		 *
		 * @since 1.3.0
		 */
		protected $script_loader_tag;

		/**
		 * @var Cookie_Consent_Interface
		 *
		 * @since 1.3.0
		 */
		public $cookie_consent;

		/**
		 * @var Buffer_Output_Interface
		 *
		 * @since 1.3.0
		 */
		protected $buffer_output;

		/**
		 * Jetpack constructor.
		 *
		 * @param $settings          Settings_Service_Interface
		 * @param $script_loader_tag Script_Loader_Tag_Interface
		 * @param $cookie_consent    Cookie_Consent_Interface
		 * @param $buffer_output     Buffer_Output_Interface
		 *
		 * @since 1.3.0
		 */
		public function __construct( Settings_Service_Interface $settings, Script_Loader_Tag_Interface $script_loader_tag, Cookie_Consent_Interface $cookie_consent, Buffer_Output_Interface $buffer_output ) {
			$this->settings          = $settings;
			$this->script_loader_tag = $script_loader_tag;
			$this->cookie_consent    = $cookie_consent;
			$this->buffer_output     = $buffer_output;
		}

		/**
		 * Loads addon configuration
		 *
		 * @since 1.3.0
		 */
		public function load_configuration() {
			add_action( 'wp_loaded', array( $this, 'cookiebot_addon_enhanced_ecommerce_for_woocommerce_store' ), 5 );
		}

		/**
		 * Disable scripts if state not accepted
		 *
		 * @since 1.3.0
		 */
		public function cookiebot_addon_enhanced_ecommerce_for_woocommerce_store() {
			cookiebot_addons_remove_class_action('woocommerce_after_checkout_billing_form', 'Enhanced_Ecommerce_Google_Analytics_Public', 'checkout_step_3_tracking', 10);
			cookiebot_addons_remove_class_action('woocommerce_after_add_to_cart_button', 'Enhanced_Ecommerce_Google_Analytics_Public', 'add_to_cart', 10);
			cookiebot_addons_remove_class_action('wp_head', 'Enhanced_Ecommerce_Google_Analytics_Public', 'add_dev_id', 10);
			cookiebot_addons_remove_class_action('wp_footer', 'Enhanced_Ecommerce_Google_Analytics_Public', 'tvc_store_meta_data', 10);
			cookiebot_addons_remove_class_action('woocommerce_thankyou', 'Enhanced_Ecommerce_Google_Analytics_Public', 'ecommerce_tracking_code', 10);
			cookiebot_addons_remove_class_action('wp_head', 'Enhanced_Ecommerce_Google_Analytics_Public', 'ee_settings', 10);
			cookiebot_addons_remove_class_action('wp_footer', 'Enhanced_Ecommerce_Google_Analytics_Public', 't_products_impre_clicks', 10);
			cookiebot_addons_remove_class_action('woocommerce_after_shop_loop_item', 'Enhanced_Ecommerce_Google_Analytics_Public', 'bind_product_metadata', 10);
			cookiebot_addons_remove_class_action('woocommerce_after_single_product', 'Enhanced_Ecommerce_Google_Analytics_Public', 'product_detail_view', 10);
			cookiebot_addons_remove_class_action('woocommerce_after_cart', 'Enhanced_Ecommerce_Google_Analytics_Public', 'remove_cart_tracking', 10);
		}

		/**
		 * Return addon/plugin name
		 *
		 * @return string
		 *
		 * @since 1.3.0
		 */
		public function get_addon_name() {
			return 'Enhanced Ecommerce Google Analytics Plugin for WooCommerce';
		}

		/**
		 * Default placeholder content
		 *
		 * @return string
		 *
		 * @since 1.8.0
		 */
		public function get_default_placeholder() {
			return 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Social Share buttons.';
		}

		/**
		 * Get placeholder content
		 *
		 * This function will check following features:
		 * - Current language
		 *
		 * @param $src
		 *
		 * @return bool|mixed
		 *
		 * @since 1.8.0
		 */
		public function get_placeholder( $src = '' ) {
			return $this->settings->get_placeholder( $this->get_option_name(), $this->get_default_placeholder(), cookiebot_addons_output_cookie_types( $this->get_cookie_types() ), $src );
		}

		/**
		 * Option name in the database
		 *
		 * @return string
		 *
		 * @since 1.3.0
		 */
		public function get_option_name() {
			return 'enhanced_ecommerce_for_woocommerce_store';
		}

		/**
		 * Plugin file name
		 *
		 * @return string
		 *
		 * @since 1.3.0
		 */
		public function get_plugin_file() {
			return 'enhanced-e-commerce-for-woocommerce-store/enhanced-ecommerce-google-analytics.php';
		}

		/**
		 * Returns checked cookie types
		 * @return mixed
		 *
		 * @since 1.3.0
		 */
		public function get_cookie_types() {
			return $this->settings->get_cookie_types( $this->get_option_name(), $this->get_default_cookie_types() );
		}

		/**
		 * Returns default cookie types
		 * @return array
		 *
		 * @since 1.5.0
		 */
		public function get_default_cookie_types() {
			return array('statistics');
		}

		/**
		 * Check if plugin is activated and checked in the backend
		 *
		 * @since 1.3.0
		 */
		public function is_addon_enabled() {
			return $this->settings->is_addon_enabled( $this->get_option_name() );
		}

		/**
		 * Checks if addon is installed
		 *
		 * @since 1.3.0
		 */
		public function is_addon_installed() {
			return $this->settings->is_addon_installed( $this->get_plugin_file() );
		}

		/**
		 * Checks if addon is activated
		 *
		 * @since 1.3.0
		 */
		public function is_addon_activated() {
			return $this->settings->is_addon_activated( $this->get_plugin_file() );
		}

		/**
		 * Retrieves current installed version of the addon
		 *
		 * @return bool
		 *
		 * @since 2.2.1
		 */
		public function get_addon_version() {
			return $this->settings->get_addon_version( $this->get_plugin_file() );
		}

		/**
		 * Checks if it does have custom placeholder content
		 *
		 * @return mixed
		 *
		 * @since 1.8.0
		 */
		public function has_placeholder() {
			return $this->settings->has_placeholder( $this->get_option_name() );
		}

		/**
		 * returns all placeholder contents
		 *
		 * @return mixed
		 *
		 * @since 1.8.0
		 */
		public function get_placeholders() {
			return $this->settings->get_placeholders( $this->get_option_name() );
		}

		/**
		 * Return true if the placeholder is enabled
		 *
		 * @return mixed
		 *
		 * @since 1.8.0
		 */
		public function is_placeholder_enabled() {
			return $this->settings->is_placeholder_enabled( $this->get_option_name() );
		}

		/**
		 * Adds extra information under the label
		 *
		 * @return string
		 *
		 * @since 1.8.0
		 */
		public function get_extra_information() {
			return '<p>' . __( 'Blocks enhanced e-commerce for WooCommerce store', 'cookiebot-addons' ) . '</p>';
		}

		/**
		 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
		 *
		 * @return string
		 *
		 * @since 1.8.0
		 */
		public function get_svn_url() {
			return 'https://plugins.svn.wordpress.org/enhanced-e-commerce-for-woocommerce-store/trunk/enhanced-ecommerce-google-analytics.php';
		}

		/**
		 * Placeholder helper overlay in the settings page.
		 *
		 * @return string
		 *
		 * @since 1.8.0
		 */
		public function get_placeholder_helper() {
			return '<p>Merge tags you can use in the placeholder text:</p><ul><li>%cookie_types - Lists required cookie types</li><li>[renew_consent]text[/renew_consent] - link to display cookie settings in frontend</li></ul>';
		}


		/**
		 * Returns parent class or false
		 *
		 * @return string|bool
		 *
		 * @since 2.1.3
		 */
		public function get_parent_class() {
			return get_parent_class( $this );
		}

		/**
		 * Action after enabling the addon on the settings page
		 *
		 * @since 2.2.0
		 */
		public function post_hook_after_enabling() {
			//do nothing
		}

		/**
		 * Cookiebot plugin is deactivated
		 *
		 * @since 2.2.0
		 */
		public function plugin_deactivated() {
			//do nothing
		}

		/**
		 * @return mixed
		 *
		 * @since 2.4.5
		 */
		public function extra_available_addon_option() {
			//do nothing
		}

		/**
		 * Returns boolean to enable/disable plugin by default
		 *
		 * @return bool
		 *
		 * @since 3.6.3
		 */
		public function enable_by_default() {
			return false;
		}

		/**
		 * Sets default settings for this addon
		 *
		 * @return array
		 *
		 * @since 3.6.3
		 */
		public function get_default_enable_setting() {
			return array(
				'enabled'     => 1,
				'cookie_type' => $this->get_default_cookie_types(),
				'placeholder' => $this->get_default_placeholder(),
			);
		}
	}
