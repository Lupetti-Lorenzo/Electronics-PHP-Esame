<?php
session_start();
if (isset($_POST['acquista-submit']) && isset($_SESSION['userUid']) && isset($_POST['tipoSpedizione']) && isset($_POST['pv'])) {
    require './databasehandler.script.php';
    session_start();
    if (!mysqli_query($conn, "START TRANSACTION;")) die(mysqli_error($conn));
    
    //aggiorno quantita nel magazzino e lock table per essere sicuro che nessuno stia modificando la quantita
    mysqli_query($conn, 'LOCK TABLE Magazzino WRITE;');
    //salvo una copia del carrello
    $prevCarrello =  $_SESSION['carrello'];
    //controllo se il carrello è valido
    require './checkCarrello.script.php';
    $puntoV = "1";
    for ($i = 0; $i < count($_SESSION['carrello']); $i = $i + 2) {
        $prod = $_SESSION['carrello'][$i];
        $quantita = $_SESSION['carrello'][$i + 1];
        $update = "UPDATE Magazzino SET quantita=((SELECT quantita WHERE IDProdotto=$prod AND IDPuntoVendita=$puntoV)-$quantita) WHERE IDProdotto=$prod AND IDPuntoVendita=$puntoV;";
        if (!mysqli_query($conn, $update)) {
            mysqli_query($conn, "UNLOCK TABLES;ROLLBACK;");
            header('Location: ../carrello.php?errorCheck=queryError');
            exit();
        }
    }
    mysqli_query($conn, 'UNLOCK TABLES;');

    $data = date("Y")."-".date("m")."-".date("d");
    $insert = "INSERT INTO Ordine VALUES (0, '$data', ".$_SESSION['userId'].", "; 
    if ($_POST['tipoSpedizione'] == "r") {
        //se ha selezionato ritiro 
        $puntoV = mysqli_real_escape_string($conn, $_POST['pv']);
        $insert .= "TRUE, "."$puntoV".");";
    }
    else if ($_POST['tipoSpedizione'] == "s") {
        //se ha selezionato spedizione  
        $insert .= "FALSE,".$puntoV.");";
    }
     
    if (mysqli_query($conn, $insert)) { 
        $id = mysqli_insert_id($conn);
        for ($i = 0; $i < count($_SESSION['carrello']); $i = $i + 2) {
            $prod = $_SESSION['carrello'][$i];
            $quantita = $_SESSION['carrello'][$i + 1];
            //aggiungo alla tabella DettagliOrdine i prodotti 
            $insert = "INSERT INTO DettagliOrdine VALUES ($prod, $id, $quantita)";
            if (!mysqli_query($conn, $insert)) {
                mysqli_query($conn, "ROLLBACK;");
                header('Location: ../carrello.php?errorCheck=queryError');
                exit();
            }
        }
    }
    else {
        mysqli_query($conn, "ROLLBACK;");
        header('Location: ../carrello.php?errorCheck=queryError');
        exit();
    }
    //é tutto andato bene, pulisco il carrello e mando alla pagina di index
    unset($_SESSION['carrello']);
    if (!mysqli_query($conn, "COMMIT;")) die(mysqli_error($conn));
    mysqli_close($conn);
    header('Location: ../index.php?acquisto=success');
    exit();
}
else {
    header('Location: ../carrello.php');
    exit();
}