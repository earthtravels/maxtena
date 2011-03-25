<?php
include ("access.php");
include ("header.php");
include ('../includes/conf.class.php'); 
include("../includes/language.admin.php");
include ('../includes/details.class.php');
$bsiCore->clearExpiredBookings();
$bsibooking = new bsiBookingDetails(); 
?>
<script language="JavaScript" type="text/javascript">
<!--
function checkform ( form )
{
  // see http://www.thesitewizard.com/archive/validation.shtml
  // for an explanation of this script and how to use it on your
  // own website

  // ** START **
  if (form.fname.value == "") {
    alert( "Please enter customer first name." );
    form.fname.focus();
    return false ;
  }
  if (form.lname.value == "") {
    alert( "Please enter customer last name." );
    form.lname.focus();
    return false ;
  }
  if (form.str_addr.value == "") {
    alert( "Please enter customer Steet Address." );
    form.str_addr.focus();
    return false ;
  }
  if (form.city.value == "") {
    alert( "Please enter customer City." );
    form.city.focus();
    return false ;
  }
  if (form.state.value == "") {
    alert( "Please enter customer State." );
    form.state.focus();
    return false ;
  }
  if (form.zipcode.value == "") {
    alert( "Please enter customer Post Code." );
    form.zipcode.focus();
    return false ;
  }
  if (form.country.value == "") {
    alert( "Please enter customer Country." );
    form.country.focus();
    return false ;
  }
  if (form.phone.value == "") {
    alert( "Please enter customer phone number." );
    form.phone.focus();
    return false ;
  }
  if (form.email.value == "") {
    alert( "Please enter customer email." );
    form.email.focus();
    return false ;
  }
  // ** END **
  return true ;
}
//-->
</script>
</td>
</tr>
<tr>
 <td height="400" valign="top" align="left">  
<h2><em><strong><?=BOOKING_DETAILS_TITLE?></strong></em></h2>
      <p>
      <?php $bookingDetails = $bsibooking->generateBookingDetails(); ?>
      <table cellpadding="4" cellspacing="1" border="0" width="90%" bgcolor="#FFFFFF" style="font-size:12px">
      	<tr>
          <td bgcolor="#c7b998" align="center"><strong><?=LEFT_CHECK_IN_DT?></strong></td>
          <td bgcolor="#c7b998" align="center"><strong><?=LEFT_CHECK_OUT_DT?></strong></td>
          <td bgcolor="#c7b998" align="center"><strong><?=LEFT_TOTAL_NIGHT?></strong></td>
          <td bgcolor="#c7b998" align="center"><strong>Total Rooms</strong></td>
        </tr> 
        <tr>
          <td align="center" bgcolor="#ece7db"><?=$bsibooking->checkInDate?></td>
          <td align="center" bgcolor="#ece7db"><?=$bsibooking->checkOutDate?></td>
          <td align="center" bgcolor="#ece7db"><?=$bsibooking->nightCount?></td>
          <td align="center" bgcolor="#ece7db"><?=$bsibooking->totalRoomCount?></td>          
        </tr>
        <tr>
          <td bgcolor="#c7b998" align="center"><strong><?=BOOKING_DETAILS_ROOM_NUMBER?></strong></td>
          <td bgcolor="#c7b998" align="center"><strong><?=BOOKING_DETAILS_ROOM_TYPE?></strong></td>
          <td bgcolor="#c7b998" align="center"><strong><?=LEFT_CAPACITY?></strong></td>
          <td bgcolor="#c7b998" align="right"><strong><?=BOOKING_DETAILS_GROSS_TOTAL?></strong></td>
        </tr>
        <?php		
		foreach($bookingDetails as $bookings){		
			echo '<tr>';
			echo '<td align="center" bgcolor="#ece7db">'.$bookings['roomno'].'</td>';
			echo '<td align="center" bgcolor="#ece7db">'.$bookings['roomtype'].' ('.$bookings['capacitytitle'].')</td>';
			if($bookings['maxchild'] > 0){				
				if($bookings['extrabed'] == "yes"){
					echo '<td align="center" bgcolor="#ece7db">'.$bookings['capacity'].' Adult + '.$bookings['maxchild'].'  Child <br>Including Extra Bed</td>';
				}else{
					echo '<td align="center" bgcolor="#ece7db">'.$bookings['capacity'].' Adult + '.$bookings['maxchild'].' Child</td>';
				}
			}else{				
				if($bookings['extrabed'] == "yes"){
					echo '<td align="center" bgcolor="#ece7db">'.$bookings['capacity'].' Adult <br>Including Extra Bed</td>';
				}else{
					echo '<td align="center" bgcolor="#ece7db">'.$bookings['capacity'].' Adult</td>';
				}
			}		
			echo '<td align="right" bgcolor="#ece7db">'.$bsiCore->config['conf_currency_symbol'].number_format($bookings['grosstotal'], 2 , '.', ',').'</td>';
			echo '</tr>';		
		}
		
 		if($bsibooking->listHotelExtraService){					
			echo '<tr><td colspan="4" bgcolor="#c7b998"><strong>Hotel Extras</strong></td></tr>';
			foreach($bsibooking->listHotelExtraService as $hextra){
                echo '<tr><td colspan="3"  bgcolor="#ece7db"><img src="graphics/checked.gif" align="absmiddle"/> '.$hextra['description'].'</td><td align="right" bgcolor="#ece7db">&nbsp;'.$bsiCore->config['conf_currency_symbol'].$hextra['price'].'</td></tr>';
            }              			
		}
		?>       
        
        <tr>
          <td colspan="3" align="right" bgcolor="#c7b998"><strong><?=BOOKING_DETAILS_COMULATIVE_TOTAL?></strong></td>
          <td bgcolor="#c7b998" align="right"><strong><?=$bsiCore->config['conf_currency_symbol']?><?=number_format($bsibooking->roomPrices['subtotal'], 2 , '.', ',')?></strong></td>
        </tr>
        
        <?php
		if($bsiCore->config['conf_enabled_discount'] && $bsibooking->discountPlans['discount_percent'] > 0){
		?>
        <tr>
          <td colspan="3" align="right" bgcolor="#ece7db">Monthly Discount Scheme (<span id="mothlydicountdisplaytext" style="font-size:11px;"><?=$bsibooking->discountPlans['discount_percent']?>%</span>)</td>
          <td align="right" bgcolor="#ece7db">(-) <?=$bsiCore->config['conf_currency_symbol'].number_format($bsibooking->roomPrices['mothlydiscount'], 2 , '.', ',')?></span></td>
        </tr>
        <?php
		}
		?>
        
        <tr id="discount_coupon_tr">
          <td colspan="3" align="right" bgcolor="#ece7db"><img src="graphics/checked.gif" align="absmiddle"/> Discount Coupon (<span id="coupondisplay" style="font-size:11px;">Apply Discount Coupon if you have</span>) </td>
          <td align="right" bgcolor="#ece7db">(-) <?=$bsiCore->config['conf_currency_symbol']?><span id="dicountdisplay">0.00</span></td>
        </tr>
        
        <tr>
          <td colspan="3" align="right" bgcolor="#ece7db"><?=BOOKING_DETAILS_TAX?>
            (<?=$bsiCore->config['conf_tax_amount']?>%)</td>
          <td align="right" bgcolor="#ece7db">(+) <?=$bsiCore->config['conf_currency_symbol']?><span id="taxamountdisplay"><?=number_format($bsibooking->roomPrices['totaltax'], 2 , '.', ',')?></span></td>
        </tr>
        <tr>
          <td colspan="3" align="right" bgcolor="#c7b998"><strong><?=BOOKING_DETAILS_GRAND_TOTAL?></strong></td>
          <td align="right" bgcolor="#c7b998"><strong><?=$bsiCore->config['conf_currency_symbol']?><span id="grandtotaldisplay"><?=number_format($bsibooking->roomPrices['grandtotal'], 2 , '.', ',')?></span></strong></td>
        </tr>
        <?php 
		if($bsiCore->config['conf_enabled_deposit'] && ($bsibooking->discountPlans['deposit_percent'] > 0 && $bsibooking->discountPlans['deposit_percent'] < 100)){		
		?>
        <tr id="advancepaymentdisplay">		
          <td colspan="3" align="right" bgcolor="#c7b998"><strong>Advance Payment Amount</strong> (<span style="font-size:11px;"><?=$bsibooking->discountPlans['deposit_percent']?>% of Grand Total</span>)</td>
          <td align="right" bgcolor="#c7b998"><strong><?=$bsiCore->config['conf_currency_symbol']?><span id="advancepaymentamount"><?=number_format($bsibooking->roomPrices['advanceamount'], 2 , '.', ',')?></span></strong></td>
        </tr>
        <?php
        }?> 
      </table>

      </p>
      <p>
      <form method="post" action="admin.rmbookprocess.php" id="signupform" class="signupform" onsubmit="return checkform(this);">
        <input type="hidden" name="allowlang" id="allowlang" value="no" />    
        <table cellpadding="4" cellspacing="0" border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:13px;">
          <tr>
            <td><?=BOOKING_DETAILS_CLIENT_TITLE?>
              :</td>
            <td id="title"><select name="title" class="textbox3">
                <option value="Mr.">Mr.</option>
                <option value="Ms.">Ms.</option>
                <option value="Mrs.">Mrs.</option>
                <option value="Miss.">Miss.</option>
                <option value="Dr.">Dr.</option>
                <option value="Prof.">Prof.</option>
              </select></td>
            <td></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_FNAME?>
              :</td>
            <td><input type="text" name="fname" id="fname"  size="35" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_LNAME?>
              :</td>
            <td><input type="text" name="lname" id="lname" size="35" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_STR_ADDR?>
              :</td>
            <td><input type="text" name="str_addr" id="str_addr"  size="35" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_CITY?>
              :</td>
            <td><input type="text" name="city"  id="city" size="35" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_STATE?>
              :</td>
            <td><input type="text" name="state"  id="state" size="35" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_ZIP?>
              :</td>
            <td><input type="text" name="zipcode"  id="zipcode" size="35" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_COUNTRY?>
              :</td>
            <td><input type="text" name="country"  id="country" size="35" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_PHONE?>
              :</td>
            <td><input type="text" name="phone"  id="phone" size="35" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_FAX?>
              :</td>
            <td><input type="text" name="fax"  id="fax" size="35" /></td>
            <td></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_EMAIL?>
              :</td>
            <td><input type="text" name="email"  id="email" class="textbox" />
            <input type="hidden" name="payment_type" id="payment_type_admin" value="admin" /></td>
            <td  class="status"></td>
          </tr>
          
          <tr>
            <td valign="top" nowrap="nowrap"><?=BOOKING_DETAILS_ADDITIONAL_REQUEST?>
              :</td>
            <td colspan="2"><textarea name="message" rows="2" cols="22" class="textarea"></textarea></td>
          </tr>
          
          <tr>
            <td></td>
            <td><input type="submit" value="<?=BOOKING_DETAILS_CHECKOUT_BTN?>" class="button" /></td>
            <td></td>
          </tr>
        </table>
      </form>
      </p>
</td>     
</tr>
<?php include("footer.php"); ?>
</table>
</body></html>