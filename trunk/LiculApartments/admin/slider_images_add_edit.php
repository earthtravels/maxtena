<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";
$imageId = 0;
	
$logger->LogInfo(__FILE__);
$logger->LogInfo(var_export($_POST, true));
$sliderImage = new SliderImage();
if(isset($_POST['id']))
{
	$logger->LogInfo("Form has been submitted.");
	$sliderImage = SliderImage::fetchFromParameters($_POST, $_FILES);
	if (!$sliderImage->save())
	{
		$logger->LogError("Error saving slider image.");
		foreach ($sliderImage->errors as $error) 
		{
			$logger->LogError($error);
			$errors[] = $error;
		}
	}	
	else
	{		
		header("Location: slider_images_list.php");
	}
}
else if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
{	
	$imageId = intval($_REQUEST['id']);
	$logger->LogInfo("Page was called for image edit of slider image id: $imageId");	
	$sliderImage = SliderImage::fetchFromDb($imageId);
	if ($sliderImage == null)
	{
		$logger->LogError("Invalid request. No gallery slider image with  id: $imageId exists.");
		$errors[] = "Invalid request. No gallery slider image with  id: $imageId exists.";
	}
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
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onsubmit="return validateSliderImage();">
		<input type="hidden" name="id" value="<?= $imageId ?>" />		
		<fieldset>	
		    <legend class="TitleBlue11pt">Slider Gallery Image</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">		        
		        <?php
			        $languages = Language::fetchAllFromDbActive();	                                        	
			        foreach ($languages as $language) 
			        {	   
			        	$galleryImage = $sliderImage->galleryImage;
			        	$desc = $galleryImage->description->getText($language->languageCode);                             		
			    ?>
			    		<tr class="TitleBlue11pt">
			    			<td valign="middle" align="left" width="22%">
			    				<img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>" /> <?= $language->languageName ?> Description
			            	</td>
			            	<td align="left" valign="middle">
			            		<input type="text" name="desc_<?= $language->languageCode ?>" value="<?= htmlentities($sliderImage->galleryImage->description->getText($language->languageCode)) ?>" size="120"/>
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
			            		<input type="text" name="link" value="<?= htmlentities($sliderImage->galleryImage->link) ?>" size="120"/>			            					            		
		            		</td>
			            </tr>
			            <tr class="TitleBlue11pt">
			    			<td valign="middle" align="left" width="22%">
			    				Image
			    				<?php 
									if (trim($sliderImage->galleryImage->imageFileName) != "" && file_exists("../images/" . $sliderImage->galleryImage->imageFileName))
									{
										echo "<small>(Leave blank to preserve existing image)</small>";										
									}
								?>
			            	</td>
			            	<td align="left" valign="middle">
			            		<input type="file" name="image_name" size="120" /><br />
			            		<img src="../images/<?= $sliderImage->galleryImage->imageFileName ?>" width="500" />			            					            		
		            		</td>
			            </tr>			            
			            <tr class="TitleBlue11pt">
			    			<td valign="middle" align="left" width="22%">
			    				Display Order
			            	</td>
			            	<td align="left" valign="middle">
			            		<input type="text" name="display_order" value="<?= $sliderImage->galleryImage->displayOrder ?>" size="120"/>			            					            		
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

function validateSliderImage()
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