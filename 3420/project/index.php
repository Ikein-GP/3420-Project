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
    $printLists = $pdo->prepare('SELECT * FROM wishlisttable WHERE ownerID = ? ORDER BY listID;'); //prepare the query to add the name and score to the database
    $printLists->execute([$_SESSION['id']]); //execute the prepared query

    if(isset($_POST['disable'])){
        $updateQuery = $pdo->prepare('UPDATE wishlisttable SET expiryDate = createDate WHERE listId = ?');
        $updateQuery->execute([$_POST['disable']]);
        header("Location:index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>All Lists&colon; Buck-et Registry &dash; Project COIS 3420H</title>
        <link rel="stylesheet" href="styles/project_master.css" />
        <script src="https://kit.fontawesome.com/2b1acf3db3.js" crossorigin="anonymous"></script>
        <script defer src="scripts/index.js"></script>
    </head>
    <body>
       <header>
            <?php include "includes/header.php";?>
            <?php include "includes/nav.php";?>
       </header> 
       <main>
           <h2>All Lists</h2>
           <section id="viewlists">
           <?php foreach($printLists as $row): ?>
                <section>
                <h3><?php echo($row['title']); ?></h3>
                <p><?php echo($row['description']);?></p>
                <p><span class="date">Created date: <?php echo($row['createDate']); ?></span> &middot; <span class="expiry">Expiry date: <?php echo($row['expiryDate']); ?></span></p>
                <div>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <ul>
                            <li><a href="Project_ViewList.php?listID=<?php echo($row['listID']);?>" title="View List"><span class="fa-solid fa-list" aria-hidden="true"></span> <span class="sr-only">View List</span></a></li>
                            <li><a href="Project_additem.php?listID=<?php echo($row['listID']);?>" title="Add item to List"><span class="fa-solid fa-plus" aria-hidden="true"></span> <span class="sr-only">Add item to List</span></a></li>
                            <li><a href="Project_editWishlist.php?listID=<?php echo($row['listID']);?>" title="Edit List"><span class="fa-solid fa-pen-to-square" aria-hidden="true"></span> <span class="sr-only">Edit List</span></a></li>
                            <li>
                                <button <?php if($row['createDate']==$row['expiryDate']): echo "disabled "; endif?> title="disable list" type="submit" name="disable" value ="<?=$row['listID']?>"><span class="fa-solid fa-ban" aria-hidden="true"></span><span class="sr-only">Disable List</span></button>
                            </li>
                            <li><a href="Project_deleteList.php?listID=<?php echo($row['listID']);?>" title="Delete List"><span class="fa-solid fa-trash" aria-hidden="true"></span> <span class="sr-only">Delete List</span></a></li>
                            <li><button type="button" title="Copy Public View Link to Clipboard" value="https://loki.trentu.ca/~gregoryprouty/3420/project/Project_publicViewList?listID=<?php echo($row['listID']);?>"><span class="fa-solid fa-eye" aria-hidden="true"></span> <span class="sr-only">Copy Public View Link to Clipboard</span></button></li>
                        </ul>
                    </form>
                </div>
                </section>
            <?php endforeach ?>
           </section>
       </main>
       <?php include "includes/footer.php";?>
    </body>
</html>