<?php
include 'includes/library.php';

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$errors = array(); //declare empty array to add errors too
$email = $_POST['email'] ?? null;

if (isset($_POST['submit']))
{
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $errors['email'] = true;
    }
    if(count($errors)===0)
    {
        $pdo = connectDB(); //connect to the database
        $result = $pdo->prepare('SELECT * FROM projectlogin WHERE email=?');
        $result->execute([$email]); //execute the prepared query
        $row = $result->fetch(); //fetch the next row,

        if($row)
        {

            $code = uniqid(true);

            $Insertcode = $pdo->prepare('INSERT INTO resetPassword values (NULL, ?, ?);'); //prepare to create the account
            $Insertcode->execute([$code, $email]); //execute the account creation query

            require 'vendor/phpmailer/phpmailer/src/Exception.php';
            require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
            require 'vendor/phpmailer/phpmailer/src/SMTP.php';

            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer();

            try {
                //Server settings
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = '';                     //Set the SMTP server to send through USED TO CONTAIN DUMMY VALUES, NOW REMOVED
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = '';                     //SMTP username USED TO CONTAIN DUMMY VALUES, NOW REMOVED
                $mail->Password   = '';                               //SMTP password USED TO CONTAIN DUMMY VALUES, NOW REMOVED
                $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                $mail->Port       = 0;                                    //TCP port to connect to USED TO CONTAIN DUMMY VALUES, NOW REMOVED

                //Recipients
                $mail->setFrom('project3420h@gmail.com', 'Bucket-et Registry');
                $mail->addAddress($email);     //Add a recipient
                $mail->addReplyTo('no-reply@gmail.com', 'No reply');

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Password Reset Link - Buck-et Registry';
                $mail->Body    = "Click <a href='https://loki.trentu.ca/~gregoryprouty/3420/project/Project_forgotpassword_reset.php?code=$code'>here</a> to reset your password";
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();


                header("Location: Project_forgot_redirect"); //redirect to the homepage
                } 
                catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                die();
                }
        }
        else{
             $errors['email'] = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Forgot Password&colon; Buck-et Registry &dash; Project COIS 3420H</title>
        <link rel="stylesheet" href="styles/project_master.css">
    </head>
    <body>
       <?php include "includes/header.php";?>
       <?php include "includes/nav.php";?>

        <main>
            <h2>Forgot Password</h2>
            <p>Please enter your email to reset your password.</p>
            <form id="forgot" name="forgot" method="post" enctype="multipart/form-data">
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error <?=!isset($errors['email']) ? 'hidden' : "";?>">Please enter a correct email</span>
                </div>
                <div>
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>
        </main>

        <?php include "includes/footer.php";?>

    </body>
</html>
