<?php
    session_start();
    require_once ('./../Classi/Utente.php');

    //print_r($_POST); 
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['tipo']))
    {
        try
        {
            $Utente = new Utente($_POST['email'], $_POST['password'], $_POST['tipo']);
        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            die();
        }
    	
        if ($Utente->accedi())
        {
            // passa l'oggetto alla sessione
            $_SESSION['Utente'] = $Utente->parseUtenteToJson();
            header('Location: ./../Home.php');
        }
        else
        {
        	echo "<h4 style='text-align:center'>Password o email errate</h4>";
            header("Refresh:2.5; url= http://bog27.altervista.org/E-Learn");
            //echo "<script> alert('Password o email errate'); </script>";
        }
    }
    else
    {
        echo "<h4 style='text-align:center'>Password o email errate</h4>";
      	header("Refresh:2.5; url= http://bog27.altervista.org/E-Learn");
    }
    /*$_SESSION['email'] = $_POST['email'];
    require("./../CredenzialiDB.php");

    $conn = new mysqli($nomehost, $nomeuser, $password, $dbname);	
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql="SELECT Password, Tipologia FROM Utenti WHERE Email='". $_POST['email'] ."'";
    
    $result = $conn->query($sql);
    if ($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();

        $_SESSION['tipo'] = $row['Tipologia'];

        if (password_verify($_POST['password'], $row['Password']))
        {
            echo "dentro";
            header('Location: ./../Home.php');
            //header('Location: http://localhost:8080/Moodle/Home.php');
        }
        else 
        {
            echo "fuori";
            echo "<script> alert('Password o email errate'); </script>";
        }
    }
    else 
    {
        echo "0 results";
    }
    $conn->close();*/
?>
