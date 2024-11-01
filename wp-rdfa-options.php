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
?>
<div class="wrap" style="max-width:950px !important;">
 <h2>wp-RDFa</h2>
 <div id="poststuff" style="margin-top:10px;">
	<div id="mainblock" style="width:710px;">
	 <div class="dbx-content">
		<form action="<?php echo $action_url ?>" method="post">
			<input type="hidden" name="submitted" value="1" />
			<?php wp_nonce_field('wprdfa-nonce'); ?>
			<h3>Usage</h3>
			<p>wp-RDFa allows you to create machine readable content from your wordpress blog. This concept of a semantic web computers to perform more of the tedious work involved in finding, sharing, and combining information on the web. You can find out more <a href="http://en.wikipedia.org/wiki/Semantic_Web">here.</a></p>
			<h3>FOAF (Friend of a Friend) Settings</h3>
			<p>The Friend of a Friend (FOAF) project is used to create a Web of machine readable pages describing people, the links between them and the things they create and do. Here you can tweak the output of your FOAF file, acknowledging only certain users on your blog and tagging the work they do. By default its assumed that you only know higher level users on your blog. This information acn then be cross reference with other FOAF data.<br/></p>
			<input type="checkbox" name="foaf" <?php echo $foaf ?> /><Label for="foaf">Enable FOAF Publication</label><br />
			<p>Show the relationships between you and your blog users (Acknowledge in FOAF that you know them). This can be defined on a per WordPress roll basis.<br /></p>
			<input type="checkbox" name="foaf_know_admin" <?php echo $foaf_know_admin ?> /><label for="foaf_know_admin">Administrators.</label><br />
			<input type="checkbox" name="foaf_know_editor" <?php echo $foaf_know_editor ?> /><label for="foaf_know_editor">Editors.</label><br />
			<input type="checkbox" name="foaf_know_author" <?php echo $foaf_know_author ?> /><label for="foaf_know_author">Authors.</label><br />
			<input type="checkbox" name="foaf_know_contributor" <?php echo $foaf_know_contributor ?> /><label for="foaf_know_contributor">Contributors.</label><br />
			<input type="checkbox" name="foaf_know_subscriber" <?php echo $foaf_know_subscriber ?> /><label for="foaf_know_subscriber">Subscribers. </label><br /><br />
			<h3>Inline Dublin Core options</h3>
			<p>wp-RDFa uses the Dublin Core vocabularly to mark up blog posts and accredit the work to the author and the url of the post.<br /></p>
			<input type="checkbox" name="dc_elements" <?php echo $dc_elements ?> /><Label for="dc_elements">Enable inline dublin core elements.</label><br />
			<div class="submit"><input type="submit" name="Submit" value="Update" /></Div>
	 </div>
	</div>
 </div>
