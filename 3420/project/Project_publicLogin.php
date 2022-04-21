<?php  
include 'includes/library.php';

$password = $_POST['password'] ?? null;

$errors = array();
if(!isset($_GET['listID']) && !isset($_POST['submit']))
{
    die("Error: No listID set");
    exit();
}

$pdo = connectDB(); //connect to the database

if (isset($_POST['submit'])) 
{
    //get the wishlist details
    $wishlist = $pdo->prepare('SELECT * FROM wishlistTable WHERE listID = ?;');
    $wishlist->execute([$_POST['submit']]);

    $wishlistInfo = $wishlist->fetch();

    //die($wishlistInfo['publicPass']);
    if(password_verify($password, $wishlistInfo['publicPass'])) //verify that the password is correct
    {
        session_start(); //start the session
        $_SESSION['publicListID'] = $wishlistInfo['listID'];
        header("Location: Project_publicViewList.php?listID=".strval($wishlistInfo['listID'])); //redirect to the list
        exit();
    }
    else
    {
        {$errors['login'] = true;}
        die("your password is incorrect.");
    }
    
}
else{
    //get listID from url paramters
    $listID = $_GET['listID'];


    //get the wishlist details
    $wishlist = $pdo->prepare('SELECT * FROM wishlistTable WHERE listID = ?;');
    $wishlist->execute([$listID]);

    $wishlistInfo = $wishlist->fetch();

    //get creation and expiry dates, replace the / with a - and then converts it into a PHP datetime obj
    $createDate = date_create(str_replace("/","-",$wishlistInfo['createDate']));
    $expiryDate = date_create(str_replace("/","-",$wishlistInfo['expiryDate']));

    //Access control if the list has expired
    if($expiryDate<=$createDate){
        die("Error: This wishlist has expired.");
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
    </head>
    <body>
       <?php include "includes/header.php";?>
       <?php include "includes/nav.php";?>
        <main>
            <h3>Enter Password to view list : <?=$wishlistInfo['title']?></h3>
             <form id="login" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
                <div>
                    <label for="password">Password</label> 
                    <input type="password" id="password" name="password" placeholder="Enter your password here" size="40" />
                </div>
                <div>
                    <span id="error" class="error <?=!isset($errors['login']) ? 'hidden' : "";?>">Your password was invalid.</span>
                </div>
                <div id="buttons">    
                    <button type="submit" name="submit" value="<?=$listID?>">Login</button>
                </div>
             </form>
        </main>
        <?php include "includes/footer.php";?>
    </body>
</html>