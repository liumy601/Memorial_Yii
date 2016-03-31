<?php

class EmailConfigMailer{
  public $toName;
  public $toEmail;
  public $subject;
  public $body;
  
  /**
   * 
   * @param type $toName
   * @param type $toEmail
   * @param type $subject
   * @param type $body
   * @param type $attachments   array('/aaaa/bbb/lksjdf.gif', '/aaaa/bbb/sfhwre.gif')
   */
  public function __construct($companyID, $toName, $toEmail, $subject, $body='', $attachments=array()){
    $this->companyID = $companyID;
    $this->toName = $toName;
    $this->toName = $toName;
    $this->toEmail = $toEmail;
    $this->subject = $subject;
    $this->body = $body;
    $this->attachments = $attachments;
  }
  
  public function send()
  {
    if (!$this->companyID) {
      return false;
    }
    
    $emailConfig = EmailConfig::load($this->companyID);
    
    if (!$emailConfig->id) {
      return false;
    }

    if ($emailConfig->send_type == 'postmark') {
      return $this->sendByPostmark($emailConfig);
    } else {//Sendmail, SMTP
      return $this->sendByPHPMailer($emailConfig);
    }
  }
  
  private function sendByPostmark($emailConfig){
    $postmark = new Postmark($emailConfig->postmark_api_key, $emailConfig->from_name . " <". $emailConfig->from_address .">");
    $result = $postmark->to($this->toName . " <". $this->toEmail .">")
      ->subject($this->subject)
      ->html_message($this->body)
      ->send();
    
    return $result;
  }
  
  private function sendByPHPMailer($emailConfig){
    $mail = new PHPMailer();
    if ($emailConfig->send_type == 'SMTP') {//SMTP
      $mail->IsSMTP();
      $mail->Port = $emailConfig->smtp_port ? $emailConfig->smtp_port : 25;

      if ($emailConfig->smtp_ssl) {
        $mail->SMTPSecure = 'ssl';
      }

      $mail->Host = $emailConfig->smtp_server;
      if ($emailConfig->smtp_auth) {
        $mail->SMTPAuth = true;
        $mail->Username = $emailConfig->smtp_user; 
        $mail->Password = $emailConfig->smtp_pass;
      }
    } else {//sendmail
      $mail->IsMail(); 
    }
    
    $mail->CharSet = "utf-8";
    $mail->Encoding = "base64"; 
    $mail->SetFrom($emailConfig->from_address, $emailConfig->from_name);
    $mail->AddAddress($this->toEmail, $this->toName);
    $mail->Subject = $this->subject;
    $mail->Body = $this->body;
    $mail->IsHTML(true);

    if ($this->attachments) {
      foreach ($this->attachments as $attach) {
        $mail->AddAttachment($attach, basename($attach));
      }
    }

    $result = $mail->Send();
    
    return $result;
  }
}