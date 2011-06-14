<?php
// TODO: umcomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
global $logger;


$errors = array();

$logger->LogDebug("Fetching all promo codes ...");
$promoCodes = PromoCode::fetchAllFromDb();
if ($promoCodes == null)
{
	$logger->LogError("There were errors fetching nerws categories.");
	foreach (PromoCode::$staticErrors as $error) 
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
	?>	
		<fieldset>	
		    <legend class="TitleBlue11pt">Promotional Codes</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">
				<tr bgcolor="#747471">
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Code</font></b>
					</td>					
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Discount</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Min Purchase</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Min Nights</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Expiration</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Applicability</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Reusable</font></b>
					</td>
					<td scope="col" class="bodytext_h">&nbsp;</td>
				</tr>
			<?php 
					foreach ($promoCodes as $promoCode) 
					{
						if (!($promoCode instanceof PromoCode))
						{
							continue;
						}
						echo '<tr class="odd" bgcolor="#f2eaeb">' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $promoCode->promoCode . '</font></td>' . "\n";
						if ($promoCode->isPercentage)
						{
							echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $promoCode->discountAmount . '%</font></td>' . "\n";
						}	
						else 
						{
							echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $systemConfiguration->formatCurrency($promoCode->discountAmount) . '</font></td>' . "\n";
						}		
						if ($promoCode->minAmount > 0)
						{
							echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $systemConfiguration->formatCurrency($promoCode->minAmount) . '</font></td>' . "\n";
						}
						else
						{
							echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">N/A</font></td>' . "\n";
						}			
						if ($promoCode->minNights > 0)
						{
							echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' .$promoCode->minNights . '</font></td>' . "\n";
						}
						else
						{
							echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">N/A</font></td>' . "\n";
						}
						if ($promoCode->expirationDate != null)
						{
							echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $promoCode->expirationDate->format("m/d/Y") . '</font></td>' . "\n";
						}
						else
						{
							echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">N/A</font></td>' . "\n";
						}
						$applicability = "All customers";
						if ($promoCode->category == 1)
						{
							$applicability = "Only existing customers";							
						}
						else if ($promoCode->category == 2)
						{
							$applicability = "One specific customer";							
						}
						else if ($promoCode->category == 3)
						{
							$applicability = "Only new customers";							
						}
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $applicability . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . ($promoCode->isReusable ? "Yes" : "No") . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">';
						echo '		<a href="promo_codes_add_edit.php?id=' . $promoCode->id . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>&nbsp;&nbsp;';
						echo '		<a href="promo_codes_delete.php?id=' . $promoCode->id . '" style="text-decoration:none" onclick = "if (! confirm(\'Are you sure?\')) { return false; }"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Delete</font></a>';
						echo '	</td>' . "\n";
						echo "</tr>\n";	;
					}

					if(sizeof($promoCodes) == 0)
					{
						echo '<tr><td colspan="7">No promo codes are defined yet!</td></tr>' . "\n";
					}
			?>
			</table>							   
	    </fieldset>	
	    <table width="100%">
			<tr  bgcolor="#ffffff" class="TitleBlue11pt">
	            <td height="20" align="center">
	                <input type="image" value="1" src="images/button_add.png"  name="SBMT"  onclick="javascript:window.location.href='promo_codes_add_edit.php'">
	            </td>
	        </tr>
		</table>   
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
