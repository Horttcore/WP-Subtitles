<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package  WordPress-Subtitle
 * @author   Ralf Hortt
 * @license  GPL-2.0+
 * @link     htt://horttcore.de
 * @version  1.1
 * @since    1.1
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit;

// Delete all subtitles
$wpdb->query( "DELETE FROM " . $wpdb->postmeta . " WHERE meta_key = '_subtitle'" );