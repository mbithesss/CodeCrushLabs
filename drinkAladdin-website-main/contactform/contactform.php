<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer();

if (!isset($_POST['email'])) {
    echo "Enter your email";
    exit();
}
if (!isset($_POST['name'])) {
    echo "Enter your name";
    exit();
}
if (!isset($_POST['subject'])) {
    echo "Enter a subject for your message";
    exit();
}
if (!isset($_POST['message'])) {
    echo "Please type a message for us.";
    exit();
}

// Get data from form
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

$message = wordwrap($message, 70, "\r\n");

$txt = <<<EOD
<html lang="en">
<head>
  <title>‼️New email from the website.‼️</title>
</head>
<body>
  <h2>New email from the website.</h2>
  <p>From: $name, Email: $email</p>
  <hr />
  <h3>Message Details</h3>
  <table>
    <tr>
      <td><strong>Name</strong></td><td>$name</td>
    </tr>
    <tr>
      <td><strong>Email</strong></td><td>$email</td>
    </tr>
    <tr>
      <td colspan="2"><strong>Subject</strong></td>
    </tr>
    <tr>
      <td colspan="2">$subject</td>
    </tr>
    <tr>
      <td colspan="2"><strong>Message</strong></td>
    </tr>
    <tr>
      <td colspan="2">$message</td>
    </tr>
  </table>
</body>
</html>
EOD;


try {
    //Server settings
//    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                         //Enable verbose debug output
    $mail->isSMTP();                                                //Send using SMTP
    $mail->Host = getenv('MAIL_HOST');                       //Set the SMTP server to send through
    $mail->SMTPAuth = true;                                       //Enable SMTP authentication
    $mail->Username = getenv('MAIL_USERNAME');             //SMTP username
    $mail->Password = getenv('MAIL_PASSWORD');            //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           //Enable implicit TLS encryption
    $mail->Port = 465;                                        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    // 3 Recipients
    $mail->setFrom($email, $name);

    $dEmail = getenv('EMAIL_1_ADDRESS');
    $dName = getenv('EMAIL_1_NAME');
    if ($dEmail) {
        if ($dName) {
            $mail->addAddress($dEmail, $dName);
        } else {
            $mail->addAddress($dEmail);
        }
    }
    $dEmail = getenv('EMAIL_2_ADDRESS');
    $dName = getenv('EMAIL_2_NAME');
    if ($dEmail) {
        if ($dName) {
            $mail->addAddress($dEmail, $dName);
        } else {
            $mail->addAddress($dEmail);
        }
    }
    $email = getenv('EMAIL_3_ADDRESS');
    $dName = getenv('EMAIL_3_NAME');
    if ($email) {
        if ($dName) {
            $mail->addAddress($email, $dName);
        } else {
            $mail->addAddress($email);
        }
    }

    $mail->addReplyTo($email, $name);
    // $mail->addCC(''); // CC
    // $mail->addBCC(''); // BCC

    // Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body = $txt;
    $mail->AltBody = 'Your mail client does not support HTML emails. Please open this email in a browser.';

    $mail->send();
    echo 'OK';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
