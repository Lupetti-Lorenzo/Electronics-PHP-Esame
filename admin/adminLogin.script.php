<?php
if (isset($_POST['login-admin-submit'])) { 
session_start();
require './dbhAdmin.script.php';
$uid = mysqli_real_escape_string($conn, $_POST['user']);
$password =  $_POST['pass'];
if (empty($uid) || empty($password)) {
    header('Location: ./adminLogin.php?errorLogin=emptyFields');
    exit();
}
else {
    $select = "SELECT * FROM AdminProfile WHERE username='$uid';";
    if ($query = mysqli_query($conn, $select)) {
        if ($row = mysqli_fetch_assoc($query)) {
            $verifyPassword = password_verify($password, $row['pwd']);
            if ($verifyPassword == true) {         
                session_unset();
                $_SESSION['adminPV'] = $row['IDPuntoVendita'];
                header('Location: ./admin.php');
                exit();
            }
            else {
                header("Location: ./adminLogin.php?errorLogin=wrongPassword");  
                exit();
            }
        }
        else {
            header('Location: ./adminLogin.php?errorLogin=userNotFound');
            exit();
        }
    }
    else {
        header('Location: ./adminLogin.php?errorLogin=queryError');
        exit();
    }
    mysqli_close($conn);
}
}
else {
header('Location: ./adminLogin.php');
exit();
}