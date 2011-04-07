<?php 

require_once('../../../../wp-load.php');

$baseUrl = $_GET['baseurl'];

$id = $_GET["id"];

$currentImg = get_post_meta($id, "ph_picture_url");
$currentImg = $currentImg[0];

$currentHeadline = get_post_meta($id, "ph_headline");
$currentHeadline = $currentHeadline[0];

$currentOrder = get_post_meta($id, "ph_order");
$currentOrder = $currentOrder[0];
?>

<div class="ph_updated updated fade" id="ph_updated_<?php echo $id; ?>" style="display:none;"><?php _e('Settings Updated','ph'); ?></div>
<form name="ph_settings_form">
<table width="100%" class="ph_settings_table" cellspacing="10">
    <tr>
        <td rowspan="2">
        <span class="ph_settings_title"><span>1</span> <?php _e('Choose a picture from your post','ph'); ?></span>
        <div id="pictures_<?php echo $id; ?>" class="ph_set_pic">
        
        	<?php 
        	
        	$query = "SELECT guid, ID FROM $wpdb->posts WHERE post_type='attachment' AND post_parent='$id'";
        	$attachments = $wpdb->get_results($query);

        	$isImageFromPost = false;
        	foreach ($attachments as $picture):
        		
        		if ($currentImg == $picture->guid) { 
        			 $curClass = "class='ph_img_selected'";
        			 $isImageFromPost = true;
        		}else  $curClass = "";
        		echo "<img src='{$picture->guid}' alt='{$picture->ID}' $curClass>";
        	
        	endforeach;

        	?>
        </div>
        <br>
        <?php _e('Or enter URL for the picture','ph'); ?>
        <input type="text" class="ph_picture_url" id="ph_picture_url_<?php echo $id; ?>" name="ph_picture_url" value="<?php if (!$isImageFromPost && $currentImg) echo $currentImg; ?>">
        
        </td>
        <td class="ph_settings_table_right">
        
       <span class="ph_settings_title"><span>2</span>  <?php _e('Enter a headline','ph'); ?></span>
       <br>
        <span><?php _e('This will be displayed under the post\'s title:','ph'); ?></span>
        <input type="text" class="ph_headline" id="ph_headline_<?php echo $id; ?>" name="ph_headline" value="<?php echo $currentHeadline; ?>">
		</td>
	</tr>
	<tr>
		<td>
        <span class="ph_settings_title"><span>3</span> <?php _e('Order','ph'); ?></span>
        <br>
        <span><?php _e('Select the display order.','ph'); ?></span>
        <select name="ph_prder" class="ph_order" id="ph_order_<?php echo $id; ?>">
        	<?php for($x=1;$x<51;$x++){ ?>
        	
        		<option value="<?php echo $x; ?>" <?php if ($currentOrder==$x) echo "selected"; ?>>
        			<?php echo $x; ?>
        		</option>
        	
        	<?php } ?>
        </select>
        
        </td>
    </tr>
</table>
</form>
<script type="text/javascript" src="<?php echo $baseUrl; ?>js/ph_settings.js"></script>
