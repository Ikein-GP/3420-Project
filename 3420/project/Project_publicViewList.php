<?php
    include 'includes/library.php';
    session_start();

    if((!isset($_GET['listID']) && !isset($_POST)))
    {
        die("Error: No listID set");
        exit();
    }
    $pdo = connectDB(); //connect to the database

    //get listID from url paramters
    $listID = $_GET['listID'] ?? null;

    if(!isset($_SESSION['user']))
    {
        header("Location:Project_login.php");
        exit();
    }
    $errors = array();

    if(isset($_POST['markAsBought'])){
        $updateQuery = $pdo->prepare('UPDATE wishlistitems SET status = 1 WHERE itemID = ?');
        $updateQuery->execute([$_POST['markAsBought']]);
       
        $header = "Location:Project_publicViewList.php?listID=".$_POST['listID'];
        header($header);
        die();
    }
    else if(isset($_POST['unMarkAsBought'])){
        $updateQuery = $pdo->prepare('UPDATE wishlistitems SET status = 0 WHERE itemID = ?');
        $updateQuery->execute([$_POST['unMarkAsBought']]);
       
        $header = "Location:Project_publicViewList.php?listID=".$_POST['listID'];
        header($header);
        die();
    }

    if(!isset($_SESSION['publicListID'])){
        header("Location: Project_publicLogin.php?listID=".strval($listID)); //redirect to the login
        exit();
    }
    else if($_SESSION['publicListID']!=$_GET['listID']){
        header("Location: Project_publicLogin.php?listID=".strval($listID)); //redirect to the login
        exit();
    }



    $wishlist = $pdo->prepare('SELECT * FROM wishlisttable WHERE listID = ?;');
    $wishlist->execute([$listID]);
    
    $wishlistInfo = $wishlist->fetch();

    //get creation and expiry dates, replace the / with a - 
    $createDate = date_create(str_replace("/","-",$wishlistInfo['createDate']));
    $expiryDate = date_create(str_replace("/","-",$wishlistInfo['expiryDate']));


    if($expiryDate<=$createDate){
        die("Error: This wishlist has expired.");
    }
    //date_format($expiryDate,'d/m/Y');


    $wishlistItems = $pdo->prepare('SELECT * FROM wishlistitems WHERE wishListID = ?;'); //prepare the query to add the name and score to the database
    $wishlistItems->execute([$listID]); //execute the prepared query

    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Viewing list : <?=$wishlistInfo['title']?> Project COIS 3420H</title>
        <link rel="stylesheet" href="styles/project_master.css" />
        <script src="https://kit.fontawesome.com/2b1acf3db3.js" crossorigin="anonymous"></script>
    </head>
    <body>
       <header>
            <?php include "includes/header.php";?>
            <?php include "includes/nav.php";?>
       </header> 
       <main>
           <h2>Viewing list : <?=$wishlistInfo['title']?></h2>
           <section id="listItems">
               <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <table>
                        <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Link</th>
                                    <th>Status</th>
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
                                    <?php
                                        if($row['status']==0){
                                            echo "Not Purchased";
                                        }
                                        else{
                                            echo "Purchased";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <input type="text" name = "listID" value = "<?=$listID?>"hidden></input>
                                    <a href="Project_publicViewitem.php?itemID=<?=$row['itemID']?>" title="View Item"><span class="fa-solid fa-eye" aria-hidden="true"></span> <span class="sr-only">View Item</span></a>
                                    <?php if($row['status']==0):?>
                                    <button title="Mark as bought" type="submit" name="markAsBought" value="<?=$row['itemID']?>"><span class="fa-solid fa-check" aria-hidden="true"></span> <span class="sr-only">Mark as bought</span></button>
                                    <?php else:?>
                                    <button title="Unmark as bought" type="submit" name="unMarkAsBought" value="<?=$row['itemID']?>"><span class="fa-solid fa-remove" aria-hidden="true"></span> <span class="sr-only">Unmark as bought</span></button>    
                                    <?php endif ?>
                                </td>
                            </tr>
                            <?php $no++; endforeach ?>
                        </tbody>
                    </table>
                </form>
           </section>
       </main>
       <?php include "includes/footer.php";?>
    </body>
</html>