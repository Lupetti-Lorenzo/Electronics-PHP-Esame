<?php
if (isset($_POST['signup-submit'])) {
    require './databasehandler.script.php';

    $username = mysqli_real_escape_string($conn, $_POST['uid']);
    $email = mysqli_real_escape_string($conn, $_POST['mail']);
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $cognome = mysqli_real_escape_string($conn, $_POST['cognome']);
    $provincia = mysqli_real_escape_string($conn, $_POST['provincia']);
    $citta = mysqli_real_escape_string($conn, $_POST['citta']);
    $via = mysqli_real_escape_string($conn, $_POST['via']);
    $num = mysqli_real_escape_string($conn, $_POST['numero']);
    $cap = mysqli_real_escape_string($conn, $_POST['cap']);
    $password = mysqli_real_escape_string($conn, $_POST['pass']);
    $password2 = mysqli_real_escape_string($conn, $_POST['pass2']);
    
    if (empty($username) || empty($email) || empty($password) || empty($password2) || empty($nome) || empty($cognome) || empty($via) || empty($citta) || empty($provincia) || empty($num) || empty($cap)) {
        header('Location: ../signup.php?error=emptyfields&mail='.$email.'&uid='.$username.'&nome='.$nome.'&cognome='.$cognome.'&citta='.$citta.'&via='.$via.'&num='.$num.'&cap='.$cap.'&provincia='.$provincia);
        exit();
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../signup.php?error=invalidmail&uid='.$username.'&nome='.$nome.'&cognome='.$cognome.'&citta='.$citta.'&via='.$via.'&num='.$num.'&cap='.$cap);
        exit(); 
    }
    else if ($password !== $password2) {
        header('Location: ../signup.php?error=passwordCheck&mail='.$email.'&uid='.$username.'&nome='.$nome.'&cognome='.$cognome.'&citta='.$citta.'&via='.$via.'&num='.$num.'&cap='.$cap.'&provincia='.$provincia);
        exit(); 
    }
    else {
        $selectuser = "SELECT ID FROM Cliente WHERE username='$username';";
        $selectmail = "SELECT ID FROM Cliente WHERE email='$email';";
        //controllo se email cé gia
        if($query = mysqli_query($conn, $selectmail)) {
            $resCheck = mysqli_num_rows($query);
            if ($resCheck > 0) {
                header('Location: ../signup.php?error=mailTaken'.'&uid='.$username.'&nome='.$nome.'&cognome='.$cognome.'&citta='.$citta.'&via='.$via.'&num='.$num.'&cap='.$cap.'&provincia='.$provincia);
                exit(); 
            }
        }
        else {
            header('Location: ../signup.php?error=queryFailed'."&mail=".$email.'&uid='.$username.'&nome='.$nome.'&cognome='.$cognome.'&citta='.$citta.'&via='.$via.'&num='.$num.'&cap='.$cap.'&provincia='.$provincia);
            exit(); 
        }   
        //controllo se user cé gia
        if($query = mysqli_query($conn, $selectuser)) {
            $resCheck = mysqli_num_rows($query);
            if ($resCheck > 0) {
                header('Location: ../signup.php?error=userTaken'."&mail=".$email.'&nome='.$nome.'&cognome='.$cognome.'&citta='.$citta.'&via='.$via.'&num='.$num.'&cap='.$cap.'&provincia='.$provincia);
                exit(); 
            }   
            else {
                $passhash = password_hash($password, PASSWORD_DEFAULT);
                $insert = "INSERT INTO Cliente VALUES (0, '$username', '$email','$nome' ,'$cognome' , '$provincia', '$citta' ,'$via' ,'$num' ,'$cap' , '$passhash');";
                if (mysqli_query($conn, $insert)) {
                    header('Location: ../signup.php?signup=success');
                    exit(); 
                }
                else {
                    header('Location: ../signup.php?error=queryFailed'."&mail=".$email.'&uid='.$username.'&nome='.$nome.'&cognome='.$cognome.'&citta='.$citta.'&via='.$via.'&num='.$num.'&cap='.$cap.'&provincia='.$provincia);
                    exit(); 
                }
            }
        }
        else {
            header('Location: ../signup.php?error=queryFailed'."&mail=".$email.'&uid='.$username.'&nome='.$nome.'&cognome='.$cognome.'&citta='.$citta.'&via='.$via.'&num='.$num.'&cap='.$cap.'&provincia='.$provincia);
            exit(); 
        }    
        mysqli_close($conn);        
    } 
}
else {
    header('Location: ../signup.php');
    exit();
}
