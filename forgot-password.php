<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'pnncathr@gmail.com'; //เปลี่ยนของใครของมัน
    $mail->Password = "jgox kucn ldlw urfy";
    //gmail password :: 1QazxsW2
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';

    //recipients
    $mail->setFrom('pnncathr@gmail.com','Admin The Movies Spoiler');
    $mail->addAddress('patthama.no@ku.th','Test Send Email');

    //content
    $mail->isHTML(true);
    $mail->Subject = 'รหัสผ่านยืนยัน';
    $mail->Body = 'นี่คือรหัสผ่านยืนยันของคุณ!<b> in bold!</b>';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e){
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>