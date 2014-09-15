<?php
/*
Plugin Name: COR Code Replacer
Description: A lightweight plugin to replace code
Version: 1.0
Author: Lars Kroll
Author URI: http://www.lars-kroll.de
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Copyright 2014 Lars Kroll  (http://www.lars-kroll.de/)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package HR Replacer
 * @copyright 2014 Lars Kroll
 */

/**
 * Define plugin constants.
 */
if (!defined('COR_FILTER_PRIORITY'))
	define('COR_FILTER_PRIORITY', 1000);

/**
 * Register filters to replace <hr /> tags in
 * posts, pages, excerpts, widgets.
 */
foreach (array('the_content', 'the_excerpt', 'widget_text') as $filter) {
	add_filter($filter, 'COR_replace_hr', COR_FILTER_PRIORITY);
}

/**
 * Simply replace '<hr />' with site code.
 *
 * @param string $string Text from wp
 * @return string $string with replaced tag
 */

function COR_replace_hr($string) {

	$source_code = esc_attr( get_option('cor_source_code') );
	$dest_code = esc_attr( get_option('cor_dest_code') );

	// abort if $string doesn't contain a @-sign
	if (strpos($string, $source_code) === false) return $string;

	$string = str_replace($source_code,$dest_code,$string);

	return $string;
}

?>
<?php
// create custom plugin settings menu
add_action('admin_menu', 'cor_create_menu');

function cor_create_menu() {

	//create new top-level menu
	add_menu_page('COR Plugin Settings', 'COR', 'administrator', __FILE__, 'cor_settings_page',plugins_url('/images/icon.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	//register our settings
	register_setting( 'cor-settings-group', 'cor_source_code' );
	register_setting( 'cor-settings-group', 'cor_dest_code' );
}

function cor_settings_page() {
?>
<div class="wrap">
<h2>COR - Code replacer</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'cor-settings-group' ); ?>
    <?php do_settings_sections( 'cor-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Code to be replaced</th>
        <td><input type="text" name="cor_source_code" value="<?php echo esc_attr( get_option('cor_source_code') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Replace code</th>
        <td><input type="text" name="cor_dest_code" style="width:90%;" value="<?php echo esc_attr( get_option('cor_dest_code') ); ?>" /></td>
        </tr>

    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>