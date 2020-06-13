<?php
    require_once ('./../Classi/Query.php');
    require_once ('./../Classi/Utente.php');
    session_start();
    $tipologia = array(
        "s" => "studenti",
        "i" => "insegnanti",
    );
    if (isset($_POST['query']) && isset($_POST['cont']) && isset($_POST['tipo']))
    {
        $db = new Query();
        $email = $db->selecUtenti($_POST['query'], $_POST['tipo']);
        if ($email !="No matches")
        {
            $Utente = new Utente($email,"a","s");
            $ris= $Utente->existId();
            if ($ris !== false)
            {
               $_SESSION['part'].="|".$ris;
            }
            echo '
            <div class="row" id="'.$_POST['tipo'].(string)($_POST['cont']).'">
                <div class="col">
                    <input disabled type="text" name="'.$_POST['tipo'].(string)($_POST['cont']).'" class="form-control '.$_POST['tipo'].'_risultato '. $tipologia[$_POST['tipo']].'" value="'.$email.'">
                </div>
                <div class="col">
                    <img width="16px" height="16px" onclick="cancella(\''.$_POST['tipo'].(string)($_POST['cont']).'\')" src="https://image.flaticon.com/icons/svg/446/446046.svg" alt="cancella">
                </div>
            </div>
            ';
        }
    }
?>