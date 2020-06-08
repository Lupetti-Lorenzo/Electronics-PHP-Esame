<?php 
require "header.php";
require './scripts/databasehandler.script.php';

$categorie = [];
if (isset($_POST['categorie'])) {
    $categorie = [];
   foreach($_POST['categorie'] as $categoria) {
       array_push($categorie,  mysqli_real_escape_string($conn, $categoria));
   }
}

$ordine = '';
if (isset($_POST['ordine'])) {
   $ordine = mysqli_real_escape_string($conn, $_POST['ordine']);
}
?>


<main>
    <form action="<?=$_SERVER["PHP_SELF"]?>" method='post' id='filters'> 
        <h1>Prodotti</h1>
        Filtra per: <select name='ordine'>
            <option value='' <?php if($ordine === '') echo 'selected';?>> Data di inserimento </option>
            <option value='DESC' <?php if($ordine === 'DESC') echo 'selected';?>> Prezzo dal piu alto </option>
            <option value='ASC' <?php if($ordine === 'ASC') echo 'selected';?>> Prezzo dal piu basso </option>
        </select>
        </br>
        Categoria: 
        Hardware <input <?php foreach($categorie as $categoria) {if($categoria === 'Hardware') echo 'checked';} ?> type='checkbox' value='Hardware' name='categorie[]'/>
        Cuffie <input <?php foreach($categorie as $categoria) {if($categoria === 'Cuffie') echo 'checked';} ?> type='checkbox' value='Cuffie' name='categorie[]'/>
        Accessori <input <?php foreach($categorie as $categoria) {if($categoria === 'Accessori') echo 'checked';} ?> type='checkbox' value='Accessori' name='categorie[]'/>
        </br>
        <button type='submit' name='filtra-submit'>Filtra</button>
    </form>  
    
    <div id='products-grid'>
        <?php            
            $select = "SELECT * FROM Prodotto";
            if($categorie){
                $select = "SELECT * FROM Prodotto WHERE categoria in (";
                foreach($categorie as $i=>$categoria) {
                    $select = $i === 0 ? $select : $select.',';    
                    $select .= "'$categoria'";
                }
                $select .= ')';
            }
            if ($ordine) $select .= " ORDER BY prezzo $ordine";
            $query = mysqli_query($conn, $select.";");
            
            if(!$query) {
                die(mysqli_error($conn));
            }

            while($riga = mysqli_fetch_assoc($query))
            {
                echo "
                    <form action='./scripts/aggiungiAlCarrello.script.php' method='post'>    
                        <div class='product'>    
                            <img src='".$riga['immagine']."'alt='no img'/>
                            <div class='product-name'>
                                    <p>".$riga['nome']."</p>
                                    <p> -- $".$riga['prezzo']."</p>
                                    <p> Categoria: ".$riga['categoria']."</p>
                                    <section>
                                        <button name='carrello-submit' type='submit'>Aggiungi</button> 
                                        <input type='number' min='1' max='20' value = '1' name='quantita' placeholder='quantita'/> 
                                    </section>
                                    <input type='number' name='prodotto' value='".$riga['ID']."'/> 
                            </div> 
                        </div>
                    </form>
                ";
            }
            mysqli_close($conn);
        ?>

    </div>
    
</main>

<?php 
require "footer.php";
?>