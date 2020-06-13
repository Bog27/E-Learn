<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>E-Learn</title>

    <!-- mio stile
    <link rel="stylesheet" href="style.css"> -->
    <style>
        nav{
            display: inline;
            background-color: yellow;
        }
        .bd-navbar{
            background-color: #000 !important;
        }
    </style>
    
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body> 
<?php 
    session_start(); 
    require_once ('./../Classi/Utente.php');
    require ('./../Classi/Corso.php');
    require_once ('./../Classi/Post.php');
	//echo $_GET['id'];
    
    if (!isset($_SESSION['Utente']))
    	header('Location: ./../index.php');
	if (!isset($_GET['id']))
    	header('Location: ./../Home.php');
	$id=$_GET['id'];
    $Utente = Utente::parseJsonToUtente($_SESSION['Utente']);
    $corso = new Corso("a","a",$Utente);
    $info = $corso->selectCorso($id);
    //print_r($info);
    	echo '
                <nav class="navbar navbar-expand-lg navbar-light bg-light bd-navbar" style="background-color: #e3f2fd;">

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- sinistra -->
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">                            
                                <a class="navbar-brand" href="./../Home.php"><img width="50px" high="50px" src="./../Immagini/home.png" alt="Immagine di profilo"></a>
                            </li>
                        </ul>
                        <!-- destra -->
                        <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
                            <li class="nav-item" style="margin-left: 5px">
                                <a class="nav-link" href="./creaPost.php?id='.$id.'">Crea un post</a>
                            </li>
                            <li class="nav-item">
                                <a class="navbar-brand" href="./../logout.php"><img width="40px" high="40px" src="./../Immagini/logout.png" alt="Logout"></a>
                            </li>
                        </ul>
                    </div>
                </nav> ' ;
 	echo '<div class="row">
    		<div class="col-sm-2"><h1 style="text-align:center; margin-top:5px">'.$id.'</h1></div>
            <div class="col-sm-8">
                	<center>
                    	<h2 style="text-align:center; margin-top:5px">'
      						.$info[0]['Nome'].'
      					</h2>
                        <h4 style="text-align:center;">'
      						.$info[0]['Descrizione'].'
      					</h4>';
              
              $p=new Post("a","a",NULL);
              $res = $p->selectIdPost($id);
              //print_r($res);
              foreach ($res as $r)
              {
              	$i=$r['IdPost'];
                $temp=$p->selectPost($i);
                echo '<div style="height: auto; background-color: #f0ffff; border: 1px solid black; margin-top:20px">
                                    <p style="font-size: 40px; bottom: 0px;">'
                                        .$temp[0]['Titolo'].
                                    '</p>
                                    <p style="font-size: 25px; bottom: 0px;">'
                                        .$temp[0]['Corpo'].'
                                    </p>';
                if ($temp[0]['nomeFile']!=NULL)
                {
                  echo '
					<p style="font-size: 25px; bottom: 0px;">
                      <a href="./'.$temp[0]['nomeFile'].'" download>Scarica '.$temp[0]['nomeFile'].'</a></p>';
                }
                echo '</div>';
              }
              echo '
              </center>
        	</div>
            <div class="col-sm-2"></div>
     	</div>';
 	
    
?>

    

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>

<?php
	/*foreach ($temp as $v)
              {
              
              	echo $v;
                  echo '<div style="height: auto; background-color: #f0ffff; border: 1px solid black; margin-top:20px">
                                    <p style="font-size: 40px; bottom: 0px;">'
                                        .$v['Titolo'].
                                    '</p>
                                    <p style="font-size: 25px; bottom: 0px;">'
                                        .$v['Corpo'].'
                                    </p>
                            </div>';
              }      */
?>