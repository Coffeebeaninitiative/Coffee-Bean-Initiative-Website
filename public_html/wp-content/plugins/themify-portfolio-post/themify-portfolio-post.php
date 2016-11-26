<?php
/*
Plugin Name:  Themify Portfolio Post
Plugin URI:   https://themify.me
Version:      1.0.4
Author:       Themify
Description:  This plugin will add Portfolio post type to use with Themify themes.
Text Domain:  themify-portfolio-post
Domain Path:  /languages


/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 */

defined( 'ABSPATH' ) or die;

function themify_portfolio_post_setup() {
	global $themify_portfolio_posts;

	$data = get_file_data( __FILE__, array( 'Version' ) );
	if( ! defined( 'THEMIFY_PORTFOLIO_POST_DIR' ) )
		define( 'THEMIFY_PORTFOLIO_POST_DIR', plugin_dir_path( __FILE__ ) );

	if( ! defined( 'THEMIFY_PORTFOLIO_POST_URI' ) )
		define( 'THEMIFY_PORTFOLIO_POST_URI', plugin_dir_url( __FILE__ ) );

	if( ! defined( 'THEMIFY_PORTFOLIO_POST_VERSION' ) )
		define( 'THEMIFY_PORTFOLIO_POST_VERSION', $data[0] );

	if( ! defined( 'THEMIFY_PORTFOLIO_POSTS_COMPAT_MODE' ) )
		define( 'THEMIFY_PORTFOLIO_POSTS_COMPAT_MODE', false );

	include THEMIFY_PORTFOLIO_POST_DIR . 'includes/system.php';

	$themify_portfolio_posts = new Themify_Portfolio_Post( array(
		'url' => THEMIFY_PORTFOLIO_POST_URI,
		'dir' => THEMIFY_PORTFOLIO_POST_DIR,
		'version' => THEMIFY_PORTFOLIO_POST_VERSION
	) );
}
add_action( 'after_setup_theme', 'themify_portfolio_post_setup', 20 );

/**
 * Plugin activation hook
 * Flush rewrite rules after custom post type has been registered
 */
function themify_portfolio_posts_activation() {
	add_action( 'init', 'flush_rewrite_rules', 100 );
}
register_activation_hook( __FILE__, 'themify_portfolio_posts_activation' );

/**
 * Load updater
 *
 * @since 1.0.2
 */
function themify_portfolio_post_load_updater() {
	if ( is_admin() && current_user_can( 'update_plugins' ) ) {
		if ( ! function_exists( 'get_plugin_data') ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_basename = plugin_basename( __FILE__ );
		$plugin_data = get_plugin_data( plugin_dir_path( __FILE__ ) . basename( $plugin_basename ) );
		if ( ! class_exists( 'Themify_Plugin_Updater' ) ) {
			include_once plugin_dir_path( __FILE__ ) . 'updater/class-themify-plugin-updater.php';
		}
		new Themify_Plugin_Updater( array(
			'base_name'   => $plugin_basename,
			'plugin_data' => $plugin_data,
			'base_path'   => plugin_dir_path( __FILE__ ),
			'base_url'    => plugin_dir_url( __FILE__ ),
			'admin_page'  => 'themify-portfolio-posts',
		), $plugin_data['Version'] );
	}
}
add_action( 'init', 'themify_portfolio_post_load_updater' );
