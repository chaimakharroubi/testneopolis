<?php
$conn=mysqli_connect('localhost','root','','cart_db');

if(isset($_POST["submit"])){
    $name=$_POST["nom_product"];
    $price=$_POST["prix_product"];

    if($_FILES["image"]["error"] === 4){

        $message= "tous les champs doivent etre remplis";
    }
    else
    {

        $fileName= $_FILES["image"]["name"];
        $fileSize= $_FILES["image"]["size"];
        $tmpName= $_FILES["image"]["tmp_name"];

        $validImageExtension= ['jpg', 'jpeg' ,'png'];
        $imageExtension= explode('.', $fileName);

        $imageExtension= strtolower(end($imageExtension));

        if(!in_array($imageExtension,$validImageExtension)){

            $message= "l'extension de l'image est invalide";

        }
        else if ($fileSize >1000000){

            $message= "L'image est trés grande";

        }
        else
        {
            $newImageName =uniqid();
            $newImageName.= '.'. $imageExtension;

            
            move_uploaded_file($tmpName, 'image/'.$newImageName);


            $query="INSERT INTO repas (nom , price, image ) VALUES ('$name','$price','$newImageName')";

            mysqli_query($conn, $query);
            
            $message= "Pizza a été ajouté  avec succès";
        }

    }
}


if(isset($_POST['delete'])){
    $ids =$_POST['delete'];
    mysqli_query($conn,"DELETE FROM repas WHERE id=$ids");
    header('location:HomeScreen.php');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>
            La gourmandise 
        </title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
        <link rel="stylesheet" href="style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />    
        <body>
          <header>
            <div class="header-1">
                <div class ="share">
                    <span> Follow us :</span>
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
                <div class ="call">
                    <span> call us :</span>
                    <a href="#">+21696394605</a>
                </div>
            </div>
            <div class ="header-2">
                <a href="#" class="logo"><i class="fa-sharp fa-regular fa-pizza-slice"></i>La gourmandise </a>
                <form action="" class="search-bar-container">
                    <input type="text" id="search-bar" placeholder="search here....">
                    <label for="search-bar" class="fas fa-search"></label>
                </form>
            </div>
            <div class="header-3">
                <nav class="nav-bar">
                    <a href="#home">Home</a>
                    <a href="#home">About</a>
                    <a href="#home">Menu</menu></a>
                    <a href="#home">Orders</a>
                    <a href="#home">Contact</a>
                </nav>
            
            <div class="icons">
                <a href="#" class="fas fa-shopping-cart"></a>
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-user-circle" onClick="location.href='LoginScreen.php'"></a>
            </div>
          </header>
        <section class="home" id="home">
            
            <div class="home-slider">
                
                <div class="wrapper">
                    
                    <div class="slide">
                        <div class="box" style="background: url('pizza-saumon-creme-fraiche-recette.jpg');">
                            <div class="content">
                                <h3>Pizza saumon </h3>
                                <a href="#" class="btn">show me</a>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="slide">
                        <div class="box" style="background: url(Chicken-Pesto-Pizza-fabeveryday-4x3-1-9772894ba85048d6992d327f2b904908);">
                            <div class="content">
                                <h3>Pizza pesto </h3>
                                <a href="#" class="btn">show me</a>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="slide">
                        <div class="box" style="background: url(i10453-pizza-aux-crevettes.jpg);">
                            <div class="content">
                                <h3>Pizza fruit de mer</h3>
                                <a href="#" class="btn">show me</a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

        </section>


        <div class="container">

            <?php if(isset($message)) { echo "<div class='message'>".$message."</div>"; } ?>

            <div class="admin-product-container">
                <form action="<?php $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
                    <h3>ajouter une autre pizza au menu</h3>
                    <input type="text" name="nom_product" placeholder="taper le nom du repas" class="box"><br/>
                    <input type="number" name="prix_product" placeholder="taper le prix du repas" class="box"><br/>
                    <input type="file"  accept="image/jpg, image/jpeg, image/png"  name="image" placeholder="taper l'image  du repas" class="box"><br/>
                    <input type="submit" name="submit" value="enregistrer" class="btn1">
                </form>
            </div>
        </div >



        
        <div class="product-display">
            <table class="product-display-table">
                <thead>
                    <tr>
                        <th>image</th>
                        <th>nom</th>
                        <th>prix</th>
                        <th colspan="2">action </th>
                    </tr>
                </thead>
                <?php
          
                    $row=mysqli_query($conn,"SELECT * FROM  repas");
            
                    foreach($row as $row):
                ?>
                    <tr>
                
                        <td><img src="image/<?php echo $row['image']; ?>"  width="100" height="50" alt="" ></td>
                        <td><?php echo $row["nom"];?></td>
                        <td><?php echo $row["price"];?></td>
                        <td>
                            <a href="HomeScreen.php?edit=<?php echo $row['id']; ?>" class="btn3"> <i class="fa-solid fa-edit fs-5 me-3"></i></a>
                            <a href="HomeScreen.php?delete=<?php echo $row['id']; ?>" class="btn3"> <i class="fa-solid fa-trash fs-5 me-3"></i></a>

                        </td>

                    </tr>
                 <?php 
                    endforeach ; 
                ?>
            </table>
        </div >

       
    </body>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

</html>