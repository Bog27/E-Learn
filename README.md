# E-Learn
---
## Questo progetto è stato svolto per la Maturità
## Obiettivo
Creazione piattaforma per l'apprendimento online: questa parte di progetto riguarda la gestione dei corsi.

## Funzionamento
Questo progetto è stato sviluppato utilizzando delle classi scritte in php, le analizzeremo insieme ad alcuni pezzi di codice che le utilizzano:
### Database.php
Questa è una classe astratta, ovvero una classe che può essere ereditata da altre classi e ha come scopo quello di collegarsi al db. 
```php
<?php
/**
 * Classe che gestisce il db
 * @abstract
 */
abstract class Database 
{
    /**
     * @var string nome dell'host del DBMS
     * @access private
     */
    private $nomehost;
    /**
     * @var string nome utente per l'accesso al DBMS
     * @access private
     */
    private $nomeuser;
    /**
     * @var string password per l'accesso al DBMS
     * @access private
     */
    private $password;
    /**
     * @var string nome del db
     * @access private
     */
    private $dbname;

    /**
     * @var object connessione
     * @access protected
     */
    protected $conn;

    /**
     * Costruttore
     * 
     * @access public
     */
    public function __construct()
    { 	//Credenziali da inserire
        $this->username = "";
        $this->username = "";
        $this->password = "";
        $this->dbname = "";
    }
    /**
     * Metodo per la connesione al DB
     * 
     * @access protected
     * @return object $conn Connessione al db
     * @throws Exception connessione
     */
    protected function connect()
    {
        $this->conn = new mysqli($this->nomehost, $this->nomeuser, $this->password, $this->dbname);
        
        if ($this->conn->connect_error) 
        {
            throw new Exception($this->conn->connect_error);
        }
	$this->conn->set_charset("utf8");
        return $this->conn;
    }

    /**
     * Metodo per la chiusura della connessione
     * 
     * @access protected
     */
    protected function close()
    {
        $this->conn->close();
    }
}
?>
```

### Utente.php
La classe utente contiene le funzioni per l'inserimento dell'utente con i controlli  e la composizione della mail.
Implementa anche un paio di metodi per visualizzare i corsi a cui partecipa e i corsi creati. 
```php
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
     *@access public
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
```

### Query.php
Questa classe viene utilizzata per effettuare query tramite Ajax.
```php
<?php
require_once ('Database.php');

/**
 * Classe per effettuare query tramite Ajax
 */
class Query extends Database
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * fa una select sulla tabella Utenti da inserire nel corso
     * @param string $Email email dell'utente da cercare
     * @param char $tipo tipo dell'utente da cercare
     * 
     * @return string email dell'utente trovato
     * @return string No matches se l'utente non è stato trovato
     * 
     * @access public
     */
    public function selecUtenti($Email, $tipo)
    {
        if (preg_match('/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/i', $Email, $ritorno))
        {
            $Email = $ritorno[0];
            /*print_r($Email);
            print_r($tipo);*/
            $sql = "SELECT Email
                    FROM utenti
                    WHERE
                        Email = '$Email' And Tipologia = '$tipo'";
            $result = $this->connect()->query($sql);
            //$stmt->bind_param('ss', $Email, $tipo)
            //$stmt->execute();
            //$stmt->bind_result($Email);
            $risposta ="No matches";
            $numrows = $result->num_rows;
            if ($numrows > 0)
            {
                while ($row = $result->fetch_assoc())
                {
                    $risposta = $row['Email'];
                }
                $this->close();
            }
            //print_r($stmt);
            return $risposta;
        }
        else 
        {
            return "No matches";
        }
    }
}  
?>
```
Nello specifico quando creiamo un corso e aggiungiamo uno studente o un insegnate viene istanziato un oggetto di questa classe.
File : /Corsi/query.php
```php
$db = new Query();
$email = $db->selecUtenti($_POST['query'], $_POST['tipo']);
if ($email !="No matches")
{
  ...
}
```

### Corso.php
Questa classe permette la gestione dei corsi e il loro inserimento nel database.
```php
<?php
require_once ('Database.php');
require_once ('Utente.php');
/**
 * Classe per la gestione dei corsi
 */
class Corso extends Database
{
    /**
     * @var int $Id identificativo del corso
     * @access private
     */
    private $Id;
    /**
     * @var string $Nome nome del corso
     * @access private
     */
    private $Nome;
    /**
     * @var string $Descrizione Descrizione del corso
     * @access private
     */
    private $Descrizione;
    /**
     * @var int $Creatore id dell'utente che ha creato il corso
     * @access private
     */
    private $Creatore;
    /**
     * Costruttore
     * 
     * @throws Valori non validi
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        if (func_num_args() == 3)
        {
            $this->Nome = func_get_arg(0);
            $this->Descrizione =func_get_arg(1);
            $this->checkCreatore(func_get_arg(2));
        }   
    }
    /**
     * Controllo SQL Injection del nome
     * 
     * @param Utente $Utente
     * @return int $IdCreatore  ----> corretto
     * @return boolean false    ----> errato
     * @access public
     */
    public function checkCreatore($Utente)
    {
        $Utente = new Utente($Utente->Email,$Utente->Password,$Utente->Tipologia);
        $ris= $Utente->existId();
        if ($ris === false)
        {
            return false;
        }
        else
        {
            $this->Creatore = $ris;
            return true;
        }
    }
    /**
     * Select corsi
     * 
     * @param int $Id
     * @return array $data[] trovato
     * @return boolean false non trovato
     * @access public
     */
    public function selectCorso($Id)
    {
        $sql="SELECT * FROM corsi WHERE Id='". $Id ."';";
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
            return false;
        }
    }
    /**
     * Insert corso in Corso
     *
     * @return int $last_id id del corso inserito 
     * @access public
     */
    public function insertCorso()
    {
        $sql = "INSERT INTO corsi(Nome, Descrizione, Creatore) VALUES ('".$this->Nome."','".$this->Descrizione."','".$this->Creatore."');";

        $stmt = $this->connect();
        $co = $stmt->query($sql);
        //$stmt->bind_param('ssi', $this->Nome, $this->Descrizione,$this->Creatore);
	$last_id = mysqli_insert_id($stmt);
        $this->close();
        return $last_id;
    }
    
    /**
     * Insert Utenti nei corsi
     *
     * @param int $IdUtenti id dell'utente da inserire
     * @param int $IdCorsi id del corso in cui inserire l'utente
     * @access public
     */
    public function insertUtenti2Corso($IdUtenti, $IdCorsi)
    {
        $sql = "INSERT INTO utenti2corsi(IdUtenti, IdCorsi) VALUES ('".$IdUtenti."','".$IdCorsi."');";
        $stmt = $this->connect()->query($sql);
        $this->close();
    }
}
?>
```

### Post.php
Questa classe permette la gestione dei post e il loro inserimento nel database. 
```php
<?php
require_once ('Database.php');
require_once ('Utente.php');
/**
 * Classe per la gestione dei Post
 */
class Post extends Database
{
    /**
     * @var int $Id identificativo del post
     * @access private
     */
    private $Id;
    /**
     * @var string $Titolo titolo del post
     * @access private
     */
    private $Titolo;
    /**
     * @var string $Corpo corpo del post
     * @access private
     */
    private $Corpo;
    /**
     * @var string $nomeFile corpo del post
     * @access private
     */
    private $nomeFile;
    
    public function __construct()
    {
        parent::__construct();
        if (func_num_args() == 3)
        {
            $this->Titolo = func_get_arg(0);
            $this->Corpo =func_get_arg(1);
            $this->nomeFile =func_get_arg(2);
        }   
    }
    
    /**
     * Insert post in posts
     *
     * @return int $last_id id del corso inserito 
     * @access public
     */
    public function insertPost()
    {
        $sql = "INSERT INTO posts (Titolo, Corpo, nomeFile) VALUES ('".$this->Titolo."','".$this->Corpo."','".$this->nomeFile."');";

        $stmt = $this->connect();
        $co = $stmt->query($sql);
		$last_id = mysqli_insert_id($stmt);
        $this->close();
        return $last_id;
    }
    
    /**
     * Insert post associati ai corsi
     *
     * @param $IdCorsi id del corso a cui è associato il post
     * @param $IdPost id del post inserito nel corso
     * @access public
     */
    public function insertPost2Corso($IdCorsi, $IdPost)
    {
        $sql = "INSERT INTO `corsi2post`(`IdCorso`, `IdPost`) VALUES (".$IdCorsi.",".$IdPost.");";
        $stmt = $this->connect()->query($sql);
        $this->close();
    }
    /**
     * Select Post 
     *
     * @param $Id id del post
     * @return array $data informazioni dei post
     * @return boolean false Nessun post trovato
     * @access public
     */
    public function selectPost($Id)
    {
        $sql="SELECT * FROM posts WHERE Id=". $Id .";";
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
            return false;
        }
    }
    /**
     * Select Post in base al corso 
     *
     * @param $IdCorso id del corso
     * @return array $data id dei post
     * @return boolean false Nessun post trovato
     * @access public
     */
    public function selectIdPost($IdCorso)
    {
        $sql="SELECT * FROM `corsi2post` WHERE `IdCorso` = ". $IdCorso .";";
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
            return false;
        }
    }
}
?>
```

---
## Function Points

Ecco il calcolo dei [Function Points](https://docs.google.com/spreadsheets/d/1ryx3F_NiHmsW5mLbMu_v4sScctos5u7DAN07E2CvtjU/edit?usp=sharing)
