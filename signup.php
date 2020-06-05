<?php 
    require "header.php";
?>
<div id="signup-container" class='animate-gradient'>
    <main id='signup-form'>
        <h1>Registrati</h1>
        <?php 
            if (isset($_GET['error'])) {
                switch ($_GET['error']) {
                    case "emptyfields": echo "<p class='error'>Compila tutti i campi!</p>"; break;
                    case "invalidmail": echo "<p class='error'>Mail non valida!</p>"; break;
                    case "passwordCheck": echo "<p class='error'>Le password non corrispondono!</p>"; break;
                    case "userTaken": echo "<p class='error'>Nome utente gia registrato!</p>"; break;
                    case "queryFailed": echo "<p class='error'>C'é stato un problema con il database, riprova!</p>"; break;
                    case "mailTaken": echo "<p class='error'>Mail gia usata!</p>"; break;
                }
            }
            else if (isset($_GET['signup'])) {
                if ($_GET['signup'] == "success") {
                    echo "<p class='success'>Ti sei registrato con successo!</p>";
                }
            }
        ?>
        <form action='./scripts/signup.script.php' method='post'>
            <input required type='text' name='uid' placeholder='Username...' maxlength="29" value='<?php if (isset($_GET['uid'])) echo $_GET['uid']; ?>'/>
            <input required type='text' name='mail' placeholder='Email...' maxlength="44" value='<?php if (isset($_GET['mail'])) echo $_GET['mail']; ?>'/>
            <input required type='text' name='nome' placeholder='Nome...' maxlength="29" value='<?php if (isset($_GET['nome'])) echo $_GET['nome']; ?>'/>
            <input required type='text' name='cognome' placeholder='Cognome...' maxlength="29" value='<?php if (isset($_GET['cognome'])) echo $_GET['cognome']; ?>'/>
            <input required type='text' name='provincia' placeholder='Provincia...' maxlength="29" value='<?php if (isset($_GET['provincia'])) echo $_GET['provincia']; ?>'/>
            <input required type='text' name='citta' placeholder='Cittá...' maxlength="29" value='<?php if (isset($_GET['citta'])) echo $_GET['citta']; ?>'/>
            <input required type='text' name='via' placeholder='Via...' maxlength="29" value='<?php if (isset($_GET['via'])) echo $_GET['via']; ?>'/>
            <div>
                <input required type='number' name='numero' placeholder='Numero...' maxlength="3" value='<?php if (isset($_GET['num'])) echo $_GET['num']; ?>'/>
                <input required type='number' name='cap' placeholder='CAP...'  value='<?php if (isset($_GET['cap'])) echo $_GET['cap']; ?>'/>
            </div>
            <input required type='password' name='pass' placeholder='Password...' />   
            <input required type='password' name='pass2' placeholder='Repeat password...' />   
            <button type='submit' name='signup-submit'>Registrati</button>
        </form>
    </main>
</div>
<?php 
    require "footer.php";
?>