<?php
include ("access.php");
if(isset($_REQUEST['pid'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$is_delete=$bsiAdminMain->gallery_photo_delete(2);
	if($is_delete)
	header("location:admin_home_slider_gallery.php");
}
if(isset($_POST['sbt_lang'])){
    include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->slider_gallery_img_upload();
	header("location:admin_home_slider_gallery.php");
}
include ("header.php");
include("../includes/admin.class.php");
?>

<link href="css/pagination.css" rel="stylesheet" type="text/css">
	</td>
  </tr> 
  
  <tr>
    <td height="400" valign="top" align="left">
    <?php
	if(isset($_REQUEST['upload_photo_form']))
	{
		$upload_image_limit = 2;
		$i=0;
		$form_img="";
		$htmo="";
	 ############################### HTML FORM
	while($i++ < $upload_image_limit){
		$form_img .= '<tr><td>Image '.$i.': </td><td class="bodytext8pt"><input type="file" name="uplimg'.$i.'">&nbsp;(For better image quality upload image size: 980px × 313px)</td></tr>';
		$form_img .= '<tr><td>Description : </td><td><input type="text" name="desc[]" size="100"></td></tr>';
		$form_img .= '<tr><td colspan="2">&nbsp;</td></tr>';
	}

	$htmo .= '
		<table cellpadding="6" cellspacing="0" border="0" style="font-size:13px; border:solid #999999 2px;">
		<tr><td colspan="2" class="TitleRed11pt" align="center">Home Slider Gallery Photo Upload</td></tr>
		<form method="post" enctype="multipart/form-data"><input  type="hidden" value="11" name="sbt_lang" />
			'.$form_img.' 
			<tr><td align="center" colspan="2"><input type="submit" value="Upload Images!" style="margin-left: 50px;" /></td></tr>
			
		</form>
		</table>
		';	

	echo $htmo;
	
	} else { ?>
    <table cellpadding="5" cellspacing="0" border="0">
    <tr><td ><a href="<?=$_SERVER['PHP_SELF']?>?upload_photo_form=1"><img src="images/bttn_upload.png" border="0" /></a></td></tr>
    
    	<?php
		if(isset($_GET['page']))
		$pagination_gallery=$bsiAdminMain->pagination('bsi_gallery',2,$_GET['page'],'admin_home_slider_gallery.php',2);
		else
		$pagination_gallery=$bsiAdminMain->pagination('bsi_gallery',2,0,'admin_home_slider_gallery.php',2);
		
		while($row_gallery = mysql_fetch_array($pagination_gallery['pagination_return_sql']))
		{
	    
		echo '<tr><td  align="center"><img src="../gallery/'.$row_gallery[1].'" style="border:solid 1px #FFCC00"></td></tr>';
		
		echo "<tr><td>".$row_gallery[2]."<a href='".$_SERVER['PHP_SELF'].'?pid='.base64_encode($row_gallery[0])."'><img src=\"images/button_delete.gif\" border=\"0\"  align=\"right\"/></a></td></tr>";	
		echo "<tr><td>&nbsp;</td></tr>";
		
		}
		
	?>
    
    <tr><td><?=$pagination_gallery['page_list']?></td></tr>
    </table>
    <?php } ?>
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
