<?php
// TODO: Uncomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
session_start();

$language_selected="en";
global $systemConfiguration;
global $logger;

$language_selected = "en";
if (!isset($_SESSION['bookingDetailsAdmin']))
{
	$_SESSION['errors'] = array (0 => "Invalid request: could not find booking request data");
	header ("Location: error.php");
}

// Get booking details from session
$bookingDetails = unserialize($_SESSION['bookingDetailsAdmin']);

// Get selected extra bed
if (isset($_POST['extraBed']) && is_numeric($_POST['extraBed']) && intval($_POST['extraBed']) > 0)
{
    $bookingDetails->extraBedRequested = true;
}

// Get selected services
if (isset($_POST['extraServices']))
{
    $bookingDetails->extraServices = array();
    foreach ($_POST['extraServices'] as $serviceId => $quantity)
    {
        $extraService = ExtraService::fetchFromDb(intval($serviceId));
        if ($extraService == null)
        {
            $_SESSION['errors'] = array (0 => "Invalid request: could not find additional service with id: " . intval($serviceId));
            header ("Location: error.php");
        }
        if (intval($quantity) > 0)
        {
            $extraServiceDetails = array ( 0 => $extraService, 1 => $quantity, 2 => floatval($extraService->price * $quantity ) );
            $bookingDetails->extraServices[] = $extraServiceDetails;
        }
    }
}

// Calculate price details
$bookingDetails->calculatePriceDetails($language_selected);

// Get entered promo code and client info
$promoErrorMessage = null;
$clientEmailAddress = null;
$client = new Client();
if (isset($_POST['promo_code']))
{
	$client = Client::fetchFromParameters($_POST);
    $bookingDetails->promoCode = null;
    if (isset($_POST['email']))
    {
       $clientsEmailAddress = $_POST['email'];
    }
    $promoCode = PromoCode::fetchFromDBForCode($_POST['promo_code']);
    if ($promoCode == null)
    {   
        $promoErrorMessage = PromoCode::$staticErrors[0];
    }
    else if ($promoCode->isApplicable($bookingDetails->priceDetails->grandTotal, $clientsEmailAddress, $bookingDetails->searchCriteria->getNightCount(), $promoErrorMessage))
    {
        $bookingDetails->promoCode = $promoCode;
        $bookingDetails->calculatePriceDetails($language_selected);
    }
}
    


// Save booking details to session
$bookingDetailsSerialized = serialize($bookingDetails);
$_SESSION['bookingDetailsAdmin'] = $bookingDetailsSerialized;

include ("header.php");
?>

<!-- jquery.datePicker.js -->
<script type="text/javascript" src="../js/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="../js/date.js"></script>
<script type="text/javascript" src="../js/jquery.datePicker.js"></script>

</td>
  </tr> 
  
  <tr>
    <td valign="top" >    
    <fieldset>
	    <legend class="TitleBlue11pt">Additional Services</legend>
	    <form name="book_services" method="post" action="booking_step5.php"> 
	    	<table cellspacing="1" border="0" cellpadding="3" class="TitleBlue11pt" width="100%">                               		
					<tr>
						<th align="center" width="33%">
							Check In
						</th>
						<th  align="center" width="33%">
							Check Out
						</th>
						<th  align="center">
							Total Nights
						</th>                                   
					</tr>
					<tr>
						<td align="center">
							<?= $bookingDetails->searchCriteria->checkInDate->format("m/d/Y") ?>
						</td>
						<td  align="center">
							<?= $bookingDetails->searchCriteria->checkOutDate->format("m/d/Y") ?>
						</td>
						<td  align="center">
							<?= $bookingDetails->searchCriteria->getNightCount() ?>
						</td>                                    
					</tr>
					<tr>
						<th  align="center">
							Accommodation Number
						</th>
						<th  align="center">
							Accommodation Name
						</th>
						<th  align="center">
							Total Occupants
						</th>                                   
					</tr>
					<tr>
						<td  align="center">
							<?= $bookingDetails->room->roomNumber ?>
						</td>
						<td  align="center">
							<?= $bookingDetails->room->roomName ?>
						</td>
						<td  align="center">
							<?= intval($bookingDetails->searchCriteria->adultsCount) +  intval($bookingDetails->searchCriteria->childrenCount) ?>
						</td>                                    
					</tr>
					<tr><td colspan="3">&nbsp;</td></tr>
					<tr>
						 <td colspan="2" style="text-align: right;">
							Accommodation Total
						</td>
						<td style="text-align: right;">
							<?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->roomPrice) ?>
						</td>
					</tr>					                               
				 <?php
					if ($bookingDetails->priceDetails->extraBedPrice > 0)
					{                       		?>
						<tr>
							 <td colspan="2" style="text-align: right;">
								Extra Bed
							</td>
							<td style="text-align: right;">
								<?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->extraBedPrice) ?>
							</td>
						</tr>
				<?php									
					}
				
					if (sizeof($bookingDetails->priceDetails->extraServicesDetail) > 0)
					{
						foreach($bookingDetails->priceDetails->extraServicesDetail as $extraServiceDetails)
						{
							$extraService = $extraServiceDetails[0];
							$quantity = intval($extraServiceDetails[1]);
							$totalPrice = floatval($extraServiceDetails[2]);
				?>
							<tr>
								 <td colspan="2" style="text-align: right;">
									<?= $extraService->getName($language_selected) . " (" . $systemConfiguration->formatCurrency($extraService->price) .  " x " . $quantity . ")"   ?>
								</td>
								<td style="text-align: right;">
									<?= $systemConfiguration->formatCurrency($totalPrice) ?>
								</td>
							</tr>
				<?php 
						}
					}
					if (sizeof($bookingDetails->priceDetails->extraServicesDetail) > 0 || $bookingDetails->priceDetails->extraBedPrice > 0)
					{
				?>
						<tr>
							 <th colspan="2" style="text-align: right;">
								Subtotal
							</th>
							<td style="text-align: right;">
								<?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->subtotalBeforeDiscounts) ?>
							</td>
						</tr>
				<?php 
					}
					
					if ($bookingDetails->priceDetails->monthlyDiscount > 0)
					{
				?>
						<tr>
							 <td colspan="2" style="text-align: right;">
								Seasonal Discount (<?= $bookingDetails->priceDetails->monthlyDiscountPercent ?> %)
							</td>
							<td style="text-align: right;">
								(- <?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->monthlyDiscount) ?>)
							</td>
						</tr>
				
				<?php
					} 
					
					if ($bookingDetails->priceDetails->promoDiscount > 0)
					{
				?>   
						<tr>
							 <td colspan="2" style="text-align: right;">
								Promo Code <?= "(" . $bookingDetails->priceDetails->promoCode . ")" ?>
							</td>
							<td style="text-align: right;">
								(- <?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->promoDiscount) ?>)
							</td>
						</tr>
				<?php
					} 
					
					if ($bookingDetails->priceDetails->monthlyDiscount > 0 || $bookingDetails->priceDetails->promoDiscount > 0)
					{
				?>
						<tr>
							 <th colspan="2" style="text-align: right;">
								Subtotal
							</td>
							<td style="text-align: right;">
								<?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->subtotalAfterDiscounts) ?>
							</td>
						</tr>
				
				<?php
					}
					
					if ($bookingDetails->priceDetails->taxAmount > 0)
					{
				?>   
						<tr>
							 <td colspan="2" style="text-align: right;">
								Tax (<?= $bookingDetails->priceDetails->taxRate ?> %)
							</td>
							<td style="text-align: right;">
								<?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->taxAmount) ?>
							</td>
						</tr>	                                
				<?php
					} 
				?>
						<tr>
							 <th colspan="2" style="text-align: right;">
								Grand Total
							</td>
							<td style="text-align: right;">
								<?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->grandTotal) ?>
							</td>
						</tr>
				<?php 
					
					if ($bookingDetails->priceDetails->monthlyDepositPercent > 0)
					{
				?>                          
						<tr>                                	
							<th colspan="2" style="text-align: right;">
								Deposit <?= "(" . $bookingDetails->priceDetails->monthlyDepositPercent . " %)"  ?>
							</th>
							<td style="text-align: right;">
								<?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->totalDue) ?>
							</td>
						</tr>
				<?php
					}
				?>
				                    
				<tr><td colspan="3">&nbsp;</td></tr>
				<tr>
					<td>
						First Name
					</td>
					<td colspan="2">
						<input type="text" name="first_name" />
					</td>
				</tr>
				<tr>
					<td>
						Middle Name
					</td>
					<td colspan="2">
						<input type="text" name="middle_name" />
					</td>
				</tr>
				<tr>
					<td>
						Last Name
					</td>
					<td colspan="2">
						<input type="text" name="last_name" />
					</td>
				</tr>
				<tr>
					<td>
						Street Address
					</td>
					<td colspan="2">
						<input type="text" name="street_address" />
					</td>
				</tr>
				<tr>
					<td>
						City
					</td>
					<td colspan="2">
						<input type="text" name="city" />
					</td>
				</tr>
				<tr>
					<td>
						State
					</td>
					<td colspan="2">
						<input type="text" name="state" />
					</td>
				</tr>
				<tr>
					<td>
						Country
					</td>
					<td colspan="2">
						<input type="text" name="country" />
					</td>
				</tr>
				<tr>
					<td>
						Phone
					</td>
					<td colspan="2">
						<input type="text" name="phone" />
					</td>
				</tr>
				<tr>
					<td>
						Email
					</td>
					<td colspan="2">
						<input type="text" name="email" />
					</td>
				</tr>				
				<tr>				
    				<td align="center" colspan="3">
    					<br />
    					<input type="image" value="1" src="images/button_finish.png"  name='SBMT_FORM' />
    				</td>
    			</tr>					
	    	</table>
    	</form>			
    </fieldset>   
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>
</body>
</html>
