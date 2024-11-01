<?php
/*
Part of wp-rdfa

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

header('Content-type: text/xml');
require('./../../../wp-blog-header.php');


function get_users($level) {
global $wpdb;
$aUsersID = $wpdb->get_col( $wpdb->prepare("SELECT * FROM $wpdb->users"));
#print_r($aUsersID);
foreach ( $aUsersID as $iUserID ) :
 $user = get_userdata( $iUserID );
 if($user->user_level == $level && $iUserID != 1) {
  echo '<foaf:knows rdf:resource="/author/'. $user->user_login .'"></foaf:knows>';
 }
/*
        10 admin
        7 editor
        2 author
        1 Contributor
          subscriber
  <foaf:knows rdf:resource="<?php bloginfo('url');?>/author/NAME"></foaf:knows> 
*/
endforeach;
}

function get_user_info($level) {
global $wpdb;
$aUsersID = $wpdb->get_col( $wpdb->prepare("SELECT * FROM $wpdb->users"));
#print_r($aUsersID);
foreach ( $aUsersID as $iUserID ) :
 $user = get_userdata( $iUserID );
 if($user->user_level == $level && $iUserID != 1) {
 
 $user_mail_sha1 = sha1($user->user_email);
 
 print	'<foaf:Person rdf:about="/author/'. $user->user_login .'">'.
	  '<foaf:mbox_sha1sum>'.$user_mail_sha1.'</foaf:mbox_sha1sum>'.
	  '<foaf:givenname>'.$user->first_name.'</foaf:givenname>'.
	  '<foaf:family_name>'.$user->last_name.'</foaf:family_name>'.
	  '<foaf:nick>'.$user->nickname.'</foaf:nick>'.
	  '<foaf:homepage>'.$user->user_url.'</foaf:homepage>'.
	'</foaf:Person>';

 }
endforeach;
}

function get_info() {
$user_info = get_userdata(1);
$email_sha1 = sha1($user_info->user_email);
$options = get_option('wprda_options');

print	'<rdf:RDF '.
		'xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" '.
		'xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" '.
		'xmlns:foaf="http://xmlns.com/foaf/0.1/" '.
		'xmlns:admin="http://webns.net/mvcb/"> '.
	'<foaf:PersonalProfileDocument rdf:about=""> '.
	 '<foaf:maker rdf:resource="/author/admin"></foaf:maker> '.
	 '<foaf:primaryTopic rdf:resource="../../../"></foaf:primaryTopic> '.
	'</foaf:PersonalProfileDocument> '.
	'<foaf:Person rdf:about="/author/admin"> '.
	 '<foaf:mbox_sha1sum>'.$email_sha1.'</foaf:mbox_sha1sum> '.
	 '<foaf:name>'.$user_info->first_name.' '.$user_info->last_name.'</foaf:name> '.
  	 '<foaf:givenname>'.$user_info->first_name.'</foaf:givenname> '.
  	 '<foaf:family_name>'.$user_info->last_name.'</foaf:family_name> '.
	 '<foaf:nick>'.$user_info->nickname.'</foaf:nick> '.
	 '<foaf:homepage>'.$user_info->user_url.'</foaf:homepage> ';


	if($options['foaf_know_admin'] == 'on') {
		get_users(10);  
	}
	if($options['foaf_know_editor'] == 'on') {
		get_users(7);  
	}
	if($options['foaf_know_author'] == 'on') {
		get_users(2);  
	}
	if($options['foaf_know_contributor'] == 'on') {
		get_users(1);  
	}
	if($options['foaf_know_subscriber'] == 'on') {
		get_users(NULL);  
	}
	

print	'</foaf:Person>';

	get_user_info(10);
	if($options['foaf_know_admin'] == 'on') {
		get_user_info(10);
	}
	if($options['foaf_know_editor'] == 'on') {
		get_user_info(7);
	}
	if($options['foaf_know_author'] == 'on') {
		get_user_info(2);
	}
	if($options['foaf_know_contributor'] == 'on') {
		get_user_info(1);
	}
	if($options['foaf_know_subscriber'] == 'on') {
		get_user_info(NULL);
	}


print	'</rdf:RDF>';

}

get_info();

?>
