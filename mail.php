<?php
require 'PHPMailer-master/PHPMailerAutoload.php';

class mailForm {
	private $SecurityToken = "pass";
	private $Betreff = 0;
	private $Message = 0;
	private $MailData = array(	"host" =>"smtp.host.de", 
					"username" => "USERNAME", 
					"password" => "PASSWORD", 
					"from" => "e@mail.de",
					"fromName" => "NAME",
					"toAdress" => "adress@mail.com");
	#Adding CC Recipient List.
	private $CcRecipients = array("first@email.com", "second@email.com", "third@email.com");

	// Initialize the class
	public function __construct () {
		// Check Security Token. If match proceed.
		$this->checkSecurityToken();

		// Check if the required variables are given in expected format, if yes globalize them
		$this->getBetreff();
		$this->getMessage();

		// Send Email
		$this->sendMail();
	}
	
	private function checkSecurityToken(){
		if( $_GET['Token'] != $this->SecurityToken){
			echo "<p>Security token does not match.</p>";
			exit;
		}
	}
	
	private function getBetreff() {
		if ($_GET['Betreff'] == "") {
			echo "<p>Betreff is empty.</p>";
			exit;
		} else {
			$this->Betreff = $_GET['Betreff'];
		}
	}
	private function getMessage() {
		if ($_GET['Message'] == "") {
			echo "<p>Message is empty.</p>";
			exit;
		} else {
			$this->Message = $_GET['Message'];
		}
	
	}

	private function sendMail() {
		echo " The Message is: " . $this->Message ;
		echo "Sending Mail";

		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->SMTPDebug = 2;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "tls";  
		$mail->Host = $this->MailData['host'];
		$mail->Port = 587;
		$mail->Username = $this->MailData['username'];
		$mail->Password = $this->MailData['password'];
		$mail->From = $this->MailData['from'];
		$mail->FromName = $this->MailData['fromName'];
		$mail->addAddress($this->MailData['toAdress']);
		
		#Add CC Recipients
		foreach ($this->CcRecipients as $value) {
		  $mail->addCC($value);
		}
		$mail->Subject = $this->Betreff;
		$mail->Body    = $this->Message;
		
		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent';
		}
	}

}
	$email = new mailForm();
?>
