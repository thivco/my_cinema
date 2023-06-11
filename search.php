    <form action="" method="post">
        <input type="text" name="keywords" placeholder="Nom">
        <input type="text" name="distributor" placeholder="Producteur">
        <select id="genre" name="genre">
            <option value="0">Sélectionnez un genre</option>
            <option value="1">Action</option>
            <option value="3">Adventure</option>
            <option value="2">Animation</option>
            <option value="7">Biography</option>
            <option value="5">Comedy</option>
            <option value="8">Crime</option>
            <option value="4">Drama</option>
            <option value="13">Family</option>
            <option value="9">Fantasy</option>
            <option value="10">Horror</option>
            <option value="6">Mystery</option>
            <option value="12">Romance</option>
            <option value="11">Sci-Fi</option>
            <option value="14">Thriller</option>
        </select>
        <input type="submit" name="valider" value="Search">
    </form>
    <?php if ($display == "yes") {
    ?>
        <p><?php
            if ($nb_movies > 1) {
                echo $nb_movies . " résultats trouvés";
            } else {
                echo $nb_movies . " résultat trouvé";
            }

            if (($nb_movies) > 0) { ?>
        <div class="moviecards">
            <?php foreach ($tab as $val) { ?>
                <div class="moviecard">

                    <h1><?php echo $val["Titre"]; ?></h1>
                    <p>Genre : <?php echo $val["Genre"]; ?></p>
                    <p>Produit par : <?php echo $val["Producteur"]; ?></p>
                </div>
    <?php }
            }
        }
    ?>
    
        </div>

        <?php if ($page != 1) { ?>
            <div class="users__nav">
<a href=<?php echo $prevPage ?>>Previous page</a>
<?php }
if ($page != $nb_pages && $nb_movies > $perpage) { ?>
<a href=<?php echo $nextPage ?>>Next page</a>
</div>
<?php } ?>