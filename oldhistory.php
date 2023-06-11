<?php

include_once "database.php";
include_once "header.php";
$namesearch = $_GET["namesearch"];
$namebutton = $_GET["namebutton"];
if (isset($namebutton)) {
    $displayUsers = "select user.id, firstname, lastname, subscription.name as 'subscription' from ((user left join membership on user.id = membership.id_user) left join subscription on subscription.id = membership.id_subscription) where firstname like '%$namesearch%' or lastname like '%$namesearch%' or concat(firstname, ' ', lastname) like '%$namesearch%' or concat(lastname, ' ', firstname) like '%$namesearch%';";
    $query = $pdo->prepare($displayUsers);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $query->execute();
    $array = $query->fetchAll();
}

$moviehistory = $_POST["addToHistory"];

if(isset($_POST['validHistory'])){
    $query = "insert into history (user_id, movie_id) values ('$moviehistory', '$idval')";
    $request = $pdo->query($query);
}

?>

<form action="" method="get">
    <input type="text" name="namesearch" id="" placeholder="Prénom/Nom">
    <input type="submit" name="namebutton" value="Rechercher">
</form>
<?php
foreach($array as $value){
    $idval = $value['id'];
    ?>

<h1><?php echo $value['firstname'] . " " . $value['lastname'];?></h1>
<!-- <a href=<?php 
// echo "history.php?id=$idval"
?> class="profileButton">Lien</a> -->
<form action="post">    
    <input type="text" name="addToHistory">
    <input type="button" name="validHistory" value="Ajouter à l'historique">
</form>
    <?php
}



include_once "footer.php"; 