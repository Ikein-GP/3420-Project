<?php
    include 'includes/library.php';
    session_start();

    if(!isset($_SESSION['user']))
    {
        header("Location:Project_login.php");
        exit();
    }
    $errors = array();

    if(!isset($_GET['listID']))
    {
        die("Error: No listID set");
        exit();
    }


    //get listID from url paramters
    $listID = $_GET['listID'];

    $pdo = connectDB(); //connect to the database

    $wishlistStub = $pdo->prepare('SELECT ownerID FROM wishlisttable WHERE listID = ?;');
    $wishlistStub->execute([$listID]);
    
    $ownerID = $wishlistStub->fetch()['ownerID'];

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

    if($ownerID != $_SESSION['id'])
    {
        header("Location:index.php");
        exit();
    }

    $wishlistItems = $pdo->prepare('SELECT * FROM wishlistitems WHERE wishListID = ?;'); //prepare the query to add the name and score to the database
    $wishlistItems->execute([$listID]); //execute the prepared query

    $wishlist = $pdo->prepare('SELECT * FROM wishlisttable WHERE listID = ?;');
    $wishlist->execute([$listID]);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>All Lists&colon; Buck-et Registry &dash; Project COIS 3420H</title>
        <link rel="stylesheet" href="styles/project_theme_christmas.css" />
        <link rel="stylesheet" href="styles/project_master.css" />
        <script src="https://kit.fontawesome.com/2b1acf3db3.js" crossorigin="anonymous"></script>
        <script defer src="scripts/viewItem.js"></script>
    </head>
    <body>
       <header>
            <?php include "includes/header.php";?>
            <?php include "includes/nav.php";?>
            <link rel="stylesheet" href="styles/<?=$theme?>"/>
       </header> 
       <main>
           <h2>Viewing list : <?=$wishlist->fetch()['title']?></h2>
           <section id="listItems">
               <table>
                   <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Link</th>
                            <th>Action</th>     
                        </tr>
                   </thead>
                   <tbody>
                    <?php $no=1 ;foreach($wishlistItems as $row):?>
                       <tr>
                           <td><?=$no?></td>
                           <td><?=$row['title']?></td>
                           <td><?=$row['description']?></td>
                           <td><a href="<?=$row['itemLink']?>">Item Link</a></td>
                           <td>
                                <input class="hidden" value="<?=$row['itemID']?>" id="item">
                                <a href="Project_viewitem.php?itemID=<?=$row['itemID']?>" id="view" title="View Item"><span class="fa-solid fa-eye" aria-hidden="true" id="Project_viewitem.php?itemID=<?=$row['itemID']?>"></span> <span class="sr-only">Edit Item</span></a>
                                <a href="Project_editItem.php?itemID=<?=$row['itemID']?>" title="View List"><span class="fa-solid fa-pen-to-square" aria-hidden="true"></span> <span class="sr-only">Edit Item</span></a>
                                <a href="Project_deleteItem.php?itemID=<?=$row['itemID']?>" title="Delete Item"><span class="fa-solid fa-trash" aria-hidden="true"></span> <span class="sr-only">Delete Item</span></a>
                           </td>
                       </tr>
                    <?php $no++; endforeach ?>
                   </tbody>
               </table>
           </section>
       </main>
       <?php include "includes/footer.php";?>
    </body>
</html>