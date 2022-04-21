<?php
    include 'includes/library.php';
    session_start();

    //get listID from url paramters
    $listID = $_GET['listID'];

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

    if(!isset($_SESSION['publicListID'])){
        header("Location: Project_publicLogin.php?listID=".strval($listID)); //redirect to the login
        exit();
    }
    else if($_SESSION['publicListID']!=$_GET['listID']){
        header("Location: Project_publicLogin.php?listID=".strval($listID)); //redirect to the login
        exit();
    }

    

    $pdo = connectDB(); //connect to the database

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
        <title>All Lists&colon; Buck-et Registry &dash; Project COIS 3420H</title>
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
                           <td><?=$row['status']?></td>
                           <td>
                                <a href="Project_publicViewitem.php?itemID=<?=$row['itemID']?>" title="View Item"><span class="fa-solid fa-eye" aria-hidden="true"></span> <span class="sr-only">View Item</span></a>
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