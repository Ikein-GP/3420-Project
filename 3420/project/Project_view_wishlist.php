<?php
    include 'includes/library.php';
    session_start();

    if(!isset($_SESSION['user']))
    {
        header("Location:Project_login.php");
        exit();
    }
    $errors = array();

    $pdo = connectDB(); //connect to the database
    $printLists = $pdo->prepare('SELECT * FROM wishlistTable ORDER BY userId;'); //prepare the query to add the name and score to the database
    $printLists->execute(); //execute the prepared query
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>view Wishlists&colon; Buck-et Registry &dash; Project COIS 3420H</title>
        <link rel="stylesheet" href="styles/project_master.css" />
    </head>
    <body>
       <?php include "includes/header.php";?>
       <?php include "includes/nav.php";?>
        <main id="loginpage">
            <h2>wishlists</h2>
            <ul>
            <?php foreach($printLists as $row): ?>
                    <li>Title: <?php echo($row['title']);?> Description: <?php echo($row['description']);?> Creation Date: <?php echo($row['createDate']);?> <?php echo($row['expiryDate']);?> </li>
            <?php endforeach ?>
            </ul>
        </main>
        <?php include "includes/footer.php";?>
  </body>
</html>