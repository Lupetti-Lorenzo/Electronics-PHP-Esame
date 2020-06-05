<?php
if (isset($_POST['login-submit'])) { 
    require './databasehandler.script.php';
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $password = mysqli_real_escape_string($conn, $_POST['pass']);

    if (empty($uid) || empty($password)) {
        header('Location: ../index.php?errorLogin=emptyfields');
        exit();
    }
    else {
        $selectmail = "SELECT * FROM Cliente WHERE email='$uid';";
        if ($querymail = mysqli_query($conn, $selectmail)) {
            if ($row = mysqli_fetch_assoc($querymail)) {
                $verifyPassword = password_verify($password, $row['pwd']);
                if ($verifyPassword == true) {
                    session_start();
                    $_SESSION['userId'] = $row['ID'];
                    $_SESSION['userUid'] = $row['username'];
                    $_SESSION['mail'] = $row['email'];
                    $_SESSION['nome'] = $row['nome'];
                    $_SESSION['cognome'] = $row['cognome'];
                    $_SESSION['via'] = $row['via'];
                    $_SESSION['numero'] = $row['numero'];
                    $_SESSION['cap'] = $row['cap'];
                    $_SESSION['citta'] = $row['citta'];
                    $_SESSION['provincia'] = $row['provincia'];
                    //quello che voglio sapere nelle altre pagine dello user
                    header('Location: ../index.php?login=success');
                    exit();
                }
                else {
                    header("Location: ../index.php?errorLogin=wrongPassword");  
                    exit();
                }
            }
            else {
                header('Location: ../index.php?errorLogin=mailNotFound');
                exit();
            }
        }
        else {
            header('Location: ../index.php?errorLogin=queryError');
            exit();
        }
        mysqli_close($conn);
    }
    
}
else {
    header('Location: ../index.php');
    exit();
}