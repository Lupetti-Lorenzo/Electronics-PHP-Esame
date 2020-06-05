<?php 
    session_start();
    require './scripts/databasehandler.script.php';
    $pvDisponibili;
    if (!isset($_POST['checkout-submit']) || !isset($_SESSION['userUid'])) {
        header('Location: ../carrello.php');
        exit();
    }
    else {
        //salvo una copia del carrello precedente
        $prevCarrello =  $_SESSION['carrello'];
        //aggiorno il carrello
        for ($i = 1; $i < count($_SESSION['carrello']); $i = $i + 2) {
            $_SESSION['carrello'][$i] = $_POST['quantita'][($i - 1) / 2];
        }
        //controllo se il carrello è valido
        require './scripts/checkCarrello.script.php';
        //salvo i punti vendita disponibili
        $pvDisponibili = $intersezArray;
        //calcolo il totale
        $totale = 0;
        for ($i = 0; $i < count($_SESSION['carrello']); $i = $i + 2) {
            $prod = $_SESSION['carrello'][$i];
            $quantita = $_SESSION['carrello'][$i + 1];
            $select = "SELECT * FROM Prodotto WHERE ID=$prod";
            $query = mysqli_query($conn, $select);
            if(!$query) {
                die(mysqli_error($conn));
            }
            if ($riga = mysqli_fetch_assoc($query)) {
                $prezzo = $riga['prezzo'];
                $totale += $prezzo * $quantita;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./style/style.css">
    <title>Electronics</title>
</head>
<body class='animate-gradient'>
    <main id="checkout-page">
        <h1>CHECKOUT</h1>
        <form action='./scripts/acquista.script.php' method='post'>

            <div id='tipoSpedizione'>
                <h3>Metodo di spedizione</h3>
                <div>
                    <input type='radio' name='tipoSpedizione' value='s' checked oninput='cambiaMetodo("s")'/>
                    <span>Spedizione  $8</span>
                </div>
                <div>
                    <input type='radio' name='tipoSpedizione' value='r' oninput='cambiaMetodo("r")'/>
                    <span>Ritiro in negozio</span>
                </div>
            </div>

            <div id='spedizione' class="container"> 
                <h3>Verrá consegnato a:</h3>
                <?php 
                    echo "<p>".$_SESSION['nome']."  ".$_SESSION['cognome']."<p>";
                    echo "<p>All'indirizzo: via ".$_SESSION['via']." ".$_SESSION['numero']."<br> ".$_SESSION['provincia']." ".$_SESSION['citta']." ".$_SESSION['cap'];
                    echo "<p>Email di conferma: ".$_SESSION['mail']."</p>";
                ?>
            </div>

            <div id='ritiro' class="container nascondi"> 
                <h3>Scegli uno dei punti vendita disponibili</h3>
                <table>
                    <tr>
                        <th></th>
                        <th>Regione</th>
                        <th>Città</th>
                        <th>Indirizzo</th>
                    </tr>
                <?php 
                    $select = "SELECT * FROM PuntoVendita WHERE";
                    foreach($pvDisponibili as $i=>$pv) {
                        if ($i === 0) {
                            $select .= " ID = $pv";
                        } else {
                            $select .= " OR ID = $pv";
                        }
                    }
                    $select .= ";";
                    $query = mysqli_query($conn, $select);
                    if(!$query) {
                        die(mysqli_error($conn));
                    }
                    while ($riga = mysqli_fetch_assoc($query)) {
                        $checked = "";
                        if ($riga['ID'] == 1) $checked = "checked"; 
                        echo "
                            <tr class='pv'>
                            <td><input type='radio' value='".$riga['ID']."' name='pv' $checked/></td>
                                <td>".$riga['regione']."</td>
                                <td>".$riga['citta']."</td>
                                <td>".$riga['via']." ".$riga['numero']."</td>
                            </tr>
                        ";
                    }
                ?>
                </table>

            </div>

            <div id="costi" class="container">
                <h3>Riepilogo</h3>
                <p>Subtotale: $<span id="subTotale"><?php echo $totale; ?></span></p>
                <p id="costiSped">Costi di spedizione: $8</p>
                <p>Totale: $<span id="totale"><?php echo $totale+8;?></span></p>
            </div>
            <div id='btn-container'>
                <button type='submit' name='acquista-submit'>Completa acquisto</button>
            </div>
        </form>
    </main>
    <script>
        var totale = document.querySelector("#totale")
        var subTotale = document.querySelector("#subTotale")
        function cambiaMetodo(metodo) {    
            if (metodo === "s") {
                document.querySelector("#costiSped").textContent = "Costi di spedizione: $8"        
                totale.textContent = Number(subTotale.textContent) + 8
            }
            else if (metodo === "r") {
                document.querySelector("#costiSped").textContent = "Costi di spedizione: $0"
                totale.textContent = Number(subTotale.textContent)
            }
            document.querySelector("#ritiro").classList.toggle("nascondi");
            document.querySelector("#spedizione").classList.toggle("nascondi");

        }
    </script>
</body>
</html>