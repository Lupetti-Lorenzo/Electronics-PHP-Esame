<?php

if(isset($_POST['carrello-submit']) || isset($_POST['acquista-submit'])) { 
    require './databasehandler.script.php';
}
else if (isset($_POST['checkout-submit'])) {
   require './scripts/databasehandler.script.php';
}

session_start();
//array con id punti vendita con tutti i prodotti disponibili
$intersezArray = [];
//controllo se i prodotti nel carrello sono disponibili
//effettuo il lock della tabella magazzino, se lo script é chiamato da acquista il lock é gia stato fatto
if (!isset($_POST['acquista-submit'])) mysqli_query($conn, 'LOCK TABLE Magazzino WRITE;');
for ($i = 0; $i < count($_SESSION['carrello']); $i = $i + 2) {
    $prod = $_SESSION['carrello'][$i];
    $quantita = $_SESSION['carrello'][$i + 1];
    $select = "SELECT IDPuntoVendita FROM Magazzino WHERE IDProdotto = $prod AND quantita > $quantita;";
    $query = mysqli_query($conn, $select);
    if(!$query) {
        die(mysqli_error($conn));
    }
    $resCheck = mysqli_num_rows($query);
    if ($resCheck <= 0) {
        resetCart($prevCarrello);
    }
    $arrPV = [];
    while ($riga = mysqli_fetch_assoc($query)) {
        array_push($arrPV, $riga['IDPuntoVendita']);
    }
    if ($i === 0) $intersezArray = $arrPV;
    else {
        $intersezArray = array_intersect($intersezArray, $arrPV);
        if (empty($intersezArray)) {
            resetCart($prevCarrello);
        }
    }
}
if (!isset($_POST['acquista-submit'])) mysqli_query($conn, 'UNLOCK TABLES;');


function resetCart($prevCar) {
    //resetto il carrello il prodotto non esiste in nessun pv in quelle quantita
    $_SESSION['carrello'] = $prevCar;
    //dipende da chi chiama lo script
    if(isset($_POST['carrello-submit']) || isset($_POST['acquista-submit'])) { 
        header('Location: ../index.php?erroreCarrello=nonDispo');
        exit();
    }
    else if (isset($_POST['checkout-submit'])) {
        header('Location: ../carrello.php?errorChek=nonDispo');
        exit();
    }
}