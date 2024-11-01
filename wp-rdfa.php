<?php
/*
Plugin Name: wp-rdfa
Plugin URI: http://dev.squarecows.com/projects/wp-rdfa
Description: Add RDFa to wordpress.
Version: 0.3
Author: Richard Harvey
Author URI: http://dev.squarecows.com

Copyright 2009 Richard Harvey  (email : richard@squarecows.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

# Check wordpress version and compatability

global $wp_version;
$required_ver="2.7";
$exit_msg='This plugin requires Wordpress '.$required_ver.' or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please Check for an upgrade</a>';

if (version_compare($wp_version,$required_ver,"<")) {
	exit ($exit_msg);
}

if(!class_exists('wprdfa') ) :
class wprdfa {
	var $plugin_url;
	var $db_option = 'wprda_options';

function wprdfa() {
	$this->plugin_url = trailingslashit( WP_PLUGIN_URL.'/'.dirname( plugin_basename(__FILE__)));
 add_action('admin_menu', array(&$this, 'admin_menu'));	

# Insert FOAF header
 $options = $this->get_options();
 if($options['foaf']=='on'){
	add_action('wp_head', array(&$this, 'insert_foaf_meta_tag'),'3');
 }

# This little hack stops the admin pages having <span .... in the title text 
 if($options['dc_elements']=='on'){
  add_action('wp_head', array(&$this, 'insert_dc_header'),'3');
  $admin_page = is_admin();
  if($admin_page != "true") {
	 add_filter('the_title', array(&$this, 'extend_the_title')); 
	 add_filter('the_author', array(&$this, 'extend_the_author')); 
  }
 }
}

# handle plugin options

function get_options() {
	# defaults
	$options = array (
		'foaf' => 'on',
		'foaf_know_admin' => 'on',
		'foaf_know_editor' => 'on',
		'foaf_know_author' => 'on',
		'foaf_know_contributor' => '',
		'foaf_know_subscriber' => '',
		'dc_elements' => ''
	);

	# get saved options
	$saved = get_option($this->db_option);

	# assign vars
	if (!empty($saved)) {
		foreach($saved as $key => $option)
		$options[$key] = $option;
	}

	# allow updates of var
	if ($saved != $options) {
		update_option($this->db_option, $options);
	}
	return $options;
}
	
function install() {
	$this->get_options();
}

function handle_options() {
	$options = $this->get_options();
	if(isset($_POST['submitted']) ) {
		check_admin_referer('wprdfa-nonce');
		$options= array();
		$options['foaf']= $_POST['foaf'];
		$options['layout']= (int) $_POST['layout'];
		$options['foaf_know_admin']= $_POST['foaf_know_admin'];
		$options['foaf_know_editor']= $_POST['foaf_know_editor'];
		$options['foaf_know_author']= $_POST['foaf_know_author'];
		$options['foaf_know_contributor']= $_POST['foaf_know_contributor'];
		$options['foaf_know_subscriber']= $_POST['foaf_know_subscriber'];
		$options['dc_elements']= $_POST['dc_elements'];

		update_option($this->db_option, $options);
		echo '<div class="updated fade"><p>Settings Saved.</p></div>';
	}
	$layout=$options['layout'];
	$foaf=$options['foaf']=='on'?'checked':'';
        $foaf_know_admin=$options['foaf_know_admin']=='on'?'checked':'';
        $foaf_know_editor=$options['foaf_know_editor']=='on'?'checked':'';
        $foaf_know_author=$options['foaf_know_author']=='on'?'checked':'';
        $foaf_know_contributor=$options['foaf_know_contributor']=='on'?'checked':'';
        $foaf_know_subscriber=$options['foaf_know_subscriber']=='on'?'checked':'';
	$dc_elements=$options['dc_elements']=='on'?'checked':'';

	# submit URL same as this page
	$action_url = $_SERVER['REQUEST_URI'];
	include('wp-rdfa-options.php');

}

function admin_menu() {
	add_options_page('wp-RDFa options', 'wp-rdfa', 8, basename(__FILE__), array(&$this, 'handle_options'));
}

# Let the world know about your FOAF file (generated foaf.php)

function insert_foaf_meta_tag() {
	echo '<link rel="meta" href="./wp-content/plugins/wp-rdfa/foaf.php" type="application/rdf+xml" title="FOAF"/>';
}


##### Dublin Core Intergration #####

function insert_dc_header() {
	echo '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dc="http://purl.org/dc/elements/1.1/">';

}

function extend_the_title($title = '') {
	global $post;
	$about = get_permalink($post->ID);
	if(in_the_loop() == 'true') {
		$tag_date = $this->extend_the_date();
		return $tag_date.'<span rel="'.$about.'" property="dc:title" resource="'.$about.'">'.$title.'</span>';
	}
	else {
		return $title;
	}
}


function extend_the_date() {
	global $post;
	$about = get_permalink($post->ID);
	if(in_the_loop() == 'true') {
		return '<span property="dc:date" content="'.$post->post_date.'" resource="'.$about.'" />';
	}
        else {
                return $author;
        }

}

function extend_the_author($author = '') {
	global $post;
	$about = get_permalink($post->ID);
	if(in_the_loop() == 'true') {
		return '<span property="dc:creator" resource="'.$about.'">'.$author.'</span>';
	}
        else {
                return $author;
        }
}


# end class
}

else :
	exit ("Class wprdfa already declared!");
endif;

$wprdfa = new wprdfa();

if(isset($wprdfa)) {
	register_activation_hook( __FILE__, array(&$wprdfa, 'install') );

}

?>
