<?php require './adminheader.php'; ?>
<body id='admin-login'>
<h1>Login admin</h1>
<?php 
    if (isset($_GET['errorLogin'])) {
        switch ($_GET['errorLogin']) {
            case "emptyfields": echo "<p class='error'>Compila tutti i campi!</p>"; break;
            case "queryError": echo "<p class='error'>C'é stato un problema con il database, riprova!</p>"; break;
            case 'wrongPassword': echo "<p class='error'>Password errata!</p>"; break;
            case 'userNotFound': echo "<p class='error'>User non trovato!</p>"; break;
        }
    }
?>
<form action='./adminLogin.script.php' method='post'>
    <input class='input' type='text' name='user' placeholder='Username...'  maxlength='29' required/>
    <input class='input' type='password' name='pass' placeholder='Password...' maxlength='29' required/>       
    <button type='submit' name='login-admin-submit'>Accedi</button>
</form>
</body>
</html>