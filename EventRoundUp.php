<?php
/*
Plugin Name: Event Calendar RoundUp
Plugin URI: http://v2swebdesign/projects/eventroundup
Description: Creates a post from your google calendar feed
Version: 0.1
Author: James Strocel
Author URI: http://www.v2swebdesign.com
*/

/*  Copyright 2012 James Strocel (email : James@v2swebdesign.com)

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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function evcru_create_menu() {

  //create new top-level menu
  add_options_page('Event Calendar Round Up', 'Event Calendar Round Up', 'manage_options', __FILE__, 'evcru_settings_page');
  add_filter( "plugin_action_links", "evcru_settings_link", 10, 2 );
  //call register settings function
  add_action( 'admin_init', 'evcru_register_settings' );
}

//add settings link to plugins list
function evcru_settings_link($links, $file) {
  static $this_plugin;
  if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
  if ($file == $this_plugin){
    $settings_link = '<a href="options-general.php?page=EventRoundUp/EventRoundUp.php">'.__("Settings", "EventRoundUp").'</a>';
    array_unshift($links, $settings_link);
  }
  return $links;
  }


?>