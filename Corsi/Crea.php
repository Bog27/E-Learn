<?php
    session_start();
    if (!isset($_SESSION['Utente']))
    	header("url= http://bog27.altervista.org/E-Learn");
    require_once ('./../Classi/Utente.php');
    require_once ('./../Classi/Corso.php');
	//print_r($_SESSION['part']);
	$nome=trim($_POST['nome']);
    $descrizione=trim($_POST['descrizione']);
    if (isset($_SESSION['Utente']) && isset($nome) && isset($descrizione) && isset($_POST['contI']) && isset($_POST['contS']) && $_POST['contI']>=0 && $_POST['contS']>=0 && isset($_SESSION['part']))
    {
        //insegnante2@admin.it
        //ricava l'oggetto passato tramite sessione
        $Utente = Utente::parseJsonToUtente($_SESSION['Utente']);
		//print_r($Utente);
        //(Utente) $Utente->existId();
        try
        {
            $corso = new Corso($_POST['nome'], $_POST['descrizione'], $Utente);
            $idCorso = $corso ->insertCorso();
            $temp = explode('|', $_SESSION['part']);
            unset($_SESSION['part']);
            for($i=1;$i<count($temp);$i++)
            {
            	$corso->insertUtenti2Corso($temp[$i],$idCorso);
            }
            echo "<h3 style='text-align:center'>Corso creato correttamente</h3>";
            header("Refresh:2.5; url= http://bog27.altervista.org/E-Learn/Home.php");
        }
        catch (Exception $e)
        {
            echo $e;
        }
        
    }

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Learn</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>



    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./../CSS/CreateCourse.css">
</head>
<body>
    
<div class="container">
    <div class="row page">
        <div class="col-1">
        </div>
        <div class="col-10">
            <center>
                <h1>Crea un corso</h1>
            </center>
            <form action="./Crea.php" method="POST">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" class="form-control" id="nome" required>
                </div>
                <div class="form-group">
                    <label for="descrizione">Descrizione</label>
                    <textarea name="descrizione" class="form-control" id="descrizione" required></textarea>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <center>
                                <h2>Aggiungi insegnanti</h2>
                                <div class="container" id="risultatoInsegnanti">
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" class="form-control" id="insertInsegnanti">
                                        </div>
                                        <div class="col">
                                            <img id="addInsegnante" width="32" height="32" src="./../Immagini/add_icon.png" alt="add">
                                        </div>
                                    </div>
                                </div>
                            </center>
                        </div>
                        <div class="col">
                            <center>
                                <h2>Aggiungi studenti</h2>
                                <div class="container" id="risultatoStudenti">
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" class="form-control" id="insertStudenti">
                                        </div>
                                        <div class="col">
                                            <img id="addStudente" width="32" height="32" src="./../Immagini/add_icon.png" alt="add">
                                        </div>
                                    </div>  
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
                <input style="display:none;" type="text" name="contI" id="contI">
                <input style="display:none;" type="text" name="contS" id="contS">
                <center>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </center>
            </form>
        </div>
        <div class="col-1">
        </div>
    </div>
</div>
    <script>
		function del(str){
          str=str.trim();
          if (window.XMLHttpRequest){  
            xmlhttp = new XMLHttpRequest();
          }

          xmlhttp.onreadystatechange = function(){
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
                //document.getElementById("risposta").innerHTML = xmlhttp.responseText;
            }
          }
           	xmlhttp.open("GET", "test.php?el=" + str, true);
            xmlhttp.send();
          }
        /* Metodo per rimuovere l'insegnate aggiunto */
        function cancella(elemento)
        {
        	console.log($('input.s_risultato[name='+elemento+']').val());
            let a = $('input.s_risultato[name='+elemento+']').val();
            del(a);
            $('#'+elemento ).remove();
        }
		
        /* Metodo per trovare se un insegnante è già stato aggiunto */

        function trova(utente,tipo)
        {
            let total = [];
            let trovato = false;
            $('#risultato'+tipo[0].toUpperCase() + tipo.slice(1)).find('input.' + tipo).each(function(index, elem){ // Insegnanti ->>> insegnanti
                total.push(elem.value);
            });
            console.log(total);
            for (let i=0; i<total.length; i++)
            {
                if (total[i]===utente)
                {
                    trovato = true
                    break;
                }
            }
            return trovato;
        }

        /* Evento richiamato quando la pagina è caricata */
        $(document).ready(function(){
            let utenteJson = '<?php echo $_SESSION["Utente"] ?>';
            let utenteObj = JSON.parse(utenteJson); // oggetto Utente della sessione

            function richiesta(email, cont, tipo)
            {
                $.post({
                    url:"./query.php",
                    data:
                        {
                            query:email, 
                            cont:cont, 
                            tipo:tipo
                        },
                    success:
                        function(data)
                        {
                            console.log(data);
                            switch (tipo)
                            {
                                case 'i':
                                    $('#risultatoInsegnanti').append(data);
                                break;
                                case 's':
                                    $('#risultatoStudenti').append(data);
                                break;
                            }
                        }
                });

            } 
            $('#addStudente').click(function(){
                let email = $('#insertStudenti').val();
                $('#insertStudenti').val("");
                if(email != '' && email!==utenteObj.Email)
                {
                    if (!trova(email, 'studenti'))
                    {
                        let cont = $('input.studenti').length;
                        $('#contS').val(cont);
                        richiesta(email, cont, 's');
                    }
                    else
                    {
                        alert('l\'utente è già stato inserito');
                    }
                }
                else
                {
                    alert('Valore non valido');
                }
            });

            $('#addInsegnante').click(function(){
                let email = $('#insertInsegnanti').val();
                $('#insertInsegnanti').val("");
                if(email != '' && email!==utenteObj.Email)
                {
                    if (!trova(email, 'insegnanti'))
                    {
                        let cont = $('input.insegnanti').length;
                        $('#contI').val(cont);
                        richiesta(email, cont, 'i');
                    }
                    else
                    {
                        alert('l\'utente è già stato inserito');
                    }
                }
                else
                {
                    alert('Valore non valido');
                }
            });
        });
    </script>
    <!--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    --><script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>