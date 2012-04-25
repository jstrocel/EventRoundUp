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


add_action('admin_menu', 'evcru_create_menu');

function evcru_create_menu() {

  //create new top-level menu
  add_options_page('Event Calendar Round Up', 'Event Calendar Round Up', 'manage_options', __FILE__, 'evcru_settings_page');
  add_filter( "plugin_action_links", "evcru_settings_link", 10, 2 );
  //call register settings function
  add_action( 'admin_init', 'evcru_register_settings' );
}



#register_activation_hook(__FILE__, 'my_activation');
add_action('wp', 'my_activation');
add_action('my_hourly_event', 'do_this_hourly');

add_filter( 'cron_schedules', 'cron_add_seconds' );
 
 function cron_add_seconds( $schedules ) {
 	// Adds once weekly to the existing schedules.
 	$schedules['seconds'] = array(
 		'interval' => 30,
 		'display' => __( 'seconds' )
 	);
 	return $schedules;
 }



function my_activation() {
  $activated = get_option('evcru_activated');
  if ($activated == true){
    if ( !wp_next_scheduled( 'my_hourly_event' ) ) {
	    wp_schedule_event( current_time( 'timestamp' ), 'seconds', 'my_hourly_event');
    }
  }
}

register_deactivation_hook(__FILE__, 'my_deactivation');

function my_deactivation() {
	wp_clear_scheduled_hook('my_hourly_event');
}


function do_this_hourly() {
	// do something every hour
  // Create post object
    $my_post = array(
       'post_title' => 'My post',
       'post_content' => 'This is my post.'.current_time( 'timestamp' ),
       'post_status' => 'publish',
       'post_author' => 1,
       'post_category' => array(8,39)
    );
  // Insert the post into the database
    wp_insert_post( $my_post );
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
  
  function evcru_register_settings() {
    //register our settings
    register_setting( 'EventRoundUp', 'evcru_calendar_url' );
    register_setting( 'EventRoundUp', 'evcru_interval' );
    register_setting( 'EventRoundUp', 'evcru_day' );	
    register_setting( 'EventRoundUp', 'evcru_activated' );
  }
  
  function evcru_admin_css() { ?>
  <style type="text/css"  >

  .evcru_social_list {padding-top:15px;}
  .evcru_social_list .setting {display:block;padding:1em;}
  .evcru_social_list .setting p.label_title {font-size:12px;font-weight:bold;display:block;margin-bottom:5px;}
  .evcru_social_list .setting label.no_bold {font-weight:normal;}
  .evcru_social_list .setting label span.slim {width:200px;float:left;display:block;margin: 1px;padding: 3px;}
  .evcru_social_list .setting p.desc {font-size:10px;font-style:italic;text-indent:10px; text-align:left;}
  </style>
  <?php }
  add_action('admin_head', 'evcru_admin_css');
  
  //html for settings form
  function evcru_settings_page() { ?>
    
<?php    


if ( isset($_POST['submit']) ) {

if ( isset( $_POST['evcru_activated'] ) )
			update_option( 'evcru_activated', 'true' );
		else
			update_option( 'evcru_activated', 'false' );
			
			}?>

  <div class="wrap evcru_social_list">
    <h2>Event Calendar Round Up</h2>

    <form method="post" action="options.php">
      <?php settings_fields( 'EventRoundUp' ); ?>


          <div class="setting">

          <p class="label_title">Where's the Calendar?'</p>
          <p><label class="no_bold" for="evcru_calendar_url"><span class="slim">Calendar URL</span>
          <input name="evcru_calendar_url" type="text" id="evcru_facebook" value="<?php form_option('evcru_calendar_url'); ?>" /></label></p>
          
          <p class="label_title">How Often?</p>
          <p><label class="no_bold" for="evcru_interval"><span class="slim">Interval</span>
          <input name="evcru_interval" type="text" id="evcru_interval" value="<?php form_option('evcru_interval'); ?>" /></label></p>
        

          <p class="label_title">What Day?</p>
          <p><label class="no_bold" for="evcru_day"><span class="slim">Day of Post</span>
          <input name="evcru_day" type="text" id="evcru_day" value="<?php form_option('evcru_day'); ?>" /></label></p>
          
          <p class="label_title">Activate?</p>
          <p><label class="no_bold" for="evcru_activated"><span class="slim">Activated?</span>
            <input name="evcru_activated" type="checkbox" value="1" <?php checked( '1', get_option( 'evcru_activated' ) ); ?> />
          
      
          <p class="setting">
          <input type="submit" class="button-primary" value="Save Settings" />
          </p>

          </div>

    </form>

  </div>

  <?php }
  

?>