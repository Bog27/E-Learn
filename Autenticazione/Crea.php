<?php
    session_start();
    require("./../CredenzialiDB.php");
    require_once ('./../Classi/Utente.php');

    if(isset($_SESSION['Utente']))
    {
        //print_r($_SESSION['Utente']);
        try 
        {
           $Utente = Utente::parseJsonToUtente($_SESSION['Utente']);
           $Utente = new Utente($Utente->Email, $Utente->Password, $Utente->Tipologia);
        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            die();
        }
        $Utente->insertUtenti();
        //$_SESSION['Utente'] = $Utente->parseUtenteToJson();
        
        /*
        $_SESSION['email'] = $Utente->getEmail();
        $_SESSION['tipo'] = $Utente->getTipologia();
        $conn = new mysqli($nomehost, $nomeuser, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connessione al db fallita: " . $conn->connect_error);
        }   
        
        $sql = "INSERT INTO Utenti(Email, Password, Tipologia) VALUES (?,?,?);";
        $stmt = $conn->prepare($sql);
        
        $email = $_POST['email'];
        $pwd =password_hash($_POST['password'], PASSWORD_BCRYPT);
        $tipo ='i'; 
    
        $stmt->bind_param('sss', $_SESSION['email'], $_SESSION['password'], $_SESSION['tipo']);
        $stmt->execute();*/
        //header("location: http://bog27.altervista.org/E-Learn/Home.php");
    }
    else
    {
        echo "<h4 style='text-align:center'>Errore</h4>";
      	header("Refresh:2.5; url= http://bog27.altervista.org/E-Learn");
    }

?>