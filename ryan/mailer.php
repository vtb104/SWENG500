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
	public $mail_obj;
	private $to;
	private $fromaddress;
	private $fromname;
	private $subject;
	private $message;
	
	function __construct($to, $fromaddress, $fromname, $subject, $message){
		$this->mail_obj = new PHPMailer();	
		$this->to = $to;
		$this->fromaddress = $fromaddress;
		$this->fromname = $fromname;
		$this->subject = $subject;
		$this->message = $message;
	}
	
	public function send_mail(){
		$this->mail_obj->IsSMTP();
		$this->mail_obj->SMTPDebug = 1;
		$this->mail_obj->SMTPAuth = true;
		$this->mail_obj->SMTPSecure = "ssl";
		$this->mail_obj->Host = "smtp.gmail.com";
		$this->mail_obj->Port = 465;
		$this->mail_obj->Username = _EMAIL_USERNAME;
		$this->mail_obj->Password = _EMAIL_PASSWORD;
		$this->mail_obj->SetFrom($this->fromaddress, $this->fromname);
		$this->mail_obj->AddAddress($this->to);
		$this->mail_obj->Subject = $this->subject;
		$this->mail_obj->IsHTML(true);
		$this->mail_obj->Body = $this->message; 
		return $this->mail_obj->Send();
	}
};
