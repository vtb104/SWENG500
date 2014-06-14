<?php

//This Mailer object sets up and sends e-mail



//Send mail
//
//@params-> $to array of e-mail addresses
//@params-> $from: two element array of e-mail address the message is from and the name
//@params-> $subject: Obvious
//@params-> $message: HTML message
//@return-> True or false if sent

include_once("class.phpmailer.php");

class Mailer
{
	private $to;
	private $fromaddress;
	private $fromname;
	private $subject;
	private $message;
	
	function __construct($to, $fromaddress, $fromname, $subject, $message){
		$this->$mail = new PHPMailer();	
		$this->$to = $to;
		$this->$fromaddress = $fromaddress;
		$this->$fromname = $fromname;
		$this->$subject = $subject;
		$this->$message = $message;
	}
	
	public function send_mail(){
		$this->$mail->IsSMTP();
		$this->$mail->SMTPDebug = 1;
		$this->$mail->SMTPAuth = true;
		$this->$mail->SMTPSecure = "ssl";
		$this->$mail->Host = "smtp.gmail.com";
		$this->$mail->Port = 465;
		$this->$mail->Username = _EMAIL_USERNAME;
		$this->$mail->Password = _EMAIL_PASSWORD;
		$this->$mail->SetFrom($this->$fromaddress, $this->$fromname);
		$this->$mail->AddAddress($this->$to);
		$this->$mail->Subject = $this->$subject;
		$this->$mail->IsHTML(true);
		$this->$mail->Body = $this->$message; 
		return $this->$mail->Send();
	}
};
