<?php
    include 'includes/library.php';
    session_start();
    if(!isset($_SESSION['user']))
    {
        header("Location:Project_login.php");
        exit();
    }

    //if itemID has not been passed to url paramters, die
    if(!isset($_GET['itemID'])){
        die('Error : itemID not set');
    }

    //get itemID from url paramters
    $itemID = $_GET['itemID'];

    $pdo = connectDB();

    //get just the associated list ID
    $listIDStub=$pdo->prepare('SELECT wishListID FROM wishlistitems WHERE itemID = ?');
    $listIDStub->execute([$itemID]);
    $listID = implode($listIDStub->fetch());
    $wishlistTheme = $pdo->prepare('SELECT Theme FROM wishlisttable WHERE listID = ?;');
    $wishlistTheme->execute([$listID]);
    $theme = $wishlistTheme->fetch()['Theme'];
    switch($theme)
    {
        case 1:
            $theme = "project_theme_main.css";
            break;
        case 2:
            $theme = "project_theme_christmas.css";
            break;
        case 3:
            $theme = "project_theme_halloween.css";
            break;
        case 4:
            $theme = "project_theme_valentines.css";
            break;
        case 5:
            $theme = "project_theme_wedding.css";
            break;
    }

    if(!isset($_SESSION['publicListID'])){
        header("Location: Project_publicLogin.php?listID=".strval($listID)); //redirect to the login
        exit();
    }
    else if($_SESSION['publicListID']!=$listID){
        header("Location: Project_publicLogin.php?listID=".strval($listID)); //redirect to the login
        exit();
    }

    $listItem=$pdo->prepare('SELECT * FROM wishlistitems WHERE itemID = ?');
    $listItem->execute([$itemID]);

    $itemInfo = $listItem->fetch();

    //set values from SQL select
    $title = $itemInfo['title'];
    $descrip = $itemInfo['description'];
    $link = $itemInfo['itemLink'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Final Project - Item View</title>
      <!-- link rel to any css sheets used -->
      <link rel = "stylesheet" href = "styles/project_master.css" />
</head>
<body>
    <?php include "includes/header.php";?>
    <?php include "includes/nav.php";?>
    <link rel="stylesheet" href="styles/<?=$theme?>"/>
    <main>
        <h2>Item View : <?=$title?> </h2>
        <h3>Item Description:</h3> <p><?=$descrip?></p>
        <h3>Item Link: </h3> </h3> <p><?=$link?></p>
        <h3>Image of Item (Placeholder):</h3> <!-- img not ready yet -->
        <img
            src="https://i.kym-cdn.com/entries/icons/original/000/000/305/duckroll169.jpg"
            alt="Image of item will go here (placeholder alt text)"
            height="200px"
            width="200px"
        />
    </main>
</body>
