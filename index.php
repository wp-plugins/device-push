<?php
/*
Plugin Name: Device Push
Description: Device Push is a service that consists of SDKs, API, and a control panel to manage notifications without having to deal with the different platforms, unifying all the code. With Device Push, you will save much time and no need to worry about storing large token, Device Push will do it all for you.
Author: Device Push
Version: 0.2
*/

// Create custom plugin settings menu
add_action('admin_menu', 'dp_create_menu');

function dp_create_menu() {
	add_menu_page('Device Push Plugin Settings', 'Device Push', 'administrator', __FILE__, 'dp_settings_page',plugins_url('/images/icon.png', __FILE__));
	add_action( 'admin_init', 'register_mysettings' );
}

// Function send notification API
add_action( 'publish_post', 'SendNotificacion', 10, 2);

function SendNotificacion($ID, $post) {
	if(esc_attr( get_option('dp_option_iduser') ) && esc_attr( get_option('dp_option_idaplication') ) && esc_attr( get_option('dp_option_status') )){
		$postData = array( 
			'idApplication' => esc_attr( get_option('dp_option_idaplication') ),
			'content' => $post->post_title
		);
		 
		$context = stream_context_create(array(
			'http' => array(
				'method' => 'POST',
				'header' => 'token: '.esc_attr( get_option('dp_option_iduser') ),
				'content' => http_build_query($postData)
			)
		));
		 
		$url = 'http://api.devicepush.com/send';
		$result = file_get_contents($url, false, $context);
	}
}

// Set value input form
function register_mysettings() {
	register_setting( 'dp-settings-group', 'dp_option_iduser' );
	register_setting( 'dp-settings-group', 'dp_option_idaplication' );
	register_setting( 'dp-settings-group', 'dp_option_status' );
}

function dp_settings_page() {
?>
<div class="wrap">
<h2>Device Push</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'dp-settings-group' ); ?>
    <?php do_settings_sections( 'dp-settings-group' ); ?>
	<table class="form-table">  
	<tr>
	<td style="padding:0px !important">
	<img src="<?php echo plugins_url('/images/logo-device.png', __FILE__); ?>" style="width:80px; height:auto; border-radius: 100px; margin-right:10px">
	</td>
    	<td style="padding:0px !important">
	<p><strong>Device Push</strong> is a service that consists of SDKs, API, and a control panel to manage notifications without having to deal with the different platforms, unifying all the code. With <strong>Device Push</strong>, you will save much time and no need to worry about storing large token, <strong>Device Push</strong> will do it all for you.</p>
	<p>With <strong>Device Push</strong> Plugin for <strong>WordPress</strong> you can send push notifications to your mobile application.</p>
	</td>
	</tr>
	</table>
	<p>To configure the plugin with your <a href="http://panel.devicepush.com/" target="_blank">Device Push</a> account, you'll have to fill the next form with your user id and application id of Device Push.</p>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">User ID</th>
        <td><input type="text" name="dp_option_iduser" value="<?php echo esc_attr( get_option('dp_option_iduser') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Aplication ID</th>
        <td><input type="text" name="dp_option_idaplication" value="<?php echo esc_attr( get_option('dp_option_idaplication') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Send notification</th>
        <td><input type="checkbox" name="dp_option_status" <?php if (esc_attr( get_option('dp_option_status') )){echo 'checked'; } ?>> I want to send a notification each time I post an article on my blog.</td>
        </tr>
    </table>
    
    <?php submit_button(); ?>
    
    <p>Find more information about Device Push in its website: <a href="http://www.devicepush.com/" target="_blank">www.devicepush.com</a></p>
    <p>Follow us in:</p>
    
<div style="height:30px; line-height:30px"><table><tr><td><img src="<?php echo plugins_url('/images/icon-twitter.png', __FILE__); ?>" style="width: 20px; height: auto; margin-right: 5px; margin-bottom: -5px;"></td><td>Twitter: <a href="http://twitter.com/devicepush" target="_blank">@devicepush</a></td></tr></table></div>
 
<div style="height:30px; line-height:30px"><table><tr><td><img src="<?php echo plugins_url('/images/icon-facebook.png', __FILE__); ?>" style="width: 20px; height: auto; margin-right: 5px; margin-bottom: -5px;"></td><td>Facebook: <a href="http://fb.com/devicepush" target="_blank">fb.com/devicepush</a></td></tr></table></div>

</form>
</div>
<?php } ?>