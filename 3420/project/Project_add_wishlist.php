<?php
include 'includes/library.php';
    session_start();
    if(!isset($_SESSION['user']))
    {
        header("Location:Project_login.php");
        exit();
    }
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
        if(!isset($password) || strlen($password) === 0)
        {
            $errors['password'] = true;
        }
        if(!isset($expiry) || strlen($expiry) === 0)
        {
            $errors['expiry'] = true;
        }

        if(count($errors)===0)
        {
            $pdo = connectDB(); //connect to database
            $createListEntry = $pdo->prepare('INSERT INTO wishlistTable VALUES (NULL, ?, ?, ?, ?, NOW(), ?);'); //prepare the query to add the wishlist to the table of all wishlists
            $createListEntry->execute([$title, $description, $_SESSION['id'], password_hash($password, PASSWORD_BCRYPT), $expiry]); //add the table
            $listId = $pdo->lastInsertId(); //keep track of the id number for the fresh wishlist
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="styles/project_master.css">
        <title>Add Wishlist&colon; Buck-et Registry &dash; Project COIS 3420H</title>
    </head>
    <body>
    <?php include "includes/header.php";?>
    <?php include "includes/nav.php";?>
        <main>
            <h2>Add to Wishlist</h2>
            <form id="addwishlist" name="addwishlist" method="post" enctype="multipart/form-data">
                <div>
                    <label for="title">List Title:</label>
                    <input type="text" id="title" name="title" required>
                    <span class="error <?=!isset($errors['title']) ? 'hidden' : "";?>">Please enter a title</span>
                </div>
                <div>
                    <label for="description">List Description:</label>
                    <input type="text" id="description" name="description" required>
                    <span class="error <?=!isset($errors['description']) ? 'hidden' : "";?>">Please enter a description</span>
                </div>
                <div>
                    <label for="password">Public View Password:</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error <?=!isset($errors['password']) ? 'hidden' : "";?>">Please enter a public view password</span>
                </div>
                <div>
                    <label for="expiry">Expiry Date:</label>
                    <input type="text" id="expiry" name="expiry" required>
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