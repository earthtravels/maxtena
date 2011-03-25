<?php
/**
* @package BSI
* @author BestSoft Inc see README.php
* @copyright BestSoft Inc.
* See COPYRIGHT.php for copyright notices and details.
*/

class bsiMail
{
	private $isSMTP 		= false;
	private $emailFrom 		= '';
	private $emailReplyTo 	= '';
    private $smtpHost 		= NULL;	
	private $smtpPort 		= NULL;
	private $smtpUserName 	= NULL;			
	private $smtpPassword 	= NULL;
	private $emailTo 		= '';
	private $emailSubject 	= '';
	private $emailBody 		= '';	
		
	public $emailContent	= array();		
	
	function bsiMail() {
		/**
		 * Global Ref: conf.class.php
		 **/
		global $bsiCore;		
					
		$this->isSMTP = $bsiCore->config['conf_smtp_mail'];	
			
		if($this->isSMTP == "true"){			
			$this->emailFrom = $bsiCore->config['conf_hotel_name']."<".$bsiCore->config['conf_smtp_username'].">";		
		}else{
			$this->emailFrom = $bsiCore->config['conf_hotel_name']."<".$bsiCore->config['conf_hotel_email'].">";
		}
		
		$this->emailReplyTo 	= $bsiCore->config['conf_hotel_email'];
		$this->smtpHost 		= $bsiCore->config['conf_smtp_host'];
		$this->smtpPort 		= intval($bsiCore->config['conf_smtp_port']);
		$this->smtpUserName 	= $bsiCore->config['conf_smtp_username'];
		$this->smtpPassword 	= $bsiCore->config['conf_smtp_password'];
		$this->loadEmailContent();	
		if(!$this->smtpPort){
			$this->smtpPort = NULL;
		}			
	}
	
	public function sendEMail($emailTo, $emailSubject, $emailBody){
		$this->emailTo = $emailTo;
		$this->emailSubject = $emailSubject;
		$this->emailBody = $emailBody;		
		
		return (($this->isSMTP == 'true')? $this->sendSMTPMail() : $this->sendPHPMail());			
	}
	
	/* Send Email using PHP Mail Function */	
	public function sendPHPMail(){
		// To send HTML mail, the Content-type header must be set
		$emailHeaders  = 'MIME-Version: 1.0' . "\r\n";
		$emailHeaders .= 'Content-type: text/html; charset=ISO-8859-1' . "\r\n";
				
		// Additional headers
		$emailHeaders .= 'reply-to: '.$this->emailReplyTo.'' . "\r\n";
		$emailHeaders .= 'From: '.$this->emailFrom.'' . "\r\n";	
		
		$retmsg = mail($this->emailTo, $this->emailSubject, $this->emailBody, $emailHeaders);		
		// Mail it
		if ($retmsg) {
			return "Message successfully sent!";
		}else {
			return "Failed to sent Message!";
		}
	}
			
	/* Send Email using SMTP authentication */	
	public function sendSMTPMail(){
		require_once "smtp-library.php";	
		
		$emailHeaders = array (
			'From' => $this->emailFrom, 
			'To' => $this->emailTo, 			
			'reply-to' => $this->emailReplyTo, 
			'Subject' => $this->emailSubject,
			'Mime-Version' => "1.0",
			'Content-Type' => "text/html",
			'charset' => "ISO-8859-1",
			'Content-Transfer-Encoding' => "7bit");
		$smtpAuthData = array (
			'host' => $this->smtpHost, 
			'port' => $this->smtpPort,
			'auth' => true, 
			'username' => $this->smtpUserName, 
			'password' => $this->smtpPassword);
			
		$smtpMail = Mail::factory('smtp', $smtpAuthData);			
		$smtpMsg = $smtpMail->send($this->emailTo, $emailHeaders, $this->emailBody);
		
		if (PEAR::isError($smtpMail)) {
			return $smtpMail->getMessage();
		}else {
			return "Message successfully sent!";
		}	
	} 
	
	private function loadEmailContent() {		
		$sql = mysql_query("SELECT * FROM bsi_email_contents WHERE email_name = 'Confirmation Email'");
		$currentrow = mysql_fetch_assoc($sql);	
		$this->emailContent =  array('subject'=> $currentrow["email_subject"], 'body'=> $currentrow["email_text"]);			
		mysql_free_result($sql);		
	}	
}
?>