<?php
// TODO: Uncomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
global $logger;

$newsletterSubscriptions = NewsletterSubscription::fetchAllFromDb();

include ("header.php");
?>

</td>
  </tr> 
  
  <tr>
    <td valign="top" >    
    <fieldset>
	    <legend class="TitleBlue11pt">Newsletter Subscriptions</legend>
	    	<table cellspacing="1" border="0" cellpadding="3" width="100%">				
				<tr bgcolor="#747471">
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Email</font></b>
					</td>												
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Active</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Subscription Date</font></b>
					</td>					
					<td scope="col" align="center">&nbsp;</td>					
				</tr>
				<?php
				foreach ($newsletterSubscriptions as $newsletterSubscription) 
				{
					echo '<tr class="odd" bgcolor="#f2eaeb">' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($newsletterSubscription->email) . '</font></td>' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . ($newsletterSubscription->isActive ? 'Yes' : 'No') . '</font></td>' . "\n";
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $newsletterSubscription->subscriptionDate->format("m/d/Y") . '</font></td>' . "\n";					
					echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">';
					echo '				  <a href="newsletter_subscriptions_add_edit.php?id=' . $newsletterSubscription->id . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>' . "\n";
					echo '		<br/><br/><a href="newsletter_subscriptions_deactivate.php?id=' . $newsletterSubscription->id . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Deactivate</font></a>' . "\n";
					echo '		<br/><br/><a href="newsletter_subscriptions_delete.php?id=' . $newsletterSubscription->id . '" style="text-decoration:none" onclick = "if (! confirm(\'Are you sure?\')) { return false; }"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Delete</font></a>';
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
