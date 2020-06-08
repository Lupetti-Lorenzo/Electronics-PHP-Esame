<?php 
    session_start();
    if (!isset($_SESSION['userUid']) || !isset($_POST['user-submit'])) {
        header("Location: ./index.php");
        exit();
    }
    require "header.php";
?>
<main id='userPage'>
    <div id='info-user'>
        <h2>Informazioni personali</h2>
        <?php 
            echo "<p>Username: ".$_SESSION['userUid']."</p>";
            echo "<p>Email: ".$_SESSION['mail']."</p>";
            echo "<p>Nome: ".$_SESSION['nome']."<br> Cognome:  ".$_SESSION['cognome']."<p>";
            echo "<p>Indirizzo: via ".$_SESSION['via']." ".$_SESSION['numero']." ".$_SESSION['citta']." ".$_SESSION['cap']." ".$_SESSION['provincia'];
            
        ?>
    </div>
    
    <div id='ordini'>
    <h2>I miei ordini</h2>
    <?php 
        //seleziono gli ordini del cliente
        $select = "SELECT * FROM Ordine WHERE IDCliente = ".$_SESSION['userId'].";";
        require "./scripts/databasehandler.script.php";
        $query = mysqli_query($conn, $select);
        while ($ordine = mysqli_fetch_assoc($query)) {            
            echo "<div class='ordine'>"; 
            echo "
                <p>ID ordine: ".$ordine['ID']."</p>
                <p>Data ordine: ".$ordine['dataOrdine']."</p>
            ";   
            if ($ordine['RitiroInNegozio'] == FALSE) {
                echo "
                    <p>Il tuo ordine é stato ricevuto e la sede centrale provvedera a spedirlo</p>
                ";
            }
            else {
                //seleziono i dati del pv in base all ordine
                $select2 = "SELECT * FROM PuntoVendita WHERE ID=".$ordine['IDPuntoVendita'].";";
                $query2 = mysqli_query($conn, $select2);
                $pv = mysqli_fetch_assoc($query2);
                echo "
                    <p>Il tuo ordine é pronto per essere ritirato dalla sede di ".$pv['citta']."</p>
                    <p>All indirizzo: ".$pv['via']." ".$pv['numero']." ".$pv['cap']." -  Cellulare: ".$pv['cellulare']."</p>
                ";
            }
            echo "<div class='order-products'>";
            //seleziono i dati dei prodotti in base all ordine
            $idOrdine = $ordine['ID'];
            $selectProds = "SELECT * FROM Prodotto WHERE ID IN (SELECT IDProdotto FROM DettagliOrdine WHERE IDOrdine = $idOrdine);";
            $prodotti = mysqli_query($conn, $selectProds);
            if(!$prodotti)  die(mysqli_error($conn));
            while($riga = mysqli_fetch_assoc($prodotti))
            {
                //seleziono la quantita acquistata del prodotto
                $selectqnt = "SELECT quantita FROM DettagliOrdine WHERE IDOrdine=".$ordine['ID']." AND IDProdotto =".$riga['ID'].";";
                $queryqnt = mysqli_query($conn, $selectqnt);
                if(!$queryqnt)  die(mysqli_error($conn));
                $quantita = mysqli_fetch_assoc($queryqnt);
                echo "
                        <div class='order-product'>    
                            <img src='".$riga['immagine']."'alt='no img'/>
                            <div class='product-name'>
                                    <p>".$riga['nome']."</p>
                                    <p>Prezzo: $".$riga['prezzo']."- Quantita:  ".$quantita['quantita']."</p>
                            </div> 
                        </div>
                ";
            }
            echo "</div></div>";
        }
    ?>
    </div>
</main>
</body>
</html>