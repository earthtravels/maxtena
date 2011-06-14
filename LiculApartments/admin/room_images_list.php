<?php
// TODO: umcomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
global $logger;


$errors = array();
$roomImages = array();
$roomId = 0;

if (isset($_GET['room_id']) && is_numeric($_GET['room_id']))
{
	$roomId = intval($_GET['room_id']);
	$room = Room::fetchFromDb($roomId);
	if ($room == null)
	{
		$errors[] = "There is no room with id: $roomId";
	}
	else
	{
		$logger->LogDebug("Fetching room images for room id $roomId ...");
		//$roomImages = RoomImage::fetchFromDbForRoom($roomId);
		$roomImages = $room->getImages();
	}			
}
else
{
	$errors[] = "Invalid room id";
}


$logger->LogDebug("Fetching default language ...");
$defaultLanguage = Language::fetchDefaultLangauge();
if ($defaultLanguage == null)
{
	$logger->LogError("There were errors fetching default language.");
	foreach (Language::$staticErrors as $error) 
	{
		$logger->LogError($error);
		$errors[] = $error;
	}
}
include ("header.php");
?>
	</td>
  </tr> 
  
  <tr>
    <td valign="top" >
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
		else
		{					
	?>	
		<fieldset>	
		    <legend class="TitleBlue11pt"><?= $room->roomName ?> Image Gallery</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">
				<tr bgcolor="#747471">
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Full Image</font></b>
					</td>					
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Thumbnail Image</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Description</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Link</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Display Order</font></b>
					</td>
					<td scope="col" class="bodytext_h">&nbsp;</td>
				</tr>
			<?php 
					foreach ($roomImages as $roomImage) 
					{
						if (!($roomImage instanceof RoomImage))
						{
							continue;
						}
						echo '<tr class="odd" bgcolor="#f2eaeb">' . "\n";
						echo '	<td align="left" valign="middle"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2"><img src="../images/' . $roomImage->galleryImage->imageFileName . '" width="300" /></font></td>' . "\n";
						echo '	<td align="left" valign="middle"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2"><img src="../images/' . $roomImage->galleryImage->thumbImageFileName . '" /></font></td>' . "\n";
						echo '	<td align="left" valign="middle"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($roomImage->galleryImage->description->getText($defaultLanguage->languageCode)) . '</font></td>' . "\n";
						echo '	<td align="left" valign="middle"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($roomImage->galleryImage->link) . '</font></td>' . "\n";																	
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($roomImage->galleryImage->displayOrder) . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">';
						echo '		<a href="room_images_add_edit.php?room_id=' . $roomImage->roomId . '&image_id=' . $roomImage->galleryImage->id . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>&nbsp;&nbsp;';
						echo '		<br/><br/><a href="room_images_delete.php?room_id=' . $roomImage->roomId . '&image_id=' . $roomImage->galleryImage->id . '" style="text-decoration:none" onclick = "if (! confirm(\'Are you sure?\')) { return false; }"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Delete</font></a>';
						echo '	</td>' . "\n";
						echo "</tr>\n";	;
					}

					if(sizeof($roomImages) == 0)
					{
						echo '	<td colspan="5" align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">No images are defined yet!</font></td>' . "\n";
					}
			?>				
			</table>							   
	    </fieldset>	
	    <table width="100%">
			<tr  bgcolor="#ffffff" class="TitleBlue11pt">
	            <td height="20" align="center">
	                <input type="image" value="1" src="images/button_add.png"  name='SBMT_REG'  onclick="javascript:window.location.href='room_images_add_edit.php?room_id=<?= $roomId ?>'">
	            </td>
	        </tr>
		</table>  
		<?php 
		}
		?> 
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
