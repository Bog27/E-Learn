<?php
require_once ('./../Classi/Post.php');
session_start();
if (!isset($_SESSION['Utente']))
  header("url= http://bog27.altervista.org/E-Learn");
if (!isset($_GET['id']))
  header("url= http://bog27.altervista.org/E-Learn/Home.php");
$id = $_GET['id'];
if(isset($_POST['titolo']) && isset($_POST['corpo']))
{
	$file=NULL;
    //$testo="<h2 style='text-align:center; margin-top:15px'>Il post è stato creato correttamente!</h2>";
	if (isset($_FILES['file_caricato']))
    {
        $nome = $_FILES['file_caricato']['name'];
        $nome_tmp = $_FILES['file_caricato']['tmp_name'];
        move_uploaded_file($nome_tmp,$nome);
        $file=$nome;
        //$testo="<h2 style='text-align:center; margin-top:15px'>Il file è stato caricato correttamente!</h2>";
        unset ($_FILES['file_caricato']);
    }
    $p= new Post($_POST['titolo'],$_POST['corpo'],$file);
    $ret=$p->insertPost();
    $p->insertPost2Corso($id,$ret);
    echo "<h2 style='text-align:center; margin-top:15px'>Il post è stato creato correttamente!</h2>";
    header("Refresh:2.5; url= http://bog27.altervista.org/E-Learn/Home.php");
    
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Learn</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
   	<h2 style="text-align:center; margin-top:10%">Crea un post</h2>
      <?php echo '<form action="./creaPost.php?id='.$_GET['id'].'" ENCTYPE="multipart/form-data" method="POST">';?>
        <div class="form-group">
          <label for="titolo">Titolo</label>
          <input type="text" name="titolo" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="corpo">Contenuto</label>
          <textarea name="corpo" class="form-control" required></textarea>
        </div>
        <input type="file" name="file_caricato">
        <input type="submit" value="Crea" name="submit">
      </form>
    </div>
    <div class="col-sm-2"></div>
  </div>