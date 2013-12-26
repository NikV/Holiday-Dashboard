<?php

/*
 Plugin Name: Holiday Dashboard
 Description: A plugin that reminds you of the holidays every time you log into WordPress.
 Author: Nikhil Vimal
 License: GPL2 
 */

 /*  Copyright 2013  Nikhil_Vimal  (email : techvoltz@gmail.com)

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


new holiday_dash();

class holiday_dash {
    
    public function __construct() {
        
	
	
        
        add_action( 'admin_notices', array($this, 'holiday_dash_message') );
        
        add_action( 'admin_head', array($this, 'holiday_dash_message_css') );
        
        add_filter('user_contactmethods', array($this,'add_contact_fields'));
	
	add_action('init',  array($this, 'add_santa_role'));
	
	register_deactivation_hook( __FILE__, array($this, 'holiday_dash_deactivate' ));
	
	add_action('init', array($this, 'add_santa_caps'));
	
	add_action('init', array($this, 'presents_post_type'));
	
	add_action('admin_menu', array($this, 'remove_presents_cpt_menu'));
	
	add_action('wp_dashboard_setup', array($this, 'holiday_dash_dashboard_widget'));
        
       
        
        
    }
    
    


    


    public function add_santa_role() {
    add_role('santa', 'Santa', array(
        'read' => true,
        'edit_posts' => true,
        
        
        ));
    
    
}

    public function add_santa_caps() {
	
	$role = get_role('santa');
	$role->add_cap('edit_presents');
    
}



  public function holiday_dash_message() {
	
	$message = "Happy Holidays! ";
        echo "<p id='dolly'>$message</p>";

  }
// We need some CSS to position the paragraph
  public function holiday_dash_message_css() {
	// This makes sure that the positioning is also good for right-to-left languages
	$x = is_rtl() ? 'left' : 'right';

	echo "
	<style type='text/css'>
	#dolly {
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;		
		margin: 0;
		font-size: 11px;
                color: #FF0000;
	}
	</style>
	";
}



    public function add_contact_fields($profile_fields) {
	// Adding fields
	$profile_fields['cookies'] = 'How many Cookies have you eaten this holiday season?';
	

	return $profile_fields;
}


    


//Add the presents post type easter egg
  public function presents_post_type(){
    
    $presents_args = array(
        'public' => true,
        'query_var' => 'presents',
        
        'register_meta_box_cb' => 'add_congrats_metabox',
        'show_in_admin_bar' => true,
        'supports' => array(
            'title',
            'thumbnail',
            'editor',
        ),
        'labels' => array(
            'name' => 'Presents',
            'singular_name' => 'Present',
            'add_new' => 'Add New Present',
            'add_new_item' => 'Add New Present',
            'edit_item' => 'Edit Present',
            'new_item' => 'New Present',
            'view_item' => 'View Present',
            'search_items' => 'Search Present',
            'not_found' => 'No Presents Found',
            'not_found_in_trash' => 'No Presents Found In The Trash',
            
        ),
        
    );
    
    $presents_capabilities = array(
        'edit_posts' => 'edit_present',
        'read_post' => 'read_present',
        
    );
    
    register_post_type('presents', $presents_args, $presents_capabilities);
}

    public function remove_presents_cpt_menu() {
    if( !current_user_can('santa')) :
    
    remove_menu_page('edit.php?post_type=presents');
    endif;
    
}

    //Adds a Dashboard Widget
    public function holiday_dash_dashboard_widget() {
	wp_add_dashboard_widget (
	    'holiday_dash_widget',
	    'Happy Hoidays!',
	    'holiday_dash_dashboard_widget_function'
	);
	
	function holiday_dash_dashboard_widget_function() {
	echo "Happy Holidays! Can you find the other hidden secrets in the dashboard?";
    }
	
    }
    
    
 

}


    
    
 
 
