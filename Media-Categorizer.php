<?php
/*
Plugin Name: Media-Categorizer
Author: April Marshall
Description: Categorize your media into folders by Year and month
Author URI: https://www.aprilagain.com

{Media-Categorizer} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
{Media-Categorizer} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Media-Categorizer}. If not, see {License URI}.
*/

/*
Adding top level menu
*/
function add_my_custom_menu() {
    //add an item to the menu
    add_menu_page (
        'My Page',
        'MediaCategorizer',
        'manage_options',
        'Media-Categorizer/Media-Categorizer-form.php',
        '',
        plugin_dir_url( __FILE__ ).'icons/icon.png',
        '23.56'
    );
}

add_action( 'admin_menu', 'add_my_custom_menu' ); 

function disable_srcset( $sources ) {
	return false;
}

/*This would only need if images are not properly displayed due srcset errors*/
//add_filter( 'wp_calculate_image_srcset', 'disable_srcset' );
