<?php
/* Email input from a live contact form to your desired email address through a SMTP and access to your Gmail account of choice. */

$msg = '';
if (array_key_exists('email', $_POST)) {
    date_default_timezone_set('Etc/UTC');

    require 'phpmailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;
    //Tell PHPMailer to use SMTP - requires a local mail server so don't use
    //$mail->isSMTP();

    // 0 = off // 1 = client messages // 2 = client and server messages
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'html';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    // This is the Gmail account that will be used for the tranfer (middle-man)
    $mail->Username = "YourEmailAddress@gmail.com";
    // Password to the Gmail account above. Can use a pass generated for app instead.
    $mail->Password = "YOURPASSWORD";

    // Set as the same email address you just gave up the password to up above.
    $mail->setFrom('YourEmailAddress@gmail.com', 'First Last');
    // Where do you want the message to be sent?
    $mail->addAddress('YourEmail@example.org', 'Name Here');

    if ($mail->addReplyTo($_POST['email'], $_POST['name'])) {
        // Edit the Subject line below, as desired
        $mail->Subject = 'Your Website - contact form submission';
        $mail->isHTML(false);
        // If your form has other fields, add them below. ie: Phone: {$_POST['phone']}
        $mail->Body = <<<EOT
Email: {$_POST['email']}
Name: {$_POST['name']}
Message: {$_POST['message']}
EOT;
        //Send the message, check for errors
        if (!$mail->send()) {
            $msg = 'Sorry, something went wrong. Please try again later.';
        } else {
          // JS popup alert to let user know the message was sent.
          $message = 'Message sent! Thanks for contacting us.';
          echo "<script type='text/javascript'>alert('$message');</script>";
        }
    } else {
        $msg = 'Invalid email address, message ignored.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact form</title>
</head>
<body>
<h1>Contact us</h1>
<!-- Add this bit of PHP, just before your '<form' line -->
<?php if (!empty($msg)) {
    echo "<h2>$msg</h2>";
} ?>
<!-- PHP End -->
<form method="POST">
    <!-- Be sure you correctly identify each input, ie: id="phone" ... etc -->
    <label for="name">Name: <input type="text" name="name" id="name"></label><br>
    <label for="email">Email address: <input type="email" name="email" id="email"></label><br>
    <label for="message">Message: <textarea name="message" id="message" rows="8" cols="20"></textarea></label><br>
    <input type="submit" value="Send">
</form>
</body>
</html>
