<?php
require_once ('Database.php');

/**
 * Classe per la gestione degli utenti
 *  
 */
class Utente extends Database
{
    /**
     * @var int $Id identificativo dell'utente
     * @access private
     */
    private $Id;
    /**
     * @var string $Email indirizzo di posta elettronica dell'utente
     * @access private
     */
    private $Email;
    /**
     * @var string $Password password dell'utente
     * @access private
     */
    private $Password;
    /**
     * @var int $Tipologia tipologia di utente (s, i)
     * @access private
     */
    private $Tipologia;
    /**
     * @var string $Icona immagine di profilo
     * @access private
     */
    private $Icona;

    /**
     * Costruttore
     * 
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        if (func_num_args() == 3)
        {
            try{
                $this->Email = $this->checkEmail(func_get_arg(0))[0];
            
                $this->Password = func_get_arg(1);
                
                $this->Tipologia = func_get_arg(2);

                $this->Icona = "Standard.png";
            }
            catch (Exception $e)
            {
                throw new Exception($e);
            }
            

        }

    }
    /**
     * 
     * @return int $Id
     */
    public function getId()
    {
        return $this->Id;
    }
    /**
     * 
     * @return string $Email
     */
    public function getEmail()
    {
        return $this->Email;
    }
    /**
     * 
     * @return string $Password
     */
    public function getPassword()
    {
        return $this->Password;
    }
    /**
     * 
     * @return char $Tipologia
     */
    public function getTipologia()
    {
        return $this->Tipologia;
    }
    /**
     * 
     * @return string $Icona
     */
    public function getIcona()
    {
        return $this->Icona;
    }
    /**
     * Metodo per il controllo dell'Email
     * 
     * @param string $Email
     * @return string $ritorno
     * @throws Exception Email non conforme
     */
    public function checkEmail($Email)
    {   
        if (preg_match('/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/i', $Email, $ritorno))
        {
            return $ritorno;
        }
        else
        {
            throw new Exception("Il valore non è un'email");
        }
    }
    /**
     * Metodo per calcolare l'hash della password
     * 
     * @param string $Password
     * @return string $hash
     */
    public function hashPassword($Password)
    {
        return password_hash($Password, PASSWORD_BCRYPT);
    }
    /** 
     * Metodo per controllare la tipologia
     * 
     * @param char $Password
     * @return boolean true  ----> corretto
     * @return boolean false ----> errato
     * @access public
     */
    public function checkTipologia($Tipologia)
    {
        if ($Tipologia === 's' || $Tipologia === 'i')
            return true;
        else
            throw new Exception("Il valore non è accettabile");
    }
    /** 
     * Metodo per l'inserimento di un utente
     * 
     * @param int $Id
     * @return int id se sesiste
     * @return boolean false se non esiste
     * @access public
     */
    public function existId()
    {
        $ris = $this->selectUtenti();
        
        if ($ris === 0)
        {
            return false;
        }
        else
        {
            return $ris[0]['Id'];
        }
    }
    
    /** 
     * Metodo per l'inserimento di un utente
     * 
     * @return array $data[] dati estratti dal DB 
     * @return int 0 nessun utente trovato
     * @access private
     */
    public function selectUtenti()
    {
        $sql="SELECT * FROM utenti WHERE Email='". $this->Email ."'";
        $result = $this->connect()->query($sql);
        $numrows = $result->num_rows;
        if ($numrows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                $data[] = $row;
            }
            $this->close();
            return $data;
        }
        else
        {
            $this->close();
            return 0;
        }
    }

    /** 
     * Metodo per inviare l'email di conferma dell'esistenza dell'account di posta
     * 
     * @param string $messaggio messaggio da inviare per email
     * @return boolean true mail inviata
     * @return boolean false errore
     * @access private
     */
    private function sendMail($messaggio)
    {
        $soggetto = "CONFERMA E-MAIL E-LEARN";
        // HEADER set (obbligatori)
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        // HEADER addizionali
        $headers[] = 'From: staff@e-learn.it';
        // Messaggio da inviare
        return mail($this->Email, $soggetto, $messaggio, implode("\r\n", $headers));
    }

    /** 
     * Metodo per registrarsi all'area privata
     * 
     * @return boolean true email inviata
     * @return boolean false errore invio email
     * @return int 0 Email già in uso
     * @return int -1 Email non settata
     */
    public function checkEmailRegistrazione()
    {
        $data = $this->selectUtenti();
        if ($data === 0)
        {
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
                    <a class='bottone' href='http://bog27.altervista.org/E-Learn/Autenticazione/Crea.php'> Conferma </a><br><br><br>
                </body>
                </html>";
            return $this->sendMail($messaggio);
        }
        else if ($data === -1)
        {
            return $data;
        }
        else
        {
            return 0;
        }
    }

    /** 
     * Metodo per registrarsi nel sito
     * 
     *@access pubic
     */
    public function insertUtenti()
    {
        $sql = "INSERT INTO utenti(Email, Password, Tipologia, Icona) 
        VALUES ('".$this->Email."','".password_hash($this->Password, PASSWORD_BCRYPT)."','".$this->Tipologia."','Standard.png');";

        $this->connect()->query($sql);
        //$stmt->bind_param('sss', $this->Email, password_hash($this->Password, PASSWORD_BCRYPT), $this->Tipologia);
        
        $this->close();
    }

    /** 
     * Metodo per accedere all'area privata
     * 
     * @return boolean true password giusta
     * @return boolean false password errata
     * @return int 0 nessun utente trovato
     * @return int -1 Email non settata
     */
    public function accedi()
    {
        $data = $this->selectUtenti();
        if ($data !== 0 && $data !== -1)
        {
            if (password_verify($this->Password, $data[0]['Password']))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            //return false;
            return $data;
        }

    }

    /** 
     * Metodo per convertire l'oggetto in json
     * 
     * @return string json dell'oggetto
     */
    public function parseUtenteToJson()
    {
        return json_encode(get_object_vars($this));
    }
    
    /** 
     * Metodo per convertire json in Utente
     * 
     * @return Utente json dell'oggetto
     */
    public static function parseJsonToUtente($Json)
    {
        return json_decode($Json);
    }
    /** 
     * Metodo selezionare i Corsi in cui l'utente è presente
     * 
     * @return array $data corsi selezionati
     * @return int 0 Nessun corso trovato
     */
    public function selectCorsi()
    {
        $sql="SELECT IdCorsi FROM utenti2corsi WHERE IdUtenti=". intval($this->existId()) .";";
        $result = $this->connect()->query($sql);
        $numrows = $result->num_rows;
        if ($numrows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                $data[] = $row['IdCorsi'];
            }
            $this->close();
            return $data;
        }
        else
        {
            $this->close();
            return 0;
        }
       
    }
    /** 
     * Metodo selezionare i Corsi che l'utente ha creato
     * 
     * @return array $data corsi selezionati
     * @return int 0 Nessun corso trovato
     */
     public function selectCorsiCreati()
     {
     	$sql="SELECT Id FROM corsi WHERE Creatore=". intval($this->existId()) .";";
        $result = $this->connect()->query($sql);
        $numrows = $result->num_rows;
        if ($numrows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                $data[] = $row['Id'];
            }
            $this->close();
            return $data;
        }
        else
        {
            $this->close();
            return 0;
        }
     }
}

?>