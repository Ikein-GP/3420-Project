<?php 
include 'includes/library.php';
session_start(); //start the session
$errors = array(); //declare empty array to add errors too

//get name from post or set to NULL if doesn't exist
$fname = $_POST['fname'] ?? null;
$lname = $_POST['lname'] ?? null;
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$passwordNew = $_POST['passwordNew'] ?? null;
$passwordNewRe = $_POST['passwordNewRe'] ?? null;

if (isset($_POST['submit']))
{
    if(isset($passwordNew) && strlen($passwordNew) != 0) //only check this if a password change was requested
    {
        if($passwordNew != $passwordNewRe) //if the new password doesn't match its re-entry
        {
            $errors['passwordre'] = true;
        }
        if(!isset($password) || strlen($password) === 0) //if the current password wasn't provided at all throw an error
        {
            $errors['password'] = true;
        }
    }
    if(count($errors)===0) //if no errors are encountered
    {
        $pdo = connectDB(); //connect to database
        $currentUser = $pdo->prepare("SELECT * FROM projectlogin WHERE userId=?"); //prepare the query to select the current user
        $currentUser->execute([$_SESSION['id']]); //provide the current users id to the query and execute
        //Note: we allow the user to submit the form if they gave their current password but not something to change it to and we simply refrain from changing the password
        //via the validation below
        if(isset($passwordNew) && strlen($passwordNew) != 0) //if a new password was given
        {
            foreach($currentUser as $user) //there should only ever be a single row but a foreach still has to be used to pull it out of the PDO object
            {
                if(!password_verify($password, $user['password'])) //if the given password hash does not match the current user's password hash
                {
                    $errors['passwordIncorrect'] = true; //throw an error denoting the password was incorrect
                }
            }
        }
        $accounts = $pdo->prepare('SELECT * FROM projectlogin;'); //grab all the currently existing usernames
        $accounts->execute();
        foreach($accounts as $row) //check through all existing accounts
        {
            if(isset($username) && strlen($username) != 0) //check if the user gave a username to change
            {
                if ($username == $row['username']) //if the given username is the same as one already in the database
                {
                    $errors['uniqueUser'] = true; //username is already in use
                } 
            }
        }
        
        if(count($errors)===0) //check if there are still no errors
        {
            if(isset($fname) && strlen($fname) != 0) //all individual fields need to be checked for being set because not being set isn't an error
            {
                $updateUser = $pdo->prepare("UPDATE projectlogin SET firstName=? WHERE userId=?");
                $updateUser->execute([$fname, $_SESSION['id']]);
            }
            
            if(isset($lname) && strlen($lname) != 0)
            {
                $updateUser = $pdo->prepare("UPDATE projectlogin SET lastName=? WHERE userId=?");
                $updateUser->execute([$lname, $_SESSION['id']]);
            }
            
            if(isset($username) && strlen($username) != 0)
            {
                $updateUser = $pdo->prepare("UPDATE projectlogin SET username=? WHERE userId=?");
                $updateUser->execute([$username, $_SESSION['id']]);
            }
            
            if(isset($passwordNew) && strlen($passwordNew) != 0)
            {
                $updateUser = $pdo->prepare("UPDATE projectlogin SET password=? WHERE userId=?");
                $updateUser->execute([password_hash($passwordNew, PASSWORD_BCRYPT), $_SESSION['id']]);
            }
            header("Location: Project_account_settings.php"); //redirect to the homepage
            exit();
        }
    }
}
    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Create Account</title>
        <link rel="stylesheet" href="styles/project_master.css" />
    </head>
    <body>
       <?php include "includes/header.php";?>
       <?php include "includes/nav.php";?>
        <main>
            <h2> Account Settings </h2>
                <form id="create" name="create" method="post" novalidate>
                    <!-- First Name Input -->
                    <div>
                        <label for="fname">New First Name</label>
                        <input type="text" name="fname" id="fname" placeholder="Enter your first name here" value="<?=$fname?>"/>
                    </div>
                    <!-- Last Name Input -->
                    <div>
                        <label for="lname">New Last Name</label>
                        <input type="text" name="lname" id="lname" placeholder="Enter your last name here" value="<?=$lname?>"/>
                    </div>
                    <!-- Username Input -->
                    <div>
                        <label for="username">New Username</label>
                        <input type="text" name="username" id="username" placeholder="Enter your username here" value="<?=$username?>"/>
                        <span class="error <?=!isset($errors['uniqueUser']) ? 'hidden' : "";?>">Username already taken</span>
                    </div>
                    <!-- Password Input For Changing Password -->
                    <div>
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password here" value="<?=$password?>"/>
                        <span class="error <?=!isset($errors['password']) ? 'hidden' : "";?>">Please enter your current password to change your password</span>
                        <span class="error <?=!isset($errors['passwordIncorrect']) ? 'hidden' : "";?>">Your password was incorrect</span>
                    </div>
                    <!-- Re-entering Password -->
                    <div>
                        <label for="passwordNew">New Password</label>
                        <input type="password" name="passwordNew" id="passwordNew" placeholder="Enter new password here" value="<?=$passwordNew?>"/>
                    </div>
                    <div>
                        <label for="passwordNewRe">Re-Enter New Password</label>
                        <input type="password" name="passwordNewRe" id="passwordNewRe" placeholder="Re-enter new password here" value="<?=$passwordNewRe?>"/>
                        <span class="error <?=!isset($errors['passwordre']) ? 'hidden' : "";?>">Passwords do not match!</span>
                    </div>

                    <div id="buttons">    
                    <button type="submit" name="submit">Save Settings</button>
                    </div>
                </form>
        </main>
    </body>
</html>