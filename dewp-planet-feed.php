<?php
defined( 'ABSPATH' ) or die( 'You know better.' );
/**
 * Plugin Name:       DEWP Planet Feed (Beta)
 * Description:       Generates a custom feed “dewp-planet” for posts. Adds a checkbox to the Publish meta box in order to explicitly add a post to that custom feed.
 * Version:           0.5.0
 * Author:            dewp#planet team
 * Author URI:        https://dewp.slack.com/messages/planet/
 * Plugin URI:        https://github.com/deworg/dewp-planet-feed
 * GitHub Plugin URI: https://github.com/deworg/dewp-planet-feed
 * License:           GNU General Public License v3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Release Asset: true
 *
 * Based upon DS_wpGrafie_WP_Planet_Feed class by Dominik Schilling (@ocean90).
 * https://github.com/ocean90/wpgrafie-theme/blob/master/classes/class-ds-wpgrafie-wp-planet-feed.php
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

/**
 * Set marker for activation.
 * @since 0.1
 */
 register_activation_hook(
 	__FILE__,
 	array( 'DEWP_Planet_Feed', 'activation' )
 );

/**
 * Flush rewrite rules.
 * @since 0.1
 */
register_deactivation_hook(
	__FILE__,
	array( 'DEWP_Planet_Feed', 'deactivation' )
);

class DEWP_Planet_Feed {

	/**
	 * Allowed post types.
	 * @since 0.1
	 * @var array  Default: post
	 */
	public static $post_types;

	/**
	 * Required capability.
	 * @since 0.1
	 * @var string  Default: publish_posts
	 */
	public static $capability;

	/**
	 * Plugin activation state.
	 * @var bool|string FALSE|activating|activated
	 */
	public static $maybe_activation;

	/**
	 * Populate default values and initiate.
	 */
	public function __construct() {

		/**
		* Filterable post types.
		* @since 0.1
		*/
		self::$post_types = apply_filters(
			'wp_planet_feed__post_types',
			array( 'post' )
		);

		/**
		* Filterable capability to enable checkbox.
		* @since 0.1
		*/
		self::$capability = apply_filters(
			'wp_planet_feed__capability',
			'publish_posts'
		);

		self::$maybe_activation = get_option( 'wp_planet_feed__activated', false );

		add_action( 'init', array( __CLASS__, 'init' ) );

		// Register the meta.
		add_action( 'init', array( __CLASS__, 'register_show_in_feed_meta' ) );
	}

	/**
	 * Set marker for activation.
	 * @since  0.1
	 * @return void
	 */
	public static function activation() {
		update_option( 'wp_planet_feed__activated', 'activating' );
	}

	/**
	 * Flush rewrite rules and delete option on deactivation.
	 * @since  0.1
	 * @return void
	 */
	public static function deactivation() {
		flush_rewrite_rules();
		delete_option( 'wp_planet_feed__activated', 'deactivated' );
	}

	/**
	 * Initialize plugin.
	 * @since  0.1
	 * @return void
	 */
	public static function init() {

		// Add custom feed.
		add_feed( 'dewp-planet', array( __CLASS__, 'feed_template' ) );
		if ( 'activating' === self::$maybe_activation ) {
			// Not recommended, but it’s only once during activation.
			flush_rewrite_rules();
			update_option( 'wp_planet_feed__activated', 'activated' );
		}

		// Publish post actions.
		add_action( 'post_submitbox_misc_actions', array( __CLASS__, 'add_checkbox' ), 9 );
		add_action( 'save_post', array( __CLASS__, 'save_checkbox' ) );

		// Enqueue admin scripts and styles.
		add_action(
			'admin_enqueue_scripts',
			array( __CLASS__, 'admin_enqueue_scripts' )
		);

		// Enqueue Gutenberg script.
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_block_editor_assets' ) );

		// Get feed content.
		add_action( 'pre_get_posts', array( __CLASS__, 'feed_content' ) );
	}

	/**
	 * Register the meta data and make it visible via REST API.
	 * @since 0.5.0
	 */
	public static function register_show_in_feed_meta() {
		register_post_meta(
            'post',
            'wpf_show_in_dewp_planet_feed',
            [
                'type' => 'boolean',
                'description' => 'Ob der Beitrag im DEWP-Planet erscheinen soll oder nicht.',
                'single' => true,
                'show_in_rest' => true,
            ]
		);
	}

	/**
	 * Load feed template.
	 * @since  0.1
	 * @return void
	 */
	public static function feed_template() {
		load_template( ABSPATH . WPINC . '/feed-rss2.php' );
	}

	/**
	 * Add checkbox to Publish Post meta box.
	 * @since  0.1
	 * @return void
	 */
	public static function add_checkbox() {
		global $post;

		// Bail if post type is not allowed.
		if ( ! in_array( $post->post_type, self::$post_types ) ) {
			return false;
		}

		// Check user capability. Not bailing, though, on purpose.
		$maybe_enabled = current_user_can( self::$capability );

		// This actually defines whether post will be listed in our feed.
		$value = get_post_meta( $post->ID, '_wpf_show_in_dewp_planet_feed', true );

		ob_start();
		include trailingslashit( dirname( __FILE__ ) ) . 'inc/pub-section.php';
		$pub_section = ob_get_clean();

		echo $pub_section;
	}

	/**
	 * Register and enqueue admin scripts and styles.
	 * @since  0.3
	 * @param  string $hook Current admin page
	 * @return return       Current admin page
	 */
	public static function admin_enqueue_scripts( $hook ) {
		if ( 'post.php' !== $hook && 'post-new.php' !== $hook )
			return;

		$file_data  = get_file_data( __FILE__, array( 'v' => 'Version' ) );
		$assets_url = trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/';

		// CSS
		wp_register_style(
			'dewp-planet-post', $assets_url . 'css/post.css',
			array( 'wp-admin' ),
			$file_data['v']
		);
		wp_enqueue_style( 'dewp-planet-post' );
		return $hook;
	}

	/**
	 * Enqueue assets for the Gutenberg editor.
	 * @since  0.5.0
	 */
	public static function enqueue_block_editor_assets() {
		$file_data  = get_file_data( __FILE__, array( 'v' => 'Version' ) );
		$assets_url = trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/';
		wp_enqueue_script( 'dewp-planet-functions', $assets_url . 'js/functions.js', array( 'wp-components', 'wp-editor', 'wp-core-blocks', 'wp-nux', 'wp-edit-post' ), $file_data['v'] );
	}

	/**
	 * Save option value to post meta.
	 * @since  0.1
	 * @param  integer $post_id ID of current post
	 * @return integer          ID of current post
	 */
	public static function save_checkbox( $post_id ) {

		if ( empty( $post_id ) || empty( $_POST['post_ID'] ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		if ( absint( $_POST['post_ID'] ) !== $post_id ) {
			return $post_id;
		}
		if ( ! in_array( $_POST['post_type'], self::$post_types ) ) {
			return $post_id;
		}
		if ( ! current_user_can( self::$capability ) ) {
			return $post_id;
		}
		if ( empty( $_POST['dewp-planet__add-to-feed'] ) ) {
			delete_post_meta( $post_id, '_wpf_show_in_dewp_planet_feed' );
		} else {
			add_post_meta( $post_id, '_wpf_show_in_dewp_planet_feed', 1, true );
		}

		return $post_id;
	}

	/**
	 * Set feed content.
	 * @since  0.1
	 * @param  object $query WP_Query object
	 * @return object        Altered WP_Query object
	 */
	public static function feed_content( $query ) {

		// Bail if $posts_query is not an object or of incorrect class.
		if ( ! is_object( $query ) || ( 'WP_Query' !== get_class( $query ) ) ) {
			return;
		}
		// Bail if filters are suppressed on this query.
		if ( $query->get( 'suppress_filters' ) ) {
			return;
		}
		// Bail if this is not the Shire.
		if ( ! $query->is_feed( 'dewp-planet' ) ) {
			return;
		}

		$query->set( 'post_type', self::$post_types );

		$meta_query = array(
			'relation' => 'OR',
			array(
				'key'     => 'wpf_show_in_dewp_planet_feed',
				'value'   => 1,
			),
			array(
				'key'     => '_wpf_show_in_dewp_planet_feed',
				'value'   => 1,
			),
		);

		$query->set( 'meta_query', $meta_query );

		return $query;
	}
}

// Hallo!
new DEWP_Planet_Feed();
