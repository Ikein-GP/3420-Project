<?php
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