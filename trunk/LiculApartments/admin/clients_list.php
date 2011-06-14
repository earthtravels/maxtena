<?php
// TODO: Uncomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
global $logger;

$clients = Client::fetchAllFromDb();

include ("header.php");
?>

</td>
  </tr> 
  
  <tr>
    <td valign="top" >    
    <fieldset>
	    <legend class="TitleBlue11pt">Clients</legend>
	    	<table cellspacing="1" border="0" cellpadding="3">				
				<tr bgcolor="#747471">
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">First Name</font></b>
					</td>												
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Middle Name</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Last Name</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Street Address</font></b>
					</td>					
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">City</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">State</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Zip</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Country</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Email</font></b>
					</td>					
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Phone</font></b>
					</td>
					<td scope="col" align="center">&nbsp;</td>					
				</tr>
				<?php
				foreach ($clients as $client) 
				{
					echo '<tr class="odd" bgcolor="#f2eaeb">' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $client->firstName . '</font></td>' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $client->middleName . '</font></td>' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $client->lastName . '</font></td>' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $client->streetAddress . '</font></td>' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $client->city . '</font></td>' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $client->state . '</font></td>' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $client->zip . '</font></td>' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $client->country . '</font></td>' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $client->email . '</font></td>' . "\n";		
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $client->phone . '</font></td>' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">';
					echo '		<a href="clients_add_edit.php?id=' . $client->id . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>' . "\n";
					echo '		<br/><br/><a href="clients_delete.php?id=' . $client->id . '" style="text-decoration:none" onclick = "if (! confirm(\'Are you sure?\')) { return false; }"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Delete</font></a>';
					echo '	</td>' ."\n";			
					echo "</tr>\n";							
					
				} 
				?>
			</table>
    </fieldset>    
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>
</body>
</html>
