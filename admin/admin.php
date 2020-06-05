<?php 
session_start();
require './dbhAdmin.script.php';
if (!isset($_SESSION['adminPV'])) {
    header('Location: ./adminLogin.php');
    exit();
}
$pv = $_SESSION['adminPV'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="./adminStyle/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina admin</title>
</head>
<body>
    <form action='../scripts/logout.script.php' method='post'>
        <button type='submit' name='logout-submit'>Logout</button>
    </form>
    <h1>Pagina admin del punto vendita della regione 

    <?php
        //dati del punto vendita
        $select = "SELECT * FROM PuntoVendita WHERE ID = $pv";
        $query = mysqli_query($conn, $select);
        $datipv = mysqli_fetch_assoc($query);
        if (!$query || !$datipv) die(mysqli_error($conn));
        echo $datipv['regione'];
        echo " con sede a ".$datipv['citta'];
    ?>
    </h1>
    <h2>Ordini ricevuti</h2>
    <div id='ordini'>
        <?php 
            //dati degli ordini
            $selectOrdini = "SELECT * FROM Ordine WHERE IDPuntoVendita = $pv";
            $queryOrdini = mysqli_query($conn, $selectOrdini);
            while ($datiOrdini = mysqli_fetch_assoc($queryOrdini)) {
                echo "<div class='ordine'>
                    <h3>Ordine N ".$datiOrdini['ID']."</h3>
                ";
                
                //dati dell utente
                $selectUser = "SELECT * FROM Cliente WHERE ID = ".$datiOrdini['IDCliente'].";";
                $queryUser = mysqli_query($conn, $selectUser);
                $datiUtente = mysqli_fetch_assoc($queryUser);
                if (!$query || !$datiUtente) die(mysqli_error($conn));
                echo "
                    <div class='ordine-cliente'>
                        <h3>Cliente</h3>
                        <p>Nome: ".$datiUtente['nome']."</p>
                        <p>Cognome: ".$datiUtente['cognome']."</p>
                        <p>Email: ".$datiUtente['email']."</p>";
                if ($datiOrdini['RitiroInNegozio'] == true) {
                    echo "<p>Il cliente ritirera in negozio i prodotti nei prossimi giorni</p>";
                }
                else {
                    if ($pv == 1) {
                        echo "<p>I prodotti devono essere spedidi all'indirizzo: </p>";
                        echo "<p>via ".$datiUtente['via']." ".$datiUtente['numero']."<br> ".$datiUtente['provincia']." ".$datiUtente['citta']." ".$datiUtente['cap'];
                    }
                }
                echo  "</div>";
                
                //dati dei prodotti
                $selectProds = "SELECT * FROM Prodotto WHERE ID IN (SELECT IDProdotto FROM DettagliOrdine WHERE IDOrdine = ".$datiOrdini['ID'].");";
                $prodotti = mysqli_query($conn, $selectProds);
                if(!$prodotti)  die(mysqli_error($conn));
                echo "<div class='ordine-prodotti'> <h3>Prodotti:</h3>";
                while($riga = mysqli_fetch_assoc($prodotti))
                {   //<img src='".$riga['immagine']."'alt='no img'/>
                    //seleziono la quantita acquistata del prodotto
                    $selectqnt = "SELECT quantita FROM DettagliOrdine WHERE IDOrdine=".$datiOrdini['ID']." AND IDProdotto =".$riga['ID'].";";
                    $queryqnt = mysqli_query($conn, $selectqnt);
                    if(!$queryqnt)  die(mysqli_error($conn));
                    $quantita = mysqli_fetch_assoc($queryqnt);
                    echo "
                            <div class='ordine-prodotto'>    
                                <div class='product-name'>
                                        <p>".$riga['nome']."</p>
                                        <p> -- $".$riga['prezzo']."in quantita:  ".$quantita['quantita']."</p>
                                        <p> Categoria: ".$riga['categoria']."</p>
                                </div> 
                            </div>
                    ";
                }
                echo "</div>";
                echo "</div>";
            }
            echo " </div>";
        ?>
    </div>
</body>
</html>