<?php
	/* Plugin name: Pro Call To Action Buttons
	 * Plugin URI: http://becoded.net/pro-cta
	 * Author: Alex
	 * Author URI: http://onlyplugin.net/
 	 * Description: Insert awesome buttons in your website pretty quick and easy
	 * Version: 1.3
	 */

	add_shortcode('ws_cta', 'ws_call_to_action');
	
	function ws_call_to_action($atts)
	{
		extract(shortcode_atts(array(
			'url' => 'http://example.com/',
			'button' => 'add_to_cart',
			'height' => '',
			'width' => '',
			'target' => '_self',
			'position' => 'center',
			'border' => '#df5900'
		), $atts));
		
		//get the image button
		$button = plugins_url('/btns/'.$button.'.png', __FILE__);
		//get image button info to get the ratio
		$image_info = getimagesize($button);
		//get the image ratio
		$ratio = $image_info[0]/$image_info[1];

		if ((stripos($height, "px") !== false) && (trim($width) == ""))//in case the user set the value for the height and doesn't set a value for width
		{
			$width = $height*$ratio;
			$width .="px";
		}
		
		if ((stripos($width, "px") !== false) && (trim($height) == ""))//in case the user set the value for the width and doesn't set a value for height
		{
			$height = $width/$ratio;
			$height .="px";
		}
		
		if ((trim($height) == "") && (trim($width) == ""))//in case the value are not set
		{
			$width = $image_info[0]."px";
			$height = $image_info[1]."px";
		}
		
		
			return "<div style='margin:auto; text-align: center;'><a href='$url' target='$target'><img src='$button' height='$height' width='$width' /></a></div>";

			
	}
	//add the menu to the dashboard
	add_action('admin_menu', 'pro_cta_menu');
	
	function pro_cta_menu()
	{
		add_menu_page('Pro CTA Lite', 'Pro CTA', 'manage_options', 'pro_cta_set', 'main_pro_cta_cb');
		add_submenu_page('pro_cta_set', 'Pro CTA User Guide', 'User Guide', 'manage_options', 'sub_pro_cta', 'sub_pro_cta_cb');
	}
	
	
	function sub_pro_cta_cb()
	{?>
		<h2>User Manual</h2>
		<p>
			Inserting the button to your page is very simple. Here is an example:<br />
			[ws_cta button="button_name" height="" width="" url="" target=""]
			<ul>
				<li><strong>The button</strong> value is the name of the button you need to insert into the page. The button names are stored in the main page of this plugin.</li>
				<li><strong>Height, width</strong> are set to 100% by default. You can set them percentage values or pixel values. For example height="200" width="500".</li>
				<li><strong>url</strong> is place you want the visitor go after clicking the button.</li>
				<li><strong>target="_blank"</strong>, if you want to open link in new page, else, you don't need to set this value.</li>

			</ul>
			<p>Alternatively, you can go to the main plugin page to get the code of specific buttons. Check the video below</p>
			<div style="text-align: center; margin:auto;">
				<iframe width="640" height="360" src="http://www.youtube.com/embed/oWeNMyDFulI" frameborder="0" allowfullscreen></iframe>
			</div>
			
			
		</p>
	<?php }
	
	function main_pro_cta_cb()
	{
		$btns = scandir(plugin_dir_path(__FILE__)."/btns");
		echo '<div style="margin:auto; padding: 10px; text-align: center; font-size: 24px;"><a href="http://www.onlyplugin.com/free-call-to-action-plugin-for-your-blog/" target="_blank">Want 30+ more buttons? Upgrade now for free!</a></div>';
		echo '<input type="button" id="show_btns" value="Show Buttons" style="cursor: pointer;" />';
		echo "<div id='buttons' style='display:none;'>";
		for($i=0; $i<count($btns); $i++)
		{
			if(strlen($btns[$i]) > 5)
			{
				$id = explode(".", $btns[$i]);
				$id = $id[0];
				echo "<div style='width:650px;'><div class='img' id='$id'><img src='".plugins_url("", __FILE__).'/btns/'.$btns[$i]."' /></div><div class='code' style='display:none;'></div></div><br />";
				//echo plugins_url("", __FILE__);
			}
		}
		echo "</div>"
		?>
		<script>
			jQuery(document).ready(function(){
				jQuery("#show_btns").live("click",function(){
					if (jQuery("#buttons").is(":visible"))
					{
						jQuery(this).val("Show Buttons");
						jQuery("#buttons").fadeOut();
					} else
					{
						jQuery(this).val("Hide Buttons");
						jQuery("#buttons").fadeIn();
					}
				});
				jQuery('.img').click(function(){
					var code = '[ws_cta url="" height="" width="" target="" button="'+jQuery(this).attr("id")+'"]';	

					jQuery(this).siblings('.code').text(code);
					jQuery('.code').fadeOut();					
					jQuery(this).siblings('.code').fadeIn();
				});

			});
		</script>

	<?php }