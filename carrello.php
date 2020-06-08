<?php 
    require"header.php";
    $disabled = "";
    if (!isset($_SESSION['userUid'])) {
        $disabled = "disabled";
    }
?>
<main id='carrello-page'>
    <h1>CARRELLO</h1>
    <?php 
        if (isset($_GET['errorChek'])) {
            if ($_GET['errorChek'] == "nonDispo") {
                echo "<p class='error'>Impossibile procedere, uno o piu prodotti non erano disponibili in quelle quantitá.</p>";
            }
            if ($_GET['errorChek'] == "queryError") {
                echo "<p class='error'>Errore con il database durante il checkout, riprova.</p>";
            }
        }
        if (isset($_GET['eliminaArt'])) {
            if ($_GET['eliminaArt'] == "no") {
                echo "<p class='error'>Errore nell'eliminazione del prodotto.</p>";
            }
        }
    ?>

    <form action='./scripts/eliminaArticolo.script.php' method='post' id='elimina'></form>
    <?php
    if (empty($_SESSION['carrello'])) {
        echo '<p class="error">Non cé nessun oggetto nel carrello</p>';
    }
    else 
    {
        echo "
            <form action='./checkout.php' method='post'>
            <table>
            <tr>
                <th>Articolo</th>
                <th>Prezzo</th>
                <th>Quantita</th>
                <th>Totale</th>
            </tr>
        ";
        $tottot = 0;
        for ($i = 0; $i < count($_SESSION['carrello']); $i = $i + 2) {
            require './scripts/databasehandler.script.php';
            $prod = $_SESSION['carrello'][$i];
            $quantita = $_SESSION['carrello'][$i + 1];
            $select = "SELECT * FROM Prodotto WHERE ID=$prod";
            $query = mysqli_query($conn, $select);
            if(!$query) {
                die(mysqli_error($conn));
            }
            if ($riga = mysqli_fetch_assoc($query)) {
                $prezzo = $riga['prezzo'];
                $totale = $prezzo * $quantita;
                $tottot += $totale;
                echo "
                    <tr class='cart-element'>
                        <td class='articolo'><img src='".$riga['immagine']."'alt='no img'/>
                        <p class='cart-name'><b>".$riga['nome']."</b></p>
                        </td>
                        <td>".$riga['prezzo']."$ </td>
                        <td>
                            <input type='number' min='1' value='$quantita' name='quantita[]' placeholder='quantita' class='quantita' oninput='aggiornaTotale($i, $prezzo)'/>
                            <button name='elimina-articolo' value='$prod' type='submit' form='elimina'><i class='fas fa-trash-alt'></i></button> 
                        </td>
                        <td><span class='totale'>$totale</span>$</td>
                    </tr>
                ";
            }
        }
        $errorDis = "";
        if ($disabled !== "") $errorDis =  "<p class='error'>Devi essere registrato per procedere</p>";
        echo "
            </table>
            <div id='checkout'>
                <p>Totale: 
                    <span id='tot'>
                        $tottot
                    </span>
                $</p>
                $errorDis
                <button name='checkout-submit' type='submit' $disabled class='$disabled'>Checkout</button> 
            </div>
            </form>
            ";
    }
    
    ?> 
    
</main>

<script>
    var inputs = document.getElementsByClassName("quantita")
    var totali = document.getElementsByClassName("totale")
    var tot = document.querySelector("#tot")

    function aggiornaTotale(i, prezzo) {
        tot.textContent =  Number(tot.textContent) - Number(totali[i / 2].textContent)
        totali[i / 2].textContent = inputs[i / 2].value * prezzo
        tot.textContent =  Number(tot.textContent) + Number(totali[i / 2].textContent)
    }
</script>
</body>
</html>