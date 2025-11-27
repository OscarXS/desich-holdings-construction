<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mail = new PHPMailer();

//$mail->SMTPDebug = 3; // Enable verbose debug output
$mail->isSMTP(); // Set mailer to use SMTP
$mail->Host = 'desicholdings.co.za'; // Specify main and backup SMTP servers
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->Username = 'info@desicholdings.co.za'; // SMTP username
// $mail->Password = '@Desich123'; // SMTP password
$mail->Password = '*]N8NO&rIMNx'; // SMTP password
$mail->SMTPSecure = true; // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465; // TCP port to connect to

$message = "";
$status = "false";

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
 if( $_POST['form_name'] != '' AND $_POST['form_email'] != '' AND $_POST['form_service'] != '' ) {

 $name = $_POST['form_name'];
 $email = $_POST['form_email'];
 $service = $_POST['form_service'];
 $phone = $_POST['form_phone'];
 $message = $_POST['form_message'];

 // Subject line
 $subject = "New Message | Contact Form - $service";

 // Honeypot bot check
 $botcheck = isset($_POST['form_botcheck']) ? $_POST['form_botcheck'] : '';

 $service = isset($service) ? $service : 'New Message | Contact Form';

 $toemail = 'info@desicholdings.co.za'; // Your Email Address
 $toname = 'Desich_Gardens'; // Your Name
 $header .= "Cc:chimbekeyad@gmail.com \r\n";

 if( $botcheck == '' ) {

 $mail->SetFrom( $email , $name );
 $mail->AddReplyTo( $email , $name );
 $mail->AddAddress( $toemail , $toname );
 $mail->Service = $service;

 // Set subject properly
 $mail->Subject = $subject;

 $name = isset($name) ? "Name: $name<br><br>" : '';
 $email = isset($email) ? "Email: $email<br><br>" : '';
 $phone = isset($phone) ? "Phone: $phone<br><br>" : '';
 $service = isset($service) ? "Service: $service<br><br>" : '';
 $message = isset($message) ? "Message: $message<br><br>" : '';

 $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>This Form was submitted from: ' . $_SERVER['HTTP_REFERER'] : '';

 $body = "$name $email $service $phone $message $referrer";

 $mail->MsgHTML( $body );
 $sendEmail = $mail->Send();

 if( $sendEmail == true ):
 $message = 'We have <strong>successfully</strong> received your Message and will get Back to you as soon as possible.';
 $status = "true";
 else:
 $message = 'Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.<br /><br /><strong>Reason:</strong><br />' . $mail->ErrorInfo . '';
 $status = "false";
 endif;
 } else {
 $message = 'Bot <strong>Detected</strong>.! Clean yourself Botster.!';
 $status = "false";
 }
 } else {
 $message = 'Please <strong>Fill up</strong> all the Fields and Try Again.';
 $status = "false";
 }
} else {
 $message = 'An <strong>unexpected error</strong> occured. Please Try Again later.';
 $status = "false";
}

$status_array = array( 'message' => $message, 'status' => $status);
echo json_encode($status_array);
?>