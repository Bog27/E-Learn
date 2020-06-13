# E-Learn
---
## Questo progetto è stato svolto per la Maturità
## Obiettivo
Creazione piattaforma per l'apprendimento online: questa parte di progetto riguarda la gestione dei corsi.

---
## Funzionamento
Questo progetto è stato sviluppato utilizzando delle classi scritte in php, le analizzeremo insieme ad alcuni pezzi di codice che le utilizzano:
### Database.php
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
    {
        $this->username = "";
        $this->username = "";
        $this->password = "";
        $this->dbname = "my_bog27";
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
---
## Function Points

Ecco il calcolo dei [Function Points](https://docs.google.com/spreadsheets/d/1ryx3F_NiHmsW5mLbMu_v4sScctos5u7DAN07E2CvtjU/edit?usp=sharing)
