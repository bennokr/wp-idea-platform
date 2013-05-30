<?php
/*
Plugin Name: Idea Platform
Plugin URI: http://github.com/bennokr/idea-platform
Description: Project idea sharing platform.
Author: Benno Kruit
Author URI: http://github.com/bennokr/
Text Domain: wpidpl
Domain Path: /languages/
Version: 0.0.1
*/

/*  Copyright 2013 Benno Kruit (email: bennokr at gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define( 'WPIDPL_VERSION', '0.0.1' );

define( 'WPIDPL_REQUIRED_WP_VERSION', '3.3' );

if ( ! defined( 'WPIDPL_PLUGIN_BASENAME' ) )
	define( 'WPIDPL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'WPIDPL_PLUGIN_NAME' ) )
	define( 'WPIDPL_PLUGIN_NAME', trim( dirname( WPIDPL_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'WPIDPL_PLUGIN_DIR' ) )
	define( 'WPIDPL_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );

if ( ! defined( 'WPIDPL_PLUGIN_URL' ) )
	define( 'WPIDPL_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

if ( ! defined( 'WPIDPL_PLUGIN_MODULES_DIR' ) )
	define( 'WPIDPL_PLUGIN_MODULES_DIR', WPIDPL_PLUGIN_DIR . '/modules' );

if ( ! defined( 'WPIDPL_LOAD_JS' ) )
	define( 'WPIDPL_LOAD_JS', true );

if ( ! defined( 'WPIDPL_LOAD_CSS' ) )
	define( 'WPIDPL_LOAD_CSS', true );

if ( ! defined( 'WPIDPL_AUTOP' ) )
	define( 'WPIDPL_AUTOP', true );

if ( ! defined( 'WPIDPL_USE_PIPE' ) )
	define( 'WPIDPL_USE_PIPE', true );

if ( ! defined( 'WPIDPL_ADMIN_READ_CAPABILITY' ) )
	define( 'WPIDPL_ADMIN_READ_CAPABILITY', 'edit_posts' );

if ( ! defined( 'WPIDPL_ADMIN_READ_WRITE_CAPABILITY' ) )
	define( 'WPIDPL_ADMIN_READ_WRITE_CAPABILITY', 'publish_pages' );

if ( ! defined( 'WPIDPL_VERIFY_NONCE' ) )
	define( 'WPIDPL_VERIFY_NONCE', true );

require_once WPIDPL_PLUGIN_DIR . '/settings.php';

?>