<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";

$newsPost = new NewsPost();
if(isset($_POST['SBMT_REG']))
{
	$logger->LogInfo("Form has been submitted.");
	$newsPost = NewsPost::fetchFromParameters($_POST);
	if (!$newsPost->save())
	{
		$logger->LogError("Error saving news category.");
		foreach ($newsPost->errors as $error) 
		{
			$logger->LogError($error);
			$errors[] = $error;
		}
	}	
	else
	{
		$message = "Values were updated successfully!";
		$newsPost = NewsPost::fetchFromDb($newsPost->id);
	}
}
else if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
{
	$logger->LogInfo("Page was called for edit of id: " . $_REQUEST['id']);
	$id = intval($_REQUEST['id']);
	$logger->LogDebug("Numeric id is: $id");
	$newsPost = NewsPost::fetchFromDb($id);
	if ($newsPost == null)
	{
		$logger->LogError("Invalid request. No news category with id: $id exists.");
		$errors[] = "Invalid request. No news category with id: $id exists.";
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
	?>	
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onsubmit="return validateNewsPost();">
		<fieldset>	
		    <legend class="TitleBlue11pt">News Post</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">	                                        
		        <input type="hidden" name="id" value="<?= $newsPost->id ?>" />
		        <?php
			        $languages = Language::fetchAllFromDbActive();	                                        	
			        foreach ($languages as $language) 
			        {	                                        		
			    ?>
			    		<tr class="TitleBlue11pt">
			    			<td valign="middle" align="left" width="22%">
			    				<img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>" /> <?= $language->languageName ?> Title
			            	</td>
			            	<td align="left" valign="middle">
			            		<input type="text" name="title_<?= $language->languageCode ?>" value="<?= htmlentities($newsPost->title->getText($language->languageCode)) ?>" size="120"/>
		            		</td>
			            </tr>                       
			    <?php 
			        }
			    ?>
			    		<tr class="TitleBlue11pt">
			    			<td valign="middle" align="left" width="22%">
			    				Category
			            	</td>
			            	<td align="left" valign="middle">
			            		<select name="category_id" style="width: 500px;">
			            		<?php
			            			$newsCategories = NewsCategory::fetchAllFromDb();
			            			foreach ($newsCategories as $newsCategory) 
			            			{
			            				echo '<option value="' . $newsCategory->id . '"' . ($newsCategory->id == $newsPost->categoryId ? ' selected' : '') . '>' . $newsCategory->title->getText($defaultLanguage->languageCode) . '</option>' . "\n";
			            			} 
			            		?>
			            		
			            		</select>			            		
		            		</td>
			            </tr>
		    			<tr><td colspan="2"><hr></td></tr>			    	
		        <?php 
			        
			        foreach ($languages as $language) 
			        {	                                        		
			    ?>
			    		<tr class="TitleBlue11pt">
			    			<td colspan="2">
			    				<img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>" /> <?= $language->languageName ?> Contents
			    				<br/><br />
			    				<textarea  class="ckeditor" name="contents_<?= $language->languageCode ?>"><?= htmlentities($newsPost->contents->getText($language->languageCode)) ?></textarea>
			            	</td>			            	
			            </tr>			          
			    		
			    		<tr><td colspan="2"><hr></td></tr>		                               
			    <?php 
			        }										
		        ?>
			        	<tr>
							<td class="TitleBlue11pt">Small Image (150 x 100) <?= trim($newsPost->imageSmall) != "" ? "<small>(Leave blank to preserve existing image)</small>" : "" ?></td>
		                    <td>
			                    <input type="file" size="120" name="image_small" value="" />
		                        <?php 
									if (trim($newsPost->imageSmall) != "")
									{
										echo "<br />\n";
										echo '<img src="../images/' . $newsPost->imageSmall . '" alt="" width="150px"' . "\n";
									}
								?>
		                    </td>
	                	</tr>
		                <tr>
							<td class="TitleBlue11pt">Medium Image (236 x 70) <?= trim($newsPost->imageMedium) != "" ? "<small>(Leave blank to preserve existing image)</small>" : "" ?></td>
		                    <td>
		                    	<input type="file" size="120" name="image_medium" value="" />
		                        <?php 
									if (trim($newsPost->imageMedium) != "")
									{
										echo "<br />\n";
										echo '<img src="../images/' . $newsPost->imageMedium . '" alt="" width="236px"' . "\n";
									}
								?>
		                    </td>
		                </tr>
		                <tr>
							<td class="TitleBlue11pt">Large Image (614 x 176) <?= trim($newsPost->imageLarge) != "" ? "<small>(Leave blank to preserve existing image)</small>" : "" ?></td>
		                    <td>
		                    	<input type="file" size="120" name="image_large" value="" />
		                        <?php 
									if (trim($newsPost->imageLarge) != "")
									{
										echo "<br />\n";
										echo '<img src="../images/' . $newsPost->imageLarge . '" alt="" width="614px"' . "\n";
									}
								?>
		                    </td>
		                </tr>                
		                <tr>
							<td class="TitleBlue11pt">Poster Name</td>
		                    <td><input type="text" size="120" name="poster_name" value="<?= $newsPost->posterName ?>" /></td>
		                </tr>
		                <tr>
							<td class="TitleBlue11pt">Date Posted</td>
							<td>
								<input name="date_posted" id="start-date" class="date-pick" value="<?= $newsPost->postedDate->format("m/d/Y") ?>" readonly="readonly">								
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
  </td>
</tr>
<?php include("footer.php"); ?>
</table>
<br />
</body>
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="../scripts/date.js"></script>
<script type="text/javascript" src="../scripts/jquery.datePicker.js"></script>
<!-- datePicker required styles -->
<link rel="stylesheet" type="text/css" media="screen" href="../css/datePicker.css">
<!-- page specific styles -->
<link rel="stylesheet" type="text/css" media="screen" href="../css/date.css">
<!-- page specific scripts -->
<script type="text/javascript" charset="utf-8">
Date.firstDayOfWeek = 0;
Date.format = 'mm/dd/yyyy';
$(function()
{	
	$('.date-pick').datePicker({startDate:'01/01/1996'});
	$('#start-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{			
		}
	);	
});
</script>
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

function validateNewsPost()
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