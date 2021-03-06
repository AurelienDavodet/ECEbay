<?php

//debuter une session et connexion à la base de données
session_start(); 
class Database
{
    private static $dbHost = "localhost";
    private static $dbName = "ecebay";
    private static $dbUsername = "root";
    private static $dbUserpassword = "";
    
    private static $connection = null;
    
    public static function connect()
    {
        if(self::$connection == null)
        {
            try
            {
              self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName , self::$dbUsername, self::$dbUserpassword);
            }
            catch(PDOException $e)
            {
                die($e->getMessage());
            }
        }
        return self::$connection;
    }
    
    public static function disconnect()
    {
        self::$connection = null;
    }

}


?>

<?php 
//recupération de l'id qui correspond à un des trois type d'achat

if(!empty($_GET['id'])) 
    {
        $id = $_GET['id'];
    }

?>

<!-- page html -->

<!DOCTYPE html>
<html>
    <head>
        <title>ECEbay</title>
        
        <!-- Required meta tags -->
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        
        <link rel="stylesheet" type="text/css" href="acheter.css">
        
        <script type="text/javascript">
             $(document).ready(function(){
             $('.header').height($(window).height());
             });
        </script>
        
        <!-- script pour l'affichage des différents type -->
        
        <script>
                    function visibilite(thingId){
            var targetElement;
            var elements;
            targetElement = document.getElementById(thingId) ;

            // recupere tous les elements ayant pour nom de classe "Element"
            elements = document.getElementsByClassName("items")

            // parcoure tous ces elements et les cache
            for (var i = 0; i < elements.length; i++) {
                elements[i].style.display = "none" ;
            }

            //affiche uniquement l element selectionné
            if (targetElement.style.display == "none") {
                    targetElement.style.display = "" ;
            }
        }
        </script>
        
    </head>
    
    
    <!-- Debut body -->
    
    <body>
        
    <!-- Header navbar-->
        <?php include '../template/header.php'; ?>
        
   
        <!-- titre de la categorie -->
        <div class="container-fluid">
        
        <div class="titrecat">
        <?php 
                    $db = Database::connect();
                   $statement = $db->prepare('SELECT type.name FROM type WHERE type.id=?');
                    $statement->execute(array($id));
                    while ($type = $statement->fetch()) 
                    {
                 
                        echo $type['name'];
                    }

                Database::disconnect();
                    ?>
            <div class="separation"></div>
        </div>
            
            
            <!-- partie recherche  -->
        <div class="container-fluid">
             <div class="recherche">
            </br>
             <form class="form" role="form" action="rechercher.php" methode="post" >
                   <input type="text" class="form-control" id="recherche" name="recherche" placeholder="recherche" value="">
               </form>
        </div>
        
        </br></br>
        
        <!-- liste d'items -->
    
        <div>
            <div class="row">
                <div class="col-md-2">
                    </br>
                <!-- choix de la catégorie, trois choix possivbles -->
                    <ul style="list-style:none;">
                        <li><a type="button" class="btn-vue" onclick="visibilite('1');">Férailles et Trésors</a></li>
                        <li><a type="button" class="btn-vue" onclick="visibilite('2');">Bons musée</a></li>
                        <li><a type="button" class="btn-vue" onclick="visibilite('3');">Articles VIP</a></li>
                    </ul>
               
                </br>
            </div>
            
            <div class="col-md-10">
  
                 
                <div id="1" class="items" >
                    
                    <h4>Férailles et Trésors</h4>
                    <div class="separation"></div>
                    </br>
                    <div class="row">
                    <!-- connexionn à la base de données et récupération des informations -->
                   <?php 
                    $db = Database::connect();
                    //recupération des informations de l'ensemble des articles de la catégorie et du type choisis
                   $statement = $db->prepare('SELECT article.image, article.nom, article.prix, article.id FROM article WHERE type=? AND categorie=1');
                    $statement->execute(array($id));
                    //affichage ligne par ligne de l'ensembles des articles récupérés
                    while ($article = $statement->fetch()) 
                    {
                    //affichage des différentes informations 
                        echo '<div class="col-sm-6 col-md-4">
                        <div class="item">
                        <img class="imgobj" src="../images/'.$article['image'].'">
                        <div class="description">
                            <span class="titre_objet">'.$article['nom'].'</span>
                            <br>
                            <span class="description_objet">Ferailles et Trésor</span>
                            <br>
                            <span class="description_objet">'.$article['prix'].'€</span>
                            <br>
                            <br>
                            <a href="objet.php?id='.$article['id'].'" class="btn btn-order" role=button>Voir</a>
                        </div>
                        </div>
                     </br><br><br><br>
                </div> ';
                    
                    }

                Database::disconnect();
                    ?>
                
                
                </div> </div>
                <!-- même chose pour autre catégorie -->
                <div id="2" class="items" style="display: none;">
        
                    <h4>Bons musée</h4>
                    <div class="separation"></div>
                    </br>
                    
                    
                 <div class="row">
                    <!-- connexionn à la base de données et récupération des informations -->
                   <?php 
                    $db = Database::connect();
//recupération des informations de l'ensemble des articles de la catégorie et du type choisis
                   $statement = $db->prepare('SELECT article.image, article.nom, article.prix, article.id FROM article WHERE type=? AND categorie=2');
                    $statement->execute(array($id));
                    //affichage ligne par ligne de l'ensembles des articles récupérés
                    while ($article = $statement->fetch()) 
                    {
                 //affichage des différentes informations 
                        echo '<div class="col-sm-6 col-md-4">
                    <div class="item">
                    <img class="imgobj" src="../images/'.$article['image'].'">
                    <div class="description">
                        <span class="titre_objet">'.$article['nom'].'</span>
                        <br>
                        <span class="description_objet">Ferailles et Trésor</span>
                        <br>
                        <span class="description_objet">'.$article['prix'].'</span>
                        <br>
                        <br>
                        <a href="objet.php?id='.$article['id'].'" class="btn btn-order" role=button>Voir</a>
                    </div>
                    </div>
                     </br><br><br><br>
                </div> ';
                    
                    }

                Database::disconnect();
                    ?>
                
                
                </div>
                
                
                </div> 

                <div id="3" class="items" style="display: none;">
        
                    <h4>Articles VIP</h4>
                    <div class="separation"></div>
                    </br>

                 <div class="row">
                    <!-- connexionn à la base de données et récupération des informations -->
                   <?php 
                    $db = Database::connect();
//recupération des informations de l'ensemble des articles de la catégorie et du type choisis
                   $statement = $db->prepare('SELECT article.image, article.nom, article.prix, article.id FROM article WHERE type=? AND categorie=3');
                    $statement->execute(array($id));
                    //affichage ligne par ligne de l'ensembles des articles récupérés
                    while ($article = $statement->fetch()) 
                    {
                 //affichage des différentes informations 
                        echo '<div class="col-sm-6 col-md-4">
                    <div class="item">
                    <img class="imgobj" src="../images/'.$article['image'].'">
                    <div class="description">
                        <span class="titre_objet">'.$article['nom'].'</span>
                        <br>
                        <span class="description_objet">Ferailles et Trésor</span>
                        <br>
                        <span class="description_objet">'.$article['prix'].'</span>
                        <br>
                        <br>
                        <a href="objet.php?id='.$article['id'].'" class="btn btn-order" role=button>Voir</a>
                    </div>
                    </div>
                     </br><br><br><br>
                </div> ';
                    
                    }

                Database::disconnect();
                    ?>
                
                
                </div>
                
                
                </div> 
            
            </div>
    
        </div>
     </div>
 </div>
    </div>
            
        </div>
        
        <!-- partie recherche -->
        
       
        

        
        </br></br>
        
       
<?php include '../template/footer.php'; ?>
        
    </body>
</html>