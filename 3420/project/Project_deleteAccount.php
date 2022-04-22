<?php  
include 'includes/library.php';
session_start(); //start the session
$password = $_POST['password'] ?? null;

$errors = array();


$pdo = connectDB(); //connect to the database

if(!isset($_SESSION['id']))
{
    header("Location:Project_login.php");
    exit();
}

$userID = $_SESSION['id'];

$accountQuery = $pdo->prepare('SELECT * FROM projectLogin WHERE userId = ?');
$accountQuery->execute([$userID]);
$accountInfo = $accountQuery->fetch();

$username = $accountInfo['username'];

if (isset($_POST['submit'])) 
{
    
    if(password_verify($password, $accountInfo['password'])) //verify that the password is correct
    {
        $getListsQuery = $pdo->prepare('SELECT listID FROM wishlisttable WHERE ownerID = ?');
        $getListsQuery->execute([$userID]);

        foreach($getListsQuery as $listRow){
            $listID = implode($listRow);
            
            //delete items
            $deleteItemsQuery = $pdo->prepare('DELETE FROM wishlistitems WHERE wishListID = ?');
            $deleteItemsQuery->execute([$listID]);

            //delete list

            $deleteListQuery = $pdo->prepare('DELETE FROM wishlisttable WHERE listID = ?');
            $deleteListQuery->execute([$listID]);

        }

        //delete account, committ seppuku

        $deleteAccountQuery = $pdo->prepare('DELETE FROM projectlogin WHERE userId = ?');
        $deleteAccountQuery->execute([$userID]);

        header("Location:Project_login.php");
    }
    else
    {
        {$errors['login'] = true;}
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Delete Account - Project COIS 3420H </title>
        <link rel="stylesheet" href="styles/project_master.css" />
    </head>
    <body>
       <?php include "includes/header.php";?>
       <?php include "includes/nav.php";?>
        <main>
            <h3>Are you sure you want to delete your Account, <?=$username?>?</h3>
            <h4>Enter your password and click confirm to proceed. All associated lists & items will be deleted as well.</h4>
             <form id="login" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
                <div>
                    <label for="password">Password</label> 
                    <input type="password" id="password" name="password" placeholder="Enter your password here" size="40" />
                </div>
                <div>
                    <span id="error" class="error <?=!isset($errors['login']) ? 'hidden' : "";?>">Your password was invalid.</span>
                </div>
                <div id="buttons">    
                    <button type="submit" name="submit" value="<?=$listID?>">Confirm</button>
                </div>
             </form>
        </main>
        <?php include "includes/footer.php";?>
    </body>
</html>