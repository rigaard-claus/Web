<?php 

function SendMail($messageType, $arrayTo, $message){
    include 'PHPtools/Config.php';
    
    //phpmailer
    require 'phpmailer/PHPMailer.php';
    require 'phpmailer/SMTP.php';
    require 'phpmailer/Exception.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    //gmail settings
    $mail->isSMTP();   
    $mail->Mailer = "smtp";
    $mail->SMTPAuth   = true;
    //$mail->SMTPDebug = 2;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
            
    $mail->Host       = 'smtp.gmail.com'; 
    $mail->Username   = $mailsender["mail"]; 
    $mail->Password   = $mailsender["pass"];
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
//    $mail->SMTPSecure = 'ssl';
//    $mail->Port       = 465;
    $mail->setFrom($mailsender["mail"]); 

    foreach ($arrayTo as $currentEmail) {
        $mail->addAddress($currentEmail);  
    }

    $mail->isHTML(true);
    $mail->Subject = $messageType;
    $mail->Body = $message;    

    if ($mail->send()) {echo "success";} 
    else { echo "error"; }  
}
?>