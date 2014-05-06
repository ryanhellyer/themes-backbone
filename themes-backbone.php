<?php
/*
Plugin Name: Themes Backbone
Plugin URI: http://geek.ryanhellyer.net/
Description: Backbone of the wp-admin/themes.php page
Author: Ryan Hellyer
Version: 0.1
Author URI: http://geek.ryanhellyer.net/

License: GPLv2

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/


/*
 * Set plugin folder URL
 */
define( 'THEMES_BACKBONE_URL',  );
define( 'THEMES_BACKBONE_ADMIN_URL', admin_url . 'admin.php?page=themes-backbone/themes-backbone.php' );


/**
 * End to End
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @since 1.0
 */
class Theme_Backbone {

	const plugin_dir_url( __FILE__ );

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
	}

	/*
	 * Set options for the blocks
	 *
	 * @return array  Settings for each of block
	 */
	public function get_blocks() {
		$blocks = array(
			0 => array(
				'id' => 'plugin-1',
				'name' => 'Plugin 1',
				'screenshot' => array(
					0 => 'http://uploads.ryanhellyer.net/ryan/2014/03/frogs-legs-680x382.jpg',
				),
				'description' => 'Some random plugin we iz gonna add!',
				'author' => 'Ryan the great',
				'authorAndUri' => '<a href="http://geek.ryanhellyer.net/" title="Ryan the great">Ryan the great</a>',
				'version' => '1.0',
				'tags' => 'Green, Red, White',
				'Parent' => '',
				'Active' => '',
				'hasUpdate' => '',
				'update' => '',
				'actions' => array(
					'activate' => THEMES_BACKBONE_ADMIN_URL . '&action=activate&_wpnonce=' . $nonce,
					'delete' => THEMES_BACKBONE_ADMIN_URL . '&action=delete&_wpnonce='. $nonce,
				),

			),
			1 => array(
				'id' => 'plugin-2',
				'name' => 'Plugin 2',
				'screenshot' => array(
					0 => 'http://uploads.ryanhellyer.net/ryan/2007/07/ryansarahjane2.jpg',
				),
				'description' => 'Plugin by a muppet',
				'author' => 'Remkus the muppet',
				'authorAndUri' => '<a href="http://remkusdevries.com/" title="A muppet">Remkus the muppet</a>',
				'version' => '1.2',
				'tags' => 'Black, Red, Pink',
				'Parent' => '',
				'Active' => '',
				'hasUpdate' => '',
				'update' => '',
				'actions' => array(
					'activate' => THEMES_BACKBONE_ADMIN_URL . '&action=activate&_wpnonce=' . $nonce,
					'delete' => THEMES_BACKBONE_ADMIN_URL . '&action=delete&_wpnonce='. $nonce,
				),

			),
		);

		return $blocks;
	}

	/*
	 * Initialise
	 */
	public function init() {

		// Bail out now, if not on correct admin page
		if ( ! isset( $_GET['page'] ) ) {
			return;
		}
		if ( 'themes-backbone/themes-backbone.php' != $_GET['page'] ) {
			return;
		}

		// Need to register script, for use with wp_localize_script
	 	wp_register_script(
			'ryans-theme',
			THEMES_BACKBONE_URL. 'backbone-blocks.js',
			array( 'wp-backbone' ),
			'1.0',
			true
		);

$nonce = 'sdffd';

		wp_localize_script( 'ryans-theme', '_wpThemeSettings', array(
			'themes'   => $this->get_blocks(),
			'settings' => array(
				'canInstall'    => ( ! is_multisite() && current_user_can( 'install_themes' ) ),
				'installURI'    => ( ! is_multisite() && current_user_can( 'install_themes' ) ) ? admin_url( 'theme-install.php' ) : null,
				'confirmDelete' => __( "Are you sure you want to delete this thing?\n\nClick 'Cancel' to go back, 'OK' to confirm the delete." ),
				'adminUrl'      => parse_url( admin_url(), PHP_URL_PATH ),
			),
		 	'l10n' => array(
		 		'search'  => __( 'Search Installed Things' ),
		 		'searchPlaceholder' => __( 'Search installed things...' ), // placeholder (no ellipsis)
		  	),
		) );

		add_thickbox();

		wp_enqueue_script( 'wp-util' ); // Originally included with 'theme'
		wp_enqueue_script( 'wp-backbone' ); // Originally included with 'theme'

		wp_enqueue_script( 'ryans-theme' );

		require_once( ABSPATH . 'wp-admin/admin-header.php' );
	}

	/*
	 * Add administration page
	 */
	function add_admin_page() {
		add_menu_page(
			__( 'Themes Backbone test', 'themes-backbone' ),
			__( 'Test', 'themes-backbone' ),
			'administrator',
			__FILE__,
			array( $this, 'page_content' ),
			''
		);
	}

	/*
	 * Add admin page contents
	 */
	public function page_content() {

		echo '
		<div class="wrap">
		<h2>Themes		<span class="theme-count">3</span>
		</h2>
			<h2>' . __( 'Stuff', 'themes-backbone' ) . '</h2>
			<div class="theme-browser"></div>
			<div class="theme-overlay"></div>
		';

		require( 'backbone-templates.html' );
		echo '</div>';

	}

}
new Theme_Backbone;
