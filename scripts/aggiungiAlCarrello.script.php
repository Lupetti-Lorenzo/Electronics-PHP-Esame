<?php
if(isset($_POST['carrello-submit'])) {
    require './databasehandler.script.php';
    session_start();
    if(isset($_POST['quantita']) && $_POST['quantita'] > 0) {
        $prodotto = $_POST["prodotto"];
        $quantita =  $_POST['quantita'];
        if (!is_numeric($prodotto) || !is_numeric($quantita)) {
            header('Location: ../index.php?erroreCarrello=int');
            exit();
        }
        //se il carrello non esiste lo inizializzo
        if (!isset($_SESSION['carrello'])) {
            $_SESSION['carrello'] = [];
        } 
        //aumento $quantita nel caso sia gia presente nel carrello
        else {
            for ($i = 0; $i < count($_SESSION['carrello']); $i = $i + 2) {
                if ($_SESSION['carrello'][$i] == $prodotto) {
                    $quantita += $_SESSION['carrello'][$i + 1];
                    break;
                }
            }
        }
        //salvo una copia del carrello precedente
        $prevCarrello =  $_SESSION['carrello'];
        //aggiorno il carrello
        $ceGia = false;
        for ($i = 0; $i < count($_SESSION['carrello']); $i = $i + 2) {
            if ($_SESSION['carrello'][$i] == $prodotto) {
                $ceGia = true;
                $_SESSION['carrello'][$i + 1] = $quantita;
                break;
            }
        }
        if (!$ceGia) {
            array_push($_SESSION['carrello'], $prodotto);
            array_push($_SESSION['carrello'], $quantita);
            mysqli_close($conn);
        }
        //controllo se il carrello è valido
        require './checkCarrello.script.php';
        header('Location: ../index.php?successoCarrello=aggiunto');
        exit();
    }    
    else {
        header('Location: ../index.php?erroreCarrello=quantita');
        exit();
    }
    mysqli_close($conn);
} 
else {
    header('Location: ../index.php');
    exit();
}
