<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";

$room = new Room();
$roomPricePlan = new RoomPricePlan();
if(isset($_POST['SBMT_REG']))
{
	$logger->LogInfo("Form has been submitted.");
	$room = Room::fetchFromParameters($_POST);	
	if (!$room->save())
	{
		$logger->LogError("Error saving room.");
		foreach ($room->errors as $error) 
		{
			$logger->LogError($error);
			$errors[] = $error;
		}
	}	
	else
	{
		$roomPricePlan = RoomPricePlan::fetchFromParameters($_POST);
		$roomPricePlan->id = intval($_POST['price_plan_id']);
		$roomPricePlan->isDefault = true;
		$roomPricePlan->roomId = $room->id;
		if (!$roomPricePlan->save())
		{
			$logger->LogError("Error saving room.");
			foreach ($room->errors as $error) 
			{
				$logger->LogError($error);
				$errors[] = $error;
			}
		}
		else
		{
			header("Location: rooms_list.php");
			$message = "Values were updated successfully!";
		}
	}
}
else if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
{
	$logger->LogInfo("Page was called for edit of id: " . $_REQUEST['id']);
	$id = intval($_REQUEST['id']);
	$logger->LogDebug("Numeric id is: $id");
	$room = Room::fetchFromDb($id);
	if ($room == null)
	{
		$logger->LogError("Invalid request. No room with id: $id exists.");
		$errors[] = "Invalid request. No room with id: $id exists.";
	}
	else
	{
		$roomPricePlan = $room->getDefaultPricePlan();
		if ($roomPricePlan == null)
		{
			$logger->LogError("Invalid configuration. No default price plan exists fro room with id: $roomId.");
			$errors[] = "Invalid configuration. No default price plan exists fro room with id: $roomId.";			
		}
	}
}


$defaultLanguage = Language::fetchDefaultLangauge();


include ("header.php");
?>

<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
</td>
<tr>
  <td valign="top">
  <?php
	if (sizeof($errors) > 0)
	{
		echo '			<table class="TitleBlue11pt" width="100%">' . "\n";
		foreach ($errors as $error) 
		{
			echo '				<tr><td style="color: red; font-weight: bold;">' . htmlentities($error) . '</td></tr>' . "\n";
		}
		echo '			</table>' . "\n";
	}
	else if ($message != "")
	{
		echo '			<table class="TitleBlue11pt" width="100%">' . "\n";
		echo '				<tr><td align="center" style="color: green; font-weight: bold;">' . htmlentities($message) . '</td></tr>' . "\n";
		echo '			</table>' . "\n";
	}
	else
	{
	?>	
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onsubmit="return validateRoom();">
		<input type="hidden" name="id" value="<?= $room->id ?>" />
		<input type="hidden" name="price_plan_id" value="<?= $roomPricePlan->id ?>" />
		<fieldset>	
		    <legend class="TitleBlue11pt">Add/Edit Room</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3" class="TitleBlue11pt">	                                        
		        
		        <tr>
					<td>Room Number</td>
                    <td><input type="text" size="120" name="room_number" value="<?= $room->roomNumber ?>" /></td>
                </tr>
		        <tr>
					<td>Room Name</td>
                    <td><input type="text" size="120" name="room_name" value="<?= $room->roomName ?>" /></td>
                </tr>
                <tr>
					<td>Room Max Capacity</td>
					<td><input type="text" name="capacity" value="<?= $room->capacity ?>" /></td>
				</tr>
				<tr>
					<td>Default Price per Night</td>
					<td><input type="text" name="price" value="<?= $roomPricePlan->price ?>" /></td>
				</tr>
				<tr>
					<td>Allow Extra Bed</td>
					<td>
						<input type="radio" name="extra_bed" value="1" <?= $room->hasExtraBed ? ' checked="checked"' : ' '?>/> Yes
						<input type="radio" name="extra_bed" value="0" <?= $room->hasExtraBed ? ' ' : ' checked="checked" '?>/> No
					</td>
				</tr>
				<tr>
					<td>Default Extra Bed Price</td>
					<td>
						<input type="text" name="extrabed" value="<?= $roomPricePlan->extraBedPrice ?>" />
					</td>
				</tr>
				<tr>
					<td>Type</td>
					<td>
						<input type="radio" name="is_apartment" value="1" <?= $room->isApartment ? ' checked="checked"' : ' '?>/> Apartment
						<input type="radio" name="is_apartment" value="0" <?= $room->isApartment ? ' ' : ' checked="checked"'?>/> Room
					</td>
				</tr>                
		        <?php
			        $languages = Language::fetchAllFromDbActive();	                                        	
			        foreach ($languages as $language) 
			        {	                                        		
			    ?>
			    		<tr><td colspan="2"><hr></td></tr>
			    		<tr>
			    			<td colspan="2">
			    				<img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>" /> <?= $language->languageName ?> Contents
			    				<br/><br />
			    				<textarea  class="ckeditor"  name="room_desc_<?= $language->languageCode ?>"><?= htmlentities($room->getDescription($language->languageCode)) ?></textarea>
			            	</td>			            	
			            </tr>
			                                   
			    <?php 
			        }
			    ?>	        		                                           
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

function isValidDate(strDate)
{
	var dteDate;
	var day, month, year;	
	var matchArray = strDate.split('-');
	if (matchArray == null || matchArray.length != 3)
	{				
		return false;
	}

	day = matchArray[2]; // p@rse date into variables
	month = matchArray[1];
	year = matchArray[0];
	month--;

	dteDate=new Date(year,month,day);
	return ((day==dteDate.getDate()) && (month==dteDate.getMonth()) && (year==dteDate.getFullYear()));
}

function validateRoom()
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