<?php
/*
Plugin Name: JR Traffic
Plugin URI: http://www.jakeruston.co.uk/2010/02/wordpress-plugin-jr-traffic/
Description: This plugin allows you to show the latest traffic updates as a widget.
Version: 1.2.3
Author: Jake Ruston
Author URI: http://www.jakeruston.co.uk
*/

/*  Copyright 2010 Jake Ruston - the.escapist22@gmail.com

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

$pluginname="traffic";

// Hook for adding admin menus
add_action('admin_menu', 'jr_Traffic_add_pages');

// action function for above hook
function jr_Traffic_add_pages() {
    add_options_page('JR Traffic', 'JR Traffic', 'administrator', 'jr_Traffic', 'jr_Traffic_options_page');
}

if (!function_exists("_iscurlinstalled")) {
function _iscurlinstalled() {
if (in_array ('curl', get_loaded_extensions())) {
return true;
} else {
return false;
}
}
}

if (!function_exists("jr_show_notices")) {
function jr_show_notices() {
echo "<div id='warning' class='updated fade'><b>Ouch! You currently do not have cURL enabled on your server. This will affect the operations of your plugins.</b></div>";
}
}

if (!_iscurlinstalled()) {
add_action("admin_notices", "jr_show_notices");

} else {
if (!defined("ch"))
{
function setupch()
{
$ch = curl_init();
$c = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
return($ch);
}
define("ch", setupch());
}

if (!function_exists("curl_get_contents")) {
function curl_get_contents($url)
{
$c = curl_setopt(ch, CURLOPT_URL, $url);
return(curl_exec(ch));
}
}
}

if (!function_exists("jr_Traffic_refresh")) {
function jr_Traffic_refresh() {
update_option("jr_submitted_Traffic", "0");
}
}

register_activation_hook(__FILE__,'traffic_choice');

function traffic_choice () {
if (get_option("jr_traffic_links_choice")=="") {

if (_iscurlinstalled()) {
$pname="jr_Traffic";
$url=get_bloginfo('url');
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname);
update_option("jr_submitted_Traffic", "1");
wp_schedule_single_event(time()+172800, 'jr_Traffic_refresh'); 
} else {
$content = "Powered by <a href='http://arcade.xeromi.com'>Free Online Games</a> and <a href='http://directory.xeromi.com'>General Web Directory</a>.";
}

if ($content!="") {
$content=utf8_encode($content);
update_option("jr_traffic_links_choice", $content);
}
}

if (get_option("jr_traffic_link_personal")=="") {
$content = curl_get_contents("http://www.jakeruston.co.uk/p_pluginslink4.php");

update_option("jr_traffic_link_personal", $content);
}
}

// jr_Traffic_options_page() displays the page content for the Test Options submenu
function jr_Traffic_options_page() {

    // variables for the field and option names 
    $opt_name = 'mt_Traffic_header';
	$opt_name_2 = 'mt_Traffic_color';
    $opt_name_3 = 'mt_Traffic_topic';
	$opt_name_4 = 'mt_Traffic_number';
    $opt_name_6 = 'mt_Traffic_plugin_support';
    $hidden_field_name = 'mt_quotes_submit_hidden';
    $data_field_name = 'mt_Traffic_header';
	$data_field_name_2 = 'mt_Traffic_color';
    $data_field_name_3 = 'mt_Traffic_topic';
	$data_field_name_4 = 'mt_Traffic_number';
    $data_field_name_6 = 'mt_Traffic_plugin_support';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );
	$opt_val_2 = get_option( $opt_name_2 );
    $opt_val_3 = get_option( $opt_name_3 );
	$opt_val_4 = get_option( $opt_name_4 );
    $opt_val_6 = get_option($opt_name_6);
    
if (!$_POST['feedback']=='') {
$my_email1="the.escapist22@gmail.com";
$plugin_name="JR Traffic";
$blog_url_feedback=get_bloginfo('url');
$user_email=$_POST['email'];
$user_email=stripslashes($user_email);
$subject=$_POST['subject'];
$subject=stripslashes($subject);
$name=$_POST['name'];
$name=stripslashes($name);
$response=$_POST['response'];
$response=stripslashes($response);
$category=$_POST['category'];
$category=stripslashes($category);
if ($response=="Yes") {
$response="REQUIRED: ";
}
$feedback_feedback=$_POST['feedback'];
$feedback_feedback=stripslashes($feedback_feedback);
if ($user_email=="") {
$headers1 = "From: feedback@jakeruston.co.uk";
} else {
$headers1 = "From: $user_email";
}
$emailsubject1=$response.$plugin_name." - ".$category." - ".$subject;
$emailmessage1="Blog: $blog_url_feedback\n\nUser Name: $name\n\nUser E-Mail: $user_email\n\nMessage: $feedback_feedback";
mail($my_email1,$emailsubject1,$emailmessage1,$headers1);
?>
<div class="updated"><p><strong><?php _e('Feedback Sent!', 'mt_trans_domain' ); ?></strong></p></div>
<?php
}

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];
		$opt_val_2 = $_POST[ $data_field_name_2 ];
        $opt_val_3 = $_POST[ $data_field_name_3 ];
		$opt_val_4 = $_POST[ $data_field_name_4 ];
        $opt_val_6 = $_POST[$data_field_name_6];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );
		update_option( $opt_name_2, $opt_val_2 );
        update_option( $opt_name_3, $opt_val_3 );
		update_option( $opt_name_4, $opt_val_4 );
        update_option( $opt_name_6, $opt_val_6 );  

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'JR Traffic Plugin Options', 'mt_trans_domain' ) . "</h2>";
$blog_url_feedback=get_bloginfo('url');
	$donated=curl_get_contents("http://www.jakeruston.co.uk/p-donation/index.php?url=".$blog_url_feedback);
	if ($donated=="1") {
	?>
		<div class="updated"><p><strong><?php _e('Thank you for donating!', 'mt_trans_domain' ); ?></strong></p></div>
	<?php
	} else {
	if ($_POST['mtdonationjr']!="") {
	update_option("mtdonationjr", "444");
	}
	
	if (get_option("mtdonationjr")=="") {
	?>
	<div class="updated"><p><strong><?php _e('Please consider donating to help support the development of my plugins!', 'mt_trans_domain' ); ?></strong><br /><br /><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="ULRRFEPGZ6PSJ">
<input type="image" src="https://www.paypal.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form></p><br /><form action="" method="post"><input type="hidden" name="mtdonationjr" value="444" /><input type="submit" value="Don't Show This Again" /></form></div>
<?php
}
}

    // options form
    
    $change4 = get_option("mt_Traffic_plugin_support");

if ($change4=="Yes" || $change4=="") {
$change4="checked";
$change41="";
} else {
$change4="";
$change41="checked";
}
    ?>
	<iframe src="http://www.jakeruston.co.uk/plugins/index.php" width="100%" height="20%">iframe support is required to see this.</iframe>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("Traffic Widget Title: ", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="50">
</p><hr />

<p><?php _e("Zip Code: ", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name_3; ?>" value="<?php echo $opt_val_3; ?>" size="50">
</p><hr />

<p><?php _e("Number of Traffic items: ", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name_4; ?>" value="<?php echo $opt_val_4; ?>" size="3">
</p><hr />

<p><?php _e("Hex Colour Code:", 'mt_trans_domain' ); ?> 
#<input size="7" name="<?php echo $data_field_name_2; ?>" value="<?php echo $opt_val_2; ?>">
(For help, go to <a href="http://html-color-codes.com/">HTML Color Codes</a>).
</p><hr />

<p><?php _e("Show Plugin Support?", 'mt_trans_domain' ); ?> 
<input type="radio" name="<?php echo $data_field_name_6; ?>" value="Yes" <?php echo $change4; ?>>Yes
<input type="radio" name="<?php echo $data_field_name_6; ?>" value="No" <?php echo $change41; ?> id="Please do not disable plugin support - This is the only thing I get from creating this free plugin!" onClick="alert(id)">No
</p><hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
</p><hr />

</form>
<script type="text/javascript">
function validate_required(field,alerttxt)
{
with (field)
  {
  if (value==null||value=="")
    {
    alert(alerttxt);return false;
    }
  else
    {
    return true;
    }
  }
}

function validateEmail(ctrl){

var strMail = ctrl.value
        var regMail =  /^\w+([-.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;

        if (regMail.test(strMail))
        {
            return true;
        }
        else
        {

            return false;

        }
					
	}

function validate_form(thisform)
{
with (thisform)
  {
  if (validate_required(subject,"Subject must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(email,"E-Mail must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(feedback,"Feedback must be filled out!")==false)
  {email.focus();return false;}
  if (validateEmail(email)==false)
  {
  alert("E-Mail Address not valid!");
  email.focus();
  return false;
  }
 }
}
</script>
<h3>Submit Feedback about my Plugin!</h3>
<p><b>Note: Only send feedback in english, I cannot understand other languages!</b><br /><b>Please do not send spam messages!</b></p>
<form name="form2" method="post" action="" onsubmit="return validate_form(this)">
<p><?php _e("Your Name:", 'mt_trans_domain' ); ?> 
<input type="text" name="name" /></p>
<p><?php _e("E-Mail Address (Required):", 'mt_trans_domain' ); ?> 
<input type="text" name="email" /></p>
<p><?php _e("Message Category:", 'mt_trans_domain'); ?>
<select name="category">
<option value="General">General</option>
<option value="Feedback">Feedback</option>
<option value="Bug Report">Bug Report</option>
<option value="Feature Request">Feature Request</option>
<option value="Other">Other</option>
</select>
<p><?php _e("Message Subject (Required):", 'mt_trans_domain' ); ?>
<input type="text" name="subject" /></p>
<input type="checkbox" name="response" value="Yes" /> I want e-mailing back about this feedback</p>
<p><?php _e("Message Comment (Required):", 'mt_trans_domain' ); ?> 
<textarea name="feedback"></textarea>
</p>
<p class="submit">
<input type="submit" name="Send" value="<?php _e('Send', 'mt_trans_domain' ); ?>" />
</p><hr /></form>
</div>
<?php
}

if (get_option("jr_Traffic_links_choice")=="") {
Traffic_choice();
}

function show_Traffic($args) {

extract($args);

  $Traffic_header = get_option("mt_Traffic_header"); 
  $plugin_support2 = get_option("mt_Traffic_plugin_support");
  $Traffic_type = get_option("mt_Traffic_topic");
  $Traffic_number = get_option("mt_Traffic_number");
  $Trafficcolor = get_option("mt_Traffic_color");


if ($Traffic_header=="") {
$Traffic_header="Latest Traffic News";
}

if ($Traffic_number=="") {
$Traffic_number=5;
}

if ($Traffic_type=="") {
$Traffic_type="15222";
}

$i=1;

echo $before_title.$Traffic_header.$after_title.$before_widget; 

$doc = new DOMDocument();

$doc->load('http://local.yahooapis.com/MapsService/rss/trafficData.xml?appid=DenkQ9nV34HCi6HvcbKCyffkxB7dtgWQK5E17bwRLFdbKy1aKqdQ_S064yudY_8a9_wYCeOURPHENA--&zip='.$Traffic_type) or die("Error!");

foreach ($doc->getElementsByTagName('item') as $node) {
$t_Traffic = $node->getElementsByTagName('title')->item(0);
$t_Trafficlink = $node->getElementsByTagName('link')->item(0);
$t_Trafficdesc = $node->getElementsByTagName('description')->item(0);
$Traffic = $t_Traffic->nodeValue;
$Trafficlink = $t_Trafficlink->nodeValue;
$Trafficdesc = $t_Trafficdesc->nodeValue;

echo "<li style='color:#".$Trafficcolor."'><a href='".$Trafficlink."' rel='nofollow'>".$Traffic."</a><br />".$Trafficdesc."</li>";
if($i++ >= $Traffic_number) break;

}

if ($i==1) {
echo "<li style='color:#".$Trafficcolor."'>No traffic issues to display!</li>";
}

if ($plugin_support2=="Yes" || $plugin_support2=="") {
$linkper=utf8_decode(get_option('jr_traffic_link_personal'));

if (get_option("jr_Traffic_link_newcheck")=="") {
$pieces=explode("</a>", get_option('jr_Traffic_links_choice'));
$pieces[0]=str_replace(" ", "%20", $pieces[0]);
$pieces[0]=curl_get_contents("http://www.jakeruston.co.uk/newcheck.php?q=".$pieces[0]."");
$new=implode("</a>", $pieces);
update_option("jr_Traffic_links_choice", $new);
update_option("jr_Traffic_link_newcheck", "444");
}

if (get_option("jr_submitted_Traffic")=="0") {
$pname="jr_Traffic";
$url=get_bloginfo('url');
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname);
update_option("jr_submitted_Traffic", "1");
update_option("jr_Traffic_links_choice", $content);

wp_schedule_single_event(time()+172800, 'jr_Traffic_refresh'); 
} else if (get_option("jr_submitted_Traffic")=="") {
$pname="jr_Traffic";
$url=get_bloginfo('url');
$current=get_option("jr_Traffic_links_choice");
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname."&override=".$current);
update_option("jr_submitted_Traffic", "1");
update_option("jr_Traffic_links_choice", $content);

wp_schedule_single_event(time()+172800, 'jr_Traffic_refresh'); 
}

echo "<p style='color: #".$Trafficcolor."; font-size:x-small'>Traffic Plugin created by ".$linkper." - ".stripslashes(get_option('jr_Traffic_links_choice'))."</p>";
}

echo $after_widget;

}

function init_Traffic_widget() {
register_sidebar_widget("JR Traffic", "show_Traffic");
}

add_action("plugins_loaded", "init_Traffic_widget");

?>
