<?php
// TODO: uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");
include ("header.php");

global $systemConfiguration;
global $logger;
?>


</td>
</tr>
<tr>
	<td height="400" valign="top" align="left">		
		<!--################################################# -->
		<table align="left" width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="left" valign="top">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr bgcolor="#666666" height="25">
							<td colspan="7" align="left" width="100%"><font color="#FFFFFF"
								face="Arial, Helvetica, sans-serif"><b>&nbsp;Additional Services</b></font>&nbsp;&nbsp;&nbsp;
								<input type="button" value='Add New Service' class="lnk"
									onclick="javascript:window.location.href='extra_services_add_edit.php'"
									align="right">
							</td>
						</tr>
					</table>
					
					<table width="100%" cellspacing="1" border="0" cellpadding="3"
						style="border: solid 1px #666666" bordercolor="#666666">
						<tr bgcolor="#FFFFFF">
							<td scope="col" align="left"><b><font color="#666666"
								face="Verdana, Arial, Helvetica, sans-serif" size="2">Service Name</font></b>
							</td>
							<td scope="col" align="left"><b><font color="#666666"
								face="Verdana, Arial, Helvetica, sans-serif" size="2">Nightly?</font></b>
							</td>							
							<td scope="col" align="left"><b><font color="#666666"
								face="Verdana, Arial, Helvetica, sans-serif" size="2">Available Amount</font></b>
							</td>
							<td scope="col" align="left"><b><font color="#666666"
								face="Verdana, Arial, Helvetica, sans-serif" size="2">Price</font></b></td>
							<td scope="col" class="bodytext_h">&nbsp;</td>
						</tr>
						<?php 
							$extraServicesQuery = mysql_query ("SELECT * from bsi_extra_services order by id");
							while ($extraServiceRow = mysql_fetch_assoc ($extraServicesQuery))
							{								
								echo '<tr class="odd" bgcolor="#f2eaeb">' . "\n";
								echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($extraServiceRow['service_name_en']) . '</font></td>' . "\n";
								echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . (intval($extraServiceRow['is_nightly']) == 1 ? 'Yes' : 'No') . '</font></td>' . "\n";
								$maxAvailable = intval($extraServiceRow['max_available']);
								if (intval($extraServiceRow['is_nightly']) == 1)
								{
									$maxAvailable = "N/A";
								}	
								echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $maxAvailable . '</font></td>' . "\n";								
								echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $systemConfiguration->formatCurrency($extraServiceRow['price']) . '</font></td>' . "\n";                                    
								echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="extra_services_add_edit.php?id=' . $extraServiceRow['id'] . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Edit</a>&nbsp;&nbsp;<a href="extra_services_delete.php?id=' . $extraServiceRow['id'] . '" onclick = "if (! confirm(\'Are you sure?\')) { return false; }" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Delete</a></font></td>' . "\n";
								echo "</tr>\n";								
							}
						?>
					</table>				
				</td>
			</tr>
		</table>
		<br />	
		<!--################################################# -->
	</td>
</tr>
<?php include("footer.php"); ?>
</table>
</body>
</html> 
    