<?php
    include 'includes/library.php';
    session_start();
    if(!isset($_SESSION['user']))
    {
        header("Location:Project_login.php");
        exit();
    }

    //if listID has not been passed to url paramters, die
    if(!isset($_GET['listID']) && !(isset($_POST['delete']) || isset($_POST['cancel']))){
        die('Error : listID not set');
    }

    //get listID from url paramters
    $listID = $_GET['listID'];

    $pdo = connectDB();

    $wishlist = $pdo->prepare('SELECT * FROM wishlisttable WHERE listID = ?;');
    $wishlist->execute([$listID]);
    

    //if delete selected.
    if(isset($_POST['delete'])){
        //Delete all items assoicated with the list first.
        $deleteItemsQuery = $pdo->prepare('DELETE FROM wishlistitems where wishListID = ?');
        $deleteItemsQuery->execute([$_POST['delete']]);
        $deleteQuery = $pdo->prepare('DELETE FROM wishlisttable WHERE listID = ?');
        $deleteQuery->execute([$_POST['delete']]);
        header("Location:Project_listDeleted.php");
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
    <h2>Are you sure you want to delete the list: <?=$wishlist->fetch()['title']?>?</h2>
        <div>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <button type="submit" name="delete" value=<?=$listID?>>Delete</button>
                <button type="submit" name="cancel">Cancel</button>
            </form>
        </div>
    </main>
</body>
