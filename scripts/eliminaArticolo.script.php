<?php
if(isset($_POST['elimina-articolo'])) {
    $prodotto = $_POST['elimina-articolo'];
    if (!is_numeric($prodotto)) {
        header('Location: ../carrello.php?erroreCarrello=int');
        exit();
    }
    session_start();
    $eliminato = false;
    for ($i = 0; $i < count($_SESSION['carrello']); $i = $i + 2) {
        if ($_SESSION['carrello'][$i] == $prodotto) { 
            unset($_SESSION['carrello'][$i], $_SESSION['carrello'][$i + 1]); 
            $_SESSION['carrello'] = array_values($_SESSION['carrello']);
            $eliminato = true;
            break;
        }
    }
    if (!$eliminato) {
        header('Location: ../carrello.php?eliminaArt=no');
        exit();
    }

        header('Location: ../carrello.php');
        exit();
}
else {
    header('Location: ../index.php');
    exit();
}
