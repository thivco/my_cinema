<?php
include_once "database.php";
include_once "header.php";

$userid = $_GET['id'];

// Affichage de l'historique
$query = "select user.firstname, user.lastname, movie.title, membership.id as 'membership_id', membership.id_subscription as 'subnum', subscription.name as 'subname' from ((((user left join membership on user.id = membership.id_user) left join membership_log on membership_log.id_membership = membership.id) left join movie on movie.id = id_session) left join subscription on membership.id_subscription = subscription.id) where user.id=$userid";
$statement = $pdo->query($query);
$arr = $statement->fetchAll();

// Changement de l'abonnement
$subValue = $_POST["newSub"];
if ($arr[0]['subnum'] > 0) {
    $changeSubQuery = "update membership set membership.id_subscription = $subValue where id_user = $userid;";
} else {
    $changeSubQuery = "insert into membership (id_user, id_subscription) values ($userid, $subValue);";
}
if (isset($subValue)) {
    $changeSub = $pdo->prepare($changeSubQuery);
    $changeSub->setFetchMode(PDO::FETCH_ASSOC);
    $changeSub->execute();
}

// Suppression de l'abonnement
$deleteSubQuery = "delete from membership where id_user = $userid";
$deleteSub = $pdo->prepare($deleteSubQuery);
if (isset($_POST['delete'])) {
    $deleteSub->execute();
}

// Ajout d'un film dans l'historique
$memberID = $arr[0]['membership_id'];
$newMovie = $_POST['addToHistory'];
$buttonMovie = $_POST['buttonAddToHistory'];
if(isset($buttonMovie)){
    $addMovieQuery = "insert into membership_log values ($memberID, $newMovie);";
    $addMovie = $pdo->prepare($addMovieQuery);
    $addMovie->execute();
}

?>
<div class="user__info">
    <p class="user__name"><?php echo $arr[0]['firstname'] . " " . $arr[0]['lastname'] ?> </p>
    <?php if ($arr[0]['subnum'] > 0) { ?>
        <p><?php echo "Membre " . $arr[0]['subname'] ?></p>
    <?php } ?>
</div>
<form action="" method="post">
    <select name="newSub" id="">
        <option value="0" disabled selected>Abonnement</option>
        <option value="1">VIP</option>
        <option value="2">GOLD</option>
        <option value="3">Classic</option>
        <option value="4">Pass Day</option>
    </select>
    <input type="submit" name="newSubButton" value="Actualiser">
    <input type="submit" name="delete" value="Supprimer l'abonnement">
</form>
<form action="" method="post">
    <input type="text" name="addToHistory">
    <input type="submit" name="buttonAddToHistory" value="Ajouter à l'historique">
</form>
<button id="showHistory">Afficher l'historique</button>
<div class="moviecards">

    <?php foreach ($arr as $value) { ?>
        <p class="moviecard"><?php echo $value['title'] ?></p>
    <?php } ?>
</div>

<?php include_once "footer.php";