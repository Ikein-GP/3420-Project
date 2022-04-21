<?php
include 'includes/library.php';
    session_start();
    if(!isset($_SESSION['user']))
    {
        header("Location:Project_login.php");
        exit();
    }

    //get listID from url paramters
    $listID = $_GET['listID'];

    $pdo = connectDB(); //connect to the database

    $wishlist = $pdo->prepare('SELECT * FROM wishlisttable WHERE listID = ?;');
    $wishlist->execute([$listID]);
    $wishlistData = $wishlist->fetch();

    $errors = array();
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $password = $_POST['password'] ?? null;
    $expiry = $_POST['expiry'] ?? null; //add a widget for this later

    if (isset($_POST['submit']))
    {   
        if(!isset($title) || strlen($title) === 0)
        {
            $errors['title'] = true;
        }
        if(!isset($description) || strlen($description) === 0)
        {
            $errors['description'] = true;
        }
        if(!isset($expiry) || strlen($expiry) === 0)
        {
            $errors['expiry'] = true;
        }

        if(count($errors)===0)
        {
            if(!isset($password) || strlen($password) === 0)
            {
                $pdo = connectDB(); //connect to database
                $updateListQuery = $pdo->prepare('UPDATE wishlistTable SET title = ?, description = ?, expiryDate = ? WHERE listID = ?;'); //prepare the query to add the wishlist to the table of all wishlists
                $updateListQuery->execute([$title, $description, $expiry, $listID]); //add the table
                $listId = $pdo->lastInsertId(); //keep track of the id number for the fresh wishlist
                header("Location:index.php");
            }
            else{
                $pdo = connectDB(); //connect to database
                $updateListQuery = $pdo->prepare('UPDATE wishlistTable SET title = ?, description = ?, publicPass = ?, expiryDate = ? WHERE listID = ?;'); //prepare the query to add the wishlist to the table of all wishlists
                $updateListQuery->execute([$title, $description,password_hash($password, PASSWORD_BCRYPT), $expiry, $listID]); //add the table
                $listId = $pdo->lastInsertId(); //keep track of the id number for the fresh wishlist
                header("Location:index.php");
            }
            
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="styles/project_master.css">
        <script defer src="scripts/editWishlist.js"></script>
        <title>Edit Wishlist&colon; Buck-et Registry &dash; Project COIS 3420H</title>
    </head>
    <body>
    <?php include "includes/header.php";?>
    <?php include "includes/nav.php";?>
        <main>
            <h2>Edit Wishlist</h2>
            <form id="addwishlist" name="addwishlist" method="post" enctype="multipart/form-data">
                <div>
                    <label for="title">List Title:</label>
                    <input type="text" id="title" name="title" value = "<?=$wishlistData['title']?>" required>
                    <span class="error <?=!isset($errors['title']) ? 'hidden' : "";?>">Please enter a title</span>
                </div>
                <div>
                    <label for="description">List Description:</label>
                    <input type="text" id="description" name="description" value = "<?=$wishlistData['description']?>" required>
                    <span class="error <?=!isset($errors['description']) ? 'hidden' : "";?>">Please enter a description</span>
                </div>
                <div>
                    <label for="password">Public View Password:</label>
                    <input type="password" id="password" name="password">
                </div>
                <div>
                    <label for="expiry">Expiry Date:</label>
                    <input type="date" id="expiry" name="expiry" value = "<?=$wishlistData['expiryDate']?>" required>
                    <span class="error <?=!isset($errors['expiry']) ? 'hidden' : "";?>">Please enter an expiry date</span>
                </div>
                <div>
                    <button type="submit" name="submit">Create</button>
                </div>
            </form>
        </main>
    <?php include "includes/footer.php";?>
    </body>
</html>