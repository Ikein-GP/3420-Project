<?php
    include 'includes/library.php';
    session_start();
    if(!isset($_SESSION['user']))
    {
        header("Location:Project_login.php");
        exit();
    }

    //if itemID has not been passed to url paramters, die
    if(!isset($_GET['itemID']) && !(isset($_POST['delete']) || isset($_POST['cancel']))){
        die('Error : itemID not set');
    }

    //get itemID from url paramters
    $itemID = $_GET['itemID'];

    $pdo = connectDB();
    $listItem=$pdo->prepare('SELECT * FROM wishlistitems WHERE itemID = ?');
    $listItem->execute([$itemID]);

    $itemInfo = $listItem->fetch();

    $listID = $itemInfo['wishListID'];

    $wishlist = $pdo->prepare('SELECT * FROM wishlisttable WHERE listID = ?;');
    $wishlist->execute([$listID]);
    //set values from SQL select
    $title = $itemInfo['title'];
    $descrip = $itemInfo['description'];
    $link = $itemInfo['itemLink'];

    //if delete selected.
    if(isset($_POST['delete'])){
        $deleteQuery = $pdo->prepare('DELETE FROM wishlistitems WHERE itemID = ?');
        $deleteQuery->execute([$_POST['delete']]);
        header("Location:Project_itemDeleted.php");
        exit();
    }
    else if(isset($_POST['cancel'])){
        header("Location:index.php");
        exit();
    }
    

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
    <main>
    <h2>Delete the following item from the list?</h2>
           <section id="deleteItem">
                <div>
                    <h4>List Name: <?=$wishlist->fetch()['title']?></h4>
                </div>
               <table>
                   <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Link</th>
                        </tr>
                   </thead>
                   <tbody>
                       <tr>
                           <td><?=$itemInfo['title']?></td>
                           <td><?=$itemInfo['description']?></td>
                           <td><a href="<?=$itemInfo['itemLink']?>">Item Link</a></td>
                       </tr>
                   </tbody>
               </table>
               <div>
                   <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <button type="submit" name="delete" value=<?=$itemID?>>Delete</button>
                        <button type="submit" name="cancel">Cancel</button>
                    </form>
               </div>
           </section>
    </main>
</body>
