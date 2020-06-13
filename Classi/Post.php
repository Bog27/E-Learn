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
     * Insert post in Posts
     * 
     * 
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
    
    public function insertPost2Corso($IdCorsi, $IdPost)
    {
        $sql = "INSERT INTO `corsi2post`(`IdCorso`, `IdPost`) VALUES (".$IdCorsi.",".$IdPost.");";
        $stmt = $this->connect()->query($sql);
        $this->close();
    }
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
