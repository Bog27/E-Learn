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
            //$Email = $Email;
            //$stmt->bind_param('ss', $Email, $tipo);
            
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