<?php
    include 'includes/library.php';
    session_start();

    if(!isset($_SESSION['user']))
    {
        header("Location:Project_login.php");
        exit();
    }

    if(!isset($_GET["itemID"])){
        die("Error: item ID is not set.");
    }

    $itemID = $_GET['itemID'];

    $pdo = connectDB();
    $listItem=$pdo->prepare('SELECT * FROM wishlistitems WHERE itemID = ?');
    $listItem->execute([$itemID]);

    $itemInfo = $listItem->fetch();

    $errors = array();
    $title = $_POST['title'] ?? null;
    $description = $_POST['descrip'] ?? null;
    $link = $_POST['link'] ?? null;
    $listID = $_GET['listID'] ?? null;
    

    if (isset($_POST['submit'])) //functionality is incomplete but form validation in php is functional (title is the only required field)
    {
        //will upload information here
        if (!isset($title) || strlen($title) === 0) 
        {
            $errors['title'] = true;
        }

        if(count($errors)===0)
        {
            
            //SQL statement to insert values from form into wishlistitems table
            $pdo = connectDB();
            $updateWishListItem = $pdo->prepare('UPDATE wishlistitems SET title = ?, description = ?, itemLink = ? WHERE itemID = ?');
            $updateWishListItem->execute([$title,$description,$link,$itemID]);            
            $headerString = "refresh:0, url=Project_viewitem.php?itemID=$itemID";
            header($headerString);
            
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Final Project - Edit Item</title>
      <link rel="stylesheet" href="styles/project_master.css" />
      <!-- link rel to any css sheets used -->
</head>
<body>
    <?php include "includes/header.php";?>
    <?php include "includes/nav.php";?>
    <main>
        <h2> Add an Item </h2>
        <form enctype ="multipart/form-data" id="itemadd" name="itemadd" method="POST" >
            <!-- title input (required) -->
            <div>
                <label for="title">Item title: </label>
                <input type="text" name="title" id="title" placeholder="Enter  title here" value="<?=$itemInfo['title']?>" />
                <span class="error <?=!isset($errors['title']) ? 'hidden' : "";?>">Please enter a title.</span>
            </div>
            <!-- optional description input -->
            <div>
                <label for="descrip">Description (optional):</label>
                <input type="text" name="descrip" placeholder="Enter description here" id="descrip" value="<?=$itemInfo['description']?>" />
            </div>

            <!--optional link input -->
            <div>
                <label for="link">Link to item (optional)</label>
                <input type="url" name="link" id="link" placeholder="Enter link here" value="<?=$itemInfo['itemLink']?>" />
            </div>
            <!--File upload -->
            <div>
                <input type="hidden" name="MAX_FILE_SIZE" value="10240" />
                <label for="image">Image Upload:</label>
                <input name="image" type="file" id="image" />
            </div>
            <!-- submit  button -->
            <div id="buttons">
                <button type="submit" name="submit">Submit</button>
            </div>
        </form>
    </main>
    <?php include "includes/footer.php";?>
</body>
