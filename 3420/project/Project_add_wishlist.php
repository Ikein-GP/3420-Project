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
    $expiry = $_POST['expiry'] ?? null;
    $theme = $_POST['theme'] ?? null;

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
            $createListEntry = $pdo->prepare('INSERT INTO wishlisttable VALUES (NULL, ?, ?, ?, ?, NOW(), ?, ?);'); //prepare the query to add the wishlist to the table of all wishlists
            $createListEntry->execute([$title, $description, $_SESSION['id'], password_hash($password, PASSWORD_BCRYPT), $expiry, $theme]); //add the table
            $listId = $pdo->lastInsertId(); //keep track of the id number for the fresh wishlist
            header("Location:index.php");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="styles/project_master.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script defer src="scripts/addWishlist.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <title>Add Wishlist&colon; Buck-et Registry &dash; Project COIS 3420H</title>
    </head>
    <body>
    <?php include "includes/header.php";?>
    <?php include "includes/nav.php";?>
        <main>
            <h2>Create Wishlist</h2>
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
                    <select name="theme" id="theme">
                        <option value="1">Default</option>
                        <option value="2">Christmas</option>
                        <option value="3">Halloween</option>
                        <option value="4">Valentines</option>
                        <option value="5">Wedding</option>
                    </select>
                <div>
                    <button type="submit" name="submit">Create</button>
                </div>
            </form>
        </main>
    <?php include "includes/footer.php";?>
        <script>
            flatpickr("#expiry", {});
        </script>
    </body>
</html>