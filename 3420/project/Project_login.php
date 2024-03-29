<?php  

if (isset($_COOKIE['username']) && $_COOKIE['username'] != "") //if the cookie is set and actually contains something
{
    $user = $_COOKIE['username']; //set the user value to the value of the cookie
}
else
{
    $user = $_POST['username'] ?? null; //otherwise set the user value to whatever was submitted or null
}
$password = $_POST['password'] ?? null;

$errors = array();
if(isset($_GET['logout']))
{
    session_start();
    session_destroy();
    header("Location:Project_login.php");
    exit();
}

if (isset($_POST['submit'])) 
{
    if (isset($_POST['remember'])) //if remember me was selected on login
    {
        setcookie("username", $user, time() + (86400*30), "/"); //save username in a cookie for 30 days
    }
    else //if remember me was not selected on login
    {
        setcookie("username", "", time() - 3600, "/"); //delete the username cookie
    }
    include 'includes/library.php';
    $pdo = connectDB(); //connect to database
        
    $query = "SELECT * FROM projectlogin WHERE username =?"; //select the row of the table with the given username
    $checkCredentials = $pdo->prepare($query); //prepare that query
    $checkCredentials->execute([$user]); //execute
    $row = $checkCredentials->fetch(); //fetch the next row, there should only be one as usernames are unique
        
    if(!$row) //if row is false then no row with that username exists and user is invalid
    { 
        $errors['login'] = true;
    }
    else //if the row was valid
    {
        if(password_verify($password, $row['password'])) //verify that the password is correct
        {
            session_start(); //start the session
            $_SESSION['user'] = $row['username']; //load session credentials
            $_SESSION['id'] = $row['userId'];
            header("Location: index.php?remember=$remember"); //redirect to the homepage
            exit();
        }
        else
        {
            {$errors['login'] = true;}
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login&colon; Buck-et Registry &dash; Project COIS 3420H </title>
        <link rel="stylesheet" href="styles/project_master.css" />
        <script defer src="scripts/login.js"></script>
    </head>
    <body>
       <?php include "includes/header.php";?>
       <?php include "includes/nav.php";?>
        <main>
            <h2>LOGIN</h2>
             <form id="login" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
                <div>
                     <label for="username">Username</label> 
                     <input type="text" id="username" name="username" size="40" placeholder="Enter your username here"  value="<?php echo $user?>" />
                </div>
                <div>
                    <label for="password">Password</label> 
                    <input type="password" id="password" name="password" placeholder="Enter your password here" size="40" />
                </div>
                <div>
                    <a href="Project_forgot_password.php">Forgot Password?</a>
                </div>
                <div>
                    <span id="error" class="error <?=!isset($errors['login']) ? 'hidden' : "";?>">Your username or password was invalid</span>
                </div>
                <div>
                    <label for="remember">Remember me</label>
                    <input type="checkbox" name="remember" id="remember" value="remember" />
                </div>

                <div id="buttons">    
                    <button type="submit" name="submit">Login</button>
                </div>
             </form>
        </main>
        <?php include "includes/footer.php";?>
    </body>
</html>