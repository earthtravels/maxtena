<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";
$roomId = 0;
$imageId = 0;
	
$roomImage = new RoomImage();
if(isset($_POST['SBMT_REG']))
{
	$logger->LogInfo("Form has been submitted.");
	$roomImage = RoomImage::fetchFromParameters($_POST, $_FILES);
	$logger->LogInfo("Image retrieved.");
	if (is_null($roomImage) || !$roomImage->save())
	{
		$logger->LogError("Error saving room image.");
		foreach ($roomImage->errors as $error) 
		{
			$logger->LogError($error);
			$errors[] = $error;
		}
	}	
	else
	{
		$message = "Values were updated successfully!";
		header("Location: room_images_list.php?room_id=" . $roomImage->roomId);
	}
}
else if (isset($_REQUEST['room_id']) && is_numeric($_REQUEST['room_id']) && isset($_REQUEST['image_id']) && is_numeric($_REQUEST['image_id']))
{
	$roomId = intval($_REQUEST['room_id']);
	$imageId = intval($_REQUEST['image_id']);
	$logger->LogInfo("Page was called for image edit of room id: $roomId and image id: $imageId");	
	$roomImage = RoomImage::fetchFromDb($roomId, $imageId);
	if ($roomImage == null)
	{
		$logger->LogError("Invalid request. No gallery image with room id: $roomId and image id: $imageId exists.");
		$errors[] = "Invalid request. No gallery image with room id: $roomId and image id: $imageId exists.";
	}
}
else if (isset($_REQUEST['room_id']) && is_numeric($_REQUEST['room_id']))
{
	$roomId = intval($_REQUEST['room_id']);	
	$logger->LogInfo("Page was called for image add of room id: $roomId");	
	$roomImage->roomId = $roomId;	
}
else
{
	$logger->LogError("Invalid request. No room id was specified");
	$errors[] = "Invalid request. No room id was specified";
}

include ("header.php");
?>
</td>
<tr>
  <td valign="top">
  <?php
	if (sizeof($errors) > 0)
	{
		echo '			<table width="100%">' . "\n";
		foreach ($errors as $error) 
		{
			echo '				<tr><td class="TitleBlue11pt" style="color: red; font-weight: bold;">' . htmlentities($error) . '</td></tr>' . "\n";
		}
		echo '			</table>' . "\n";
	}
	else if ($message != "")
	{
		echo '			<table width="100%">' . "\n";
		echo '				<tr><td class="TitleBlue11pt" align="center" style="color: green; font-weight: bold;">' . htmlentities($message) . '</td></tr>' . "\n";
		echo '			</table>' . "\n";
	}
	else
	{
	?>	
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onsubmit="return validateRoomImage();">
		<input type="hidden" name="id" value="<?= $imageId ?>" />
		<input type="hidden" name="room_id" value="<?= $roomId ?>" />
		<fieldset>	
		    <legend class="TitleBlue11pt">Room/Apartment Gallery Image</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">		        
		        <?php
			        $languages = Language::fetchAllFromDbActive();	                                        	
			        foreach ($languages as $language) 
			        {	   
			        	$galleryImage = $roomImage->galleryImage;
			        	$desc = $galleryImage->description->getText($language->languageCode);                             		
			    ?>
			    		<tr class="TitleBlue11pt">
			    			<td valign="middle" align="left" width="22%">
			    				<img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>" /> <?= $language->languageName ?> Description
			            	</td>
			            	<td align="left" valign="middle">
			            		<input type="text" name="desc_<?= $language->languageCode ?>" value="<?= htmlentities($roomImage->galleryImage->description->getText($language->languageCode)) ?>" size="120"/>
		            		</td>
			            </tr>                       
			    <?php 
			        }
			    ?>
			    		<tr class="TitleBlue11pt">
			    			<td valign="middle" align="left" width="22%">
			    				Link
			            	</td>
			            	<td align="left" valign="middle">
			            		<input type="text" name="link" value="<?= htmlentities($roomImage->galleryImage->link) ?>" size="120"/>			            					            		
		            		</td>
			            </tr>
			            <tr class="TitleBlue11pt">
			    			<td valign="middle" align="left" width="22%">
			    				Normal Image
			    				<?php 
									if (trim($roomImage->galleryImage->imageFileName) != "" && file_exists("../images/" . $roomImage->galleryImage->imageFileName))
									{
										echo "<small>(Leave blank to preserve existing image)</small>";										
									}
								?>
			            	</td>
			            	<td align="left" valign="middle">
			            		<input type="file" name="image_name" size="120" /><br />
			            		<img src="../images/<?= $roomImage->galleryImage->imageFileName ?>" width="500" />			            					            		
		            		</td>
			            </tr>
			            <tr class="TitleBlue11pt">
			    			<td valign="middle" align="left" width="22%">
			    				Thumbnail Image (150x150)
			    				<?php 
									if (trim($roomImage->galleryImage->imageFileName) != "" && file_exists("../images/" . $roomImage->galleryImage->imageFileName))
									{
										echo "<small>(Leave blank to preserve existing image)</small>";										
									}
								?>
			            	</td>
			            	<td align="left" valign="middle">
			            		<input type="file" name="thumb_image_name" size="120" /><br />
			            		<img src="../images/<?= $roomImage->galleryImage->thumbImageFileName ?>" />			            					            		
		            		</td>
			            </tr>
			            <tr class="TitleBlue11pt">
			    			<td valign="middle" align="left" width="22%">
			    				Display Order
			            	</td>
			            	<td align="left" valign="middle">
			            		<input type="text" name="display_order" value="<?= $roomImage->galleryImage->displayOrder ?>" size="120"/>			            					            		
		            		</td>
			            </tr>		        		                                           
			</table>		
		</fieldset>
		<table width="100%">
			<tr class="TitleBlue11pt">
	            <td height="20" align="center">
	                <input type="image" value="1" src="images/button_save.png"  name='SBMT_REG' >
	            </td>
	        </tr>
		</table>
	</form>
	<?php
	} 
	?>
  </td>
</tr>
<?php include("footer.php"); ?>
</table>
<br />
</body>
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript">
String.prototype.mytrim = function() { return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '') };

function validateRoomImage()
{
	if(document.news_posts_add_edit.title_en.value.mytrim().length == 0
			|| document.news_posts_add_edit.title_hr.value.mytrim().length == 0
			|| document.news_posts_add_edit.title_de.value.mytrim().length == 0
			|| document.news_posts_add_edit.title_it.value.mytrim().length == 0)
	{
		alert('Title cannot be blank!');
		return false;
	}

	if(document.news_posts_add_edit.category_en.value.mytrim().length == 0
			|| document.news_posts_add_edit.category_hr.value.mytrim().length == 0
			|| document.news_posts_add_edit.category_de.value.mytrim().length == 0
			|| document.news_posts_add_edit.category_it.value.mytrim().length == 0)
	{
		alert('Category cannot be blank!');
		return false;
	}	

	if(document.news_posts_add_edit.contents_en.value.mytrim().length == 0
			|| document.news_posts_add_edit.contents_hr.value.mytrim().length == 0
			|| document.news_posts_add_edit.contents_de.value.mytrim().length == 0
			|| document.news_posts_add_edit.contents_it.value.mytrim().length == 0)
	{
		alert('Contents cannot be blank!');
		return false;
	}
			
	if(document.news_posts_add_edit.id.value.mytrim() == 0 && 
			(document.news_posts_add_edit.image_small.value.mytrim().length == 0
					|| document.news_posts_add_edit.image_medium.value.mytrim().length == 0
					|| document.news_posts_add_edit.image_large.value.mytrim().length == 0))
	{
		alert('All images must be specified.');
		return false;
	}
	if(document.news_posts_add_edit.poster_name.value.mytrim().length == 0)
	{
		alert('Poster name must be specified.!');
		return false;
	}

	if(document.news_posts_add_edit.date_posted.value.mytrim().length == 0 || !isValidDate(document.news_posts_add_edit.date_posted.value))
	{
		alert('Invalid date!');
		return false;
	}	
}
</script>
</html>