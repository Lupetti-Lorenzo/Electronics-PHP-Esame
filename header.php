<?php 
    session_start();
    $carrelloCount = 0;
    if (isset($_SESSION["carrello"])) {
        for ($i = 1; $i < count($_SESSION['carrello']); $i = $i + 2) {
            $carrelloCount += $_SESSION['carrello'][$i];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronics</title>
    <link rel="stylesheet" type="text/css" href="./style/style.css">
    <script src="https://kit.fontawesome.com/3abd6eb251.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <div id='nav-links'>
            <img id='logo' src="./assets/tech.svg" alt="logo">
            <h1>Electronics</h1>
            <h2><a href='./index.php'>Home</a></h2>
        </div>
        <div id='nav-forms'>
        <?php 
            //messaggi errore carrello
            if (isset($_GET['erroreCarrello'])) {
                switch ($_GET['erroreCarrello']) {
                    case 'nonDispo' : $errorMessage = 'Quantita non disponibili!'; break;
                    case 'quantita' : $errorMessage = 'Inserisci una quantita valida!'; break;
                    case 'int' : $errorMessage = 'Errore, stai modificando i valori tramite ispeziona! Prova con il DDos'; break;
                }
                echo "<p class='error'>$errorMessage</p>";
            }
            else if (isset($_GET['successoCarrello'])) {
                echo '<p class=success>Prodotto aggiunto con successo!</p>';
            }
            //messaggio successo ordine
            if (isset($_GET['acquisto'])) {
                echo '<p class=success>Ordine confermato con successo!</p>';
            }
            //logout bottone
            if (isset($_SESSION['userId'])) {
                echo "
                    <form action='./userPage.php' method='post'>
                        <button id='btn-user' type='submit' name='user-submit'><i class='fas fa-user'></i></button>
                    </form>
                    <form action='./scripts/logout.script.php' method='post'>
                        <button type='submit' name='logout-submit'>Logout</button>
                    </form>
                ";
            }
            //errori login
            else {
                $errorMessage;
                if (isset($_GET['errorLogin'])) {
                    switch ($_GET['errorLogin']) {
                        case 'emptyfields' : $errorMessage = 'Compila i campi!'; break;
                        case 'wrongPassword' : $errorMessage = 'Password errata!'; break;
                        case 'mailNotFound' : $errorMessage = 'Mail non trovata!'; break;
                        case 'queryError' : $errorMessage = 'Errore nel database, riprova!'; break;                 
                    }
                    echo "<p class='error'>$errorMessage</p>";
                }
                //form login e registrati
                echo "            
                    <form action='./scripts/login.script.php' method='post'>
                        <input class='input' type='text' name='uid' placeholder='Email...'  maxlength='44' required/>
                        <input class='input' type='password' name='pass' placeholder='Password...' maxlength='29' required/>       
                        <button type='submit' name='login-submit'>Accedi</button>
                    </form>
                    <a href='signup.php'><button id='registrati'>Registrati</button></a>
                ";
            }
        ?>
        <a href='./carrello.php' id='cart'>
            <i class="fas fa-shopping-cart"></i> 
            <span id='cart-count'>
                <?php   echo $carrelloCount; ?>
            </span>
        </a>
        </div>
    </nav>