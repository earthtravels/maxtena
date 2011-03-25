<?php
include ("access.php");
include ("header.php");
include("../includes/conf.class.php");
if(isset($_REQUEST['id']))
$content_id=$bsiCore->ClearInput($_REQUEST['id']);

$c_update=0;
if(isset($_POST['submit1'])){
$c_update=mysql_query("update bsi_email_contents set email_subject='".$bsiCore->ClearInput($_POST['email_subj'])."', email_text='".htmlentities($_POST['contents'])."' where id=".$content_id);
}
$content_details=mysql_fetch_assoc(mysql_query("select * from bsi_email_contents where id=".$content_id));

?>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
</td>
  </tr> 
  <tr>
    <td  valign="top" align="left"><br />
    <form action="<?=$_SERVER['PHP_SELF']?>?id=<?=$content_id?>" method="post" enctype="multipart/form-data">
        <table cellpadding="5" cellspacing="0" border="0" width="900"  class="bodytext">
       
        <tr><td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold;"><?=$content_details['email_name']?></td></tr>
         <tr><td>Email Subject: <input type="text" name="email_subj" size="100" value="<?=$content_details['email_subject']?>" /></td></tr>
        <tr><td >Email Content:<br /><textarea class="ckeditor"  name="contents"  ><?=$content_details['email_text']?></textarea>
</td></tr>
<tr><td align="center"><input type="submit" value="UPDATE CONTENT" name="submit1" /></td></tr>
<?php
if ($c_update) {
?>
<tr><td style="font-family:Arial, Helvetica, sans-serif;  font-size:14px; color:#009900; font-weight:bold;">Content is updated!!</td></tr>
<?php } ?>
        </table>
      </form>
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
