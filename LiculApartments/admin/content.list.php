<?php
include("access.php");
include ("../includes/SystemConfiguration.class.php");

include ("header.php"); 

$light="#666666"; 
?>
</td>
</tr>

<tr>
  <td height="400" valign="top" align="left">
  <?php
if(isset($_GET['del'])):
   mysql_query("delete from bsi_site_contents where id=" .$_GET['del']);
endif;
?>
    <form method="post" action="<?=$_SERVER['PHP_SELF']?>">
      <table width="100%" border="0"  cellspacing="0" cellpadding="0" bgcolor="<?=$light?>" style="border:solid 1px <?=$light?>">
        <tr bgcolor="<?=$light?>" >
          <td height="25">&nbsp;&nbsp;<font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><b>Website Menu &amp; Content Manager</b></font> &nbsp;&nbsp;&nbsp;
           <img src="images/add_menu.png" border="0" onclick="javascript:window.location.href='page_editor.php'" align="absmiddle" style="cursor:pointer; cursor:hand;"/></td>
          <td bgcolor="<?=$light?>" width=40% align="right"></td>
        </tr>
      </table>
      <table id="table-1" width="100%"  border="0" cellspacing="1" cellpadding="4"  style="border:solid 1px <?=$light?>">
        <thead>
          <tr bgcolor="">
            <td class="TitleRed11pt" width="30%">&nbsp;Main Menu</td>
            <td class="TitleRed11pt" width="20%">Sub Menu</td>
            <td class="TitleRed11pt" width="20%">Sub Sub Menu</td>
            <td class="TitleRed11pt" width="8%">Order</td>
            <td class="TitleRed11pt" width="7%">Status</td>
            <td class="TitleRed11pt" width="15%">Action</td>
          </tr>
        </thead>
        <tbody>
          <?php
if(!$_GET['limit']){$_GET['limit']=1;}
if($_POST['SBMT_SEARCH'] && $_POST['search'] && $_POST['param']=="id"):
  		$cond="id=" . $_POST['search'];		
elseif($_POST['SBMT_SEARCH'] && $_POST['param'] && $_POST['search']):
 			$cond=$_POST['param']  . " rlike '" . $_POST['search'] . "'";
endif;
if($cond){$cond2=" where " . $cond;}
$j=0;$tids=array();
if($_REQUEST['tid']):
  $tids=explode("|",$_REQUEST['tid']);
endif;
$r=mysql_query("select * from bsi_site_contents where parent_id=0 order by ord");
$row_default_lang=mysql_fetch_assoc(mysql_query("select * from bsi_language where is_active=true"));
while($d=@mysql_fetch_array($r)):
    if(!($j%2)){$class="even";}else{$class="odd";}
	echo"<tr bgcolor=#ffffff class=even><td class='bodytext'>";
	if(in_array($d['id'],$tids)):
        echo"<a href='" . $_SERVER['PHP_SELF'] . "?tid=". str_replace("| " . $d['id'] . "|","|",$tid) .  "'\"><img src='images/001_02.png' border=0 align='absmiddle'></a>&nbsp;";
    else:
        $n=@mysql_num_rows(mysql_query("select * from bsi_site_contents where parent_id=" . $d[id] . " order by ord"));
	    if($n):  
	        echo"<a href='" . $_SERVER['PHP_SELF'] . "?tid=|" . $d['id'] . "|'\"><img src='images/001_01.png' border=0 align='absmiddle' ></a>&nbsp;"; 
        else:
		    echo"<img src='images/001_09.png' border=0 align='absmiddle'>&nbsp;"; 
		endif;
	endif;
	
	if($d['status']=='Y'){$stat1="Active";}else{$stat1="Hidden";}
    echo"<a  href='page_editor.php?id=" . $d['id'] . "' class='bodytext'>" . $d['title_'.$row_default_lang['language_code']] . "</a></td>
	<td></td><td></td><td class='bodytext'>".$d['ord']."</td><td class='bodytext'>".$stat1."</td><td><a class='bodytext' href='page_editor.php?id=" . $d['id'] . "'><img src='images/button_edit.gif' border='0'/></a>&nbsp;<a class='bodytext' href='content.list.php?tid=" . $_REQUEST['tid'] . "&del=" . $d['id'] . "'><img src='images/button_delete1.gif' border='0'/></a> </td></tr>";
    if(in_array($d['id'],$tids)):
		$rr=mysql_query("select * from bsi_site_contents where parent_id=" . $d['id'] . " order by ord");
		//echo"select * from delta_contents where parent_id=" . $d[id] . " order by ord";
		$k=0;
		while($dd=mysql_fetch_array($rr)):
	        if($dd['status']=='Y'){$stat2="Active";}else{$stat2="Hidden";}
	        echo"<tr bgcolor=whitesmoke><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|______________________________</td><td><a  class='bodytext' href='page_editor.php?id=" . $dd['id'] . "'>" . $dd['title_'.$row_default_lang['language_code']] . "</a></td><td></td><td class='bodytext'>".$dd['ord']."</td><td class='bodytext'>".$stat2."</td><td> <a class='bodytext' href='page_editor.php?id=" . $dd['id'] .  "'><img src='images/button_edit.gif' border='0'/></a>&nbsp;<a class='bodytext' href='content.list.php?tid=" . $_REQUEST['tid'] . "&del=" . $dd['id'] . "'><img src='images/button_delete1.gif' border='0'/></a> </td></tr>";
		    $r3=mysql_query("select * from bsi_site_contents where parent_id=" . $dd['id'] . " order by ord");
			//echo"select * from delta_contents where parent_id=" . $dd[id] . " order by ord";
	        $k=0;
	        while($d3=mysql_fetch_array($r3)):
			if($d3['status']=='Y'){$stat3="Active";}else{$stat3="Hidden";}
			    echo"<tr bgcolor=whitesmoke><td></td><td>|_____________________<td><a href='page_editor.php?id=" . $d3['id'] . "&parent_id=" . $dd['id'] . "' class='bodytext'>" . $d3['title_'.$row_default_lang['language_code']] . "</td><td class='bodytext'>".$d3['ord']."</td><td class='bodytext'>".$stat3."</td><td> <a href='page_editor.php?id=" . $d3['id'] . "' class='bodytext'><img src='images/button_edit.gif' border='0'/></a>&nbsp;<a class='bodytext' href='content.list.php?tid=" . $_REQUEST['tid'] . "&del=" . $d3['id'] . "'><img src='images/button_delete1.gif' border='0'/></a> </td></tr>";
		    endwhile;		
		    $k++;
        endwhile;
	endif;	
	$j++;
endwhile;   
?>
        </tbody>
      </table>
    </form>
   </td>
</tr>
<?php include("footer.php"); ?>
</table><br />
</body></html>