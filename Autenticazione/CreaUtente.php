<?php
    session_start();
    require("./../CredenzialiDB.php");
    require_once ('./../Classi/Utente.php');
    //$log = fopen ("./../Log/insert.txt", "w");
    
    /*if (!$log) {
        echo "<p>Unable to open remote file.\n";
        exit;
    }*/
    
    if((isset($_POST['tipo']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['conferma_password'])) && ($_POST['password'] == $_POST['conferma_password']))
    {
        /*
            - cerca se nel db c'è già un email
            - non c'è manda l'email di controllo all'utente
            - salva i dati

        */
        try
        {
            $Utente = new Utente($_POST['email'], $_POST['password'], $_POST['tipo']);
        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            die();
        }

        $risposta = $Utente->checkEmailRegistrazione();

        if ($risposta === 0)
        {
            echo "<h1> Email già in uso </h1>";
            header("Refresh:2.5; url= http://bog27.altervista.org/E-Learn");
        }
        else if ($risposta === -1)
        {
            echo "<h1> Email non settata </h1>";
          	header("Refresh:2.5; url= http://bog27.altervista.org/E-Learn");
        }
        else if($risposta == true)
        {
            echo "<h1> Ti abbiamo appena inviato una mail di conferma, clicca su Conferma per terminare la registrazione </h1>";
            /*$_SESSION['email'] = $Utente->getEmail();
            $_SESSION['tipo'] = $Utente->getTipologia();*/
            $_SESSION['Utente'] = $Utente->parseUtenteToJson();
        }
        else if($risposta == false)
        {
            echo "<h1> C'è stato un errore, riprova la pratica di registrazione  </h1>";
      		header("Refresh:2.5; url= http://bog27.altervista.org/E-Learn");
        }
        /*$conn = new mysqli($nomehost, $nomeuser, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connessione al db fallita: " . $conn->connect_error);
        }
        //$sql = "SELECT Id FROM Utenti WHERE Email='". $_POST['email'] ."' OR Username='". $_POST['email'] ."'";
        $sql = "SELECT Id FROM Utenti WHERE Email=?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $_POST['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        echo var_dump($result);
        
        if ($result->num_rows==0) {

            $_SESSION['email'] = $_POST['email'];
            $_SESSION['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $_SESSION['tipo'] = $_POST['tipo'];

            $to = $_POST['email'];
            $soggetto = "CONFERMA E-MAIL E-LEARN";
            // To send HTML mail, the Content-type header must be set
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';
            // Additional headers
            $headers[] = 'From: staff@e-learn.it';

            $messaggio = "
                    <!DOCTYPE html>
                    <head>
                        <style>
                            *{
                                margin: 0;
                                padding: 0;
                            }
                            a.bottone{
                                background: yellow;
                                padding: 10px;
                                text-align: center;
                                color: white;
                            }
                        </style>
                    </head>
                    <body>
                        <h1> Conferma e-mail </h1>
                        <p> Buongiorno, <br> Per continuare la registrazione e iniziare ad utilizzare il sito, cliccate sul bottone </p><br><br><br><br>
                        <a class='bottone' href='http://localhost:8080/Moodle/Autentificazione/Crea.php'> Conferma </a><br><br><br>
                    </body>
                    </html>";
            $success = mail($to, $soggetto, $messaggio, implode("\r\n", $headers));


            /*
            switch ($_POST['tipo'])
            {
                case 's':  
                    $sql = "INSERT INTO Utenti(Email, Password, Tipologia) VALUES (?,?,?);";
                    $stmt = $conn->prepare($sql);
                    
                    $email = $_POST['email'];
                    $pwd =password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $tipo ='s'; 

                    $stmt->bind_param('sss', $email, $pwd, $tipo);
                    $stmt->execute();
                    /*fwrite($log, $sql);
                    fclose($file);*/
                    /*
                    $_SESSION['email'] = $_POST['email'];
                    $_SESSION['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $_SESSION['tipo'] = $_POST['tipo'];

                    $to = $_POST['email'];
                    $soggetto = "CONFERMA E-MAIL E-LEARN";
                    // To send HTML mail, the Content-type header must be set
                    $headers[] = 'MIME-Version: 1.0';
                    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                    // Additional headers
                    $headers[] = 'From: staff@e-learn.it';

                    $messaggio = "
                            <!DOCTYPE html>
                            <head>
                                <style>
                                    *{
                                        margin: 0;
                                        padding: 0;
                                    }
                                    a.bottone{
                                        background: yellow;
                                        padding: 10px;
                                        text-align: center;
                                        color: white;
                                    }
                                </style>
                            </head>
                            <body>
                               <h1> Conferma e-mail </h1>
                               <p> Buongiorno, <br> Per continuare la registrazione e iniziare ad utilizzare il sito, cliccate sul bottone </p><br><br><br><br>
                               <a class='bottone' href='http://localhost:8080/Moodle/Autentificazione/Crea.php'> Conferma </a><br><br><br>
                            </body>
                            </html>";
                    $success = mail($to, $soggetto, $messaggio, implode("\r\n", $headers));
                break;
                case 'i':     
                    /*
                    $sql = "INSERT INTO Utenti(Email, Password, Tipologia) VALUES (?,?,?);";
                    $stmt = $conn->prepare($sql);

                    $email = $_POST['email'];
                    $pwd =password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $tipo ='i'; 

                    $stmt->bind_param('sss', $email, $pwd, $tipo);
                    $stmt->execute();
                    /*fwrite($log, $sql);
                    fclose($file);*/
                    /*
                    $_SESSION['email'] = $_POST['email'];
                    $_SESSION['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $_SESSION['tipo'] = $_POST['tipo'];

                    $to = $_POST['email'];
                    $soggetto = "CONFERMA E-MAIL E-LEARN";
                    // To send HTML mail, the Content-type header must be set
                    $headers[] = 'MIME-Version: 1.0';
                    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                    // Additional headers
                    $headers[] = 'From: staff@e-learn.it';

                    $messaggio = "
                            <!DOCTYPE html>
                            <head>
                                <style>
                                    *{
                                        margin: 0;
                                        padding: 0;
                                    }
                                    a.bottone{
                                        background: yellow;
                                        padding: 10px;
                                        text-align: center;
                                        color: white;
                                    }
                                </style>
                            </head>
                            <body>
                               <h1> Conferma e-mail </h1>
                               <p> Buongiorno, <br> Per continuare la registrazione e iniziare ad utilizzare il sito, cliccate sul bottone </p><br><br><br><br>
                               <a class='bottone' href='http://localhost:8080/Moodle/Autentificazione/Crea.php'> Conferma </a><br><br><br>
                            </body>
                            </html>";
                    $success = mail($to, $soggetto, $messaggio, implode("\r\n", $headers));

                break;
            }*//*
            $stmt->close();
            $conn->close();
            echo "<script>allert('email esistente')</script>";
            header("location: http://localhost:8080/Moodle/");
        }*/
    }
    else
    {
        echo "<h4 style='text-align:center'>Errore riprova</h4>";
      	header("Refresh:2.5; url= http://bog27.altervista.org/E-Learn");
    }
?>