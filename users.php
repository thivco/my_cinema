<?php

//Récupération de la page actuelle
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $page = (int) strip_tags($_GET['page']);
} else {
    $page = 1;
}

//P A G I N A T I O N
$perpage = 10;
$nb_pages = ceil($nb_users/$perpage);
$firstpage = ($page*$perpage)-$perpage;
$prevPage = "'users.php?page=" . $page-1 . "'";
$nextPage = "'users.php?page=" . $page+1 . "'";


include_once "database.php";
// La requête de base


$landingStatement = "select user.id, firstname, lastname, city, subscription.name as 'subscription' from ((user left join membership on user.id = membership.id_user) left join subscription on subscription.id = membership.id_subscription) limit $firstpage, $perpage;";
$letsgo = $pdo->query($landingStatement);
$array = $letsgo->fetchAll();
include_once "header.php";
// echo $array[0]['nombre'];


$namesearch = $_POST["namesearch"];
$namebutton = $_POST["namebutton"];

if (isset($namebutton)) {

    //On récupère le nombre d'entrées
    $sql1 = "select count(user.id) as 'number' from ((user left join membership on user.id = membership.id_user) left join subscription on subscription.id = membership.id_subscription) where firstname like '%$namesearch%' or lastname like '%$namesearch%' or concat(firstname, ' ', lastname) like '%$namesearch%' or concat(lastname, ' ', firstname) like '%$namesearch%';";
    $query = $pdo->prepare($sql1);
    $query->execute();
    $result = $query->fetch();
    $nb_users = (int) $result['number'];
    // echo $nb_users;


    //La recherche en elle même
    $statement = "select user.id, firstname, lastname, city, subscription.name as 'subscription' from ((user left join membership on user.id = membership.id_user) left join subscription on subscription.id = membership.id_subscription) where firstname like '%$namesearch%' or lastname like '%$namesearch%' or concat(firstname, ' ', lastname) like '%$namesearch%' or concat(lastname, ' ', firstname) like '%$namesearch%' limit $firstpage, $perpage;";
    $query = $pdo->prepare($statement);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $query->execute();
    $array = $query->fetchAll();
}

?>
<div class="users__content">
    <form action="" method="post">
        <input type="text" name="namesearch" placeholder="Prénom/nom">
        <input type="submit" name="namebutton" value="Recherche">
    </form>
    <table class="users__table">
        <tr>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Abonnement</th>
            <th>Ville</th>
        </tr>


        <?php foreach ($array as $val) {

            $idlink = 'user.php?id=' . $val['id'] . "'" ?>
            <tr class="users__item">
                <td><a href='<?php echo $idlink ?>'><?php echo $val['firstname'] ?></a></td>
                <td><?php echo $val['lastname'] ?></td>
                <td><?php echo $val['subscription'] ?></td>
                <td><?php echo $val['city'] ?></td>
                <td><input type="hidden" name="userid" <?php echo "value=" . $val['id'] . '"' ?></td>
            </tr><?php }                ?>

    </table>
<div class="users__nav">
    <?php if($page!=1){?>

        <a href=<?php echo $prevPage?>>Previous page</a>
    <?php } 
    if($page!=30){?>
        <a href=<?php echo $nextPage?>>Next page</a>
    <?php } ?>
</div>

</div>

<?php
include_once "footer.php";
?>