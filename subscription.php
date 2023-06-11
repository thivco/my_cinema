<?php
include_once "database.php";
include_once "header.php";

$landingStatement = "select user.id, firstname, lastname, subscription.name as 'subscription' from ((user left join membership on user.id = membership.id_user) left join subscription on subscription.id = membership.id_subscription);";
$letsgo = $pdo->query($landingStatement);
$array = $letsgo->fetchAll();



$namesearch = $_POST["namesearch"];
$namebutton = $_POST["namebutton"];
if (isset($namebutton)) {
    $displayUsers = "select user.id, firstname, lastname, subscription.name as 'subscription' from ((user left join membership on user.id = membership.id_user) left join subscription on subscription.id = membership.id_subscription) where firstname like '%$namesearch%' or lastname like '%$namesearch%' or concat(firstname, ' ', lastname) like '%$namesearch%' or concat(lastname, ' ', firstname) like '%$namesearch%';";
    $query = $pdo->prepare($displayUsers);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $query->execute();
    $array = $query->fetchAll();
}


$newSubButton = $_POST['newSubButton'];
$newSub = $_POST['newSub'];
if (isset($newSubButton)) {

    $statement = "select * from membership where id_user =" . $_POST['valid'] . ";";
    $query = $pdo->prepare($statement);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $query->execute();
    $array = $query->fetchAll();

    $subscriptionButton = (count($array) != 0) ? "update membership set membership.id_subscription = $newSub where id_user =" . $_POST['valid'] . ";" : "insert into membership (id_user, id_subscription) values (" . $_POST['valid'] . ", $newSub);
    ";
    $quero = $pdo->prepare($subscriptionButton);
    $quero->setFetchMode(PDO::FETCH_ASSOC);
    $quero->execute();
    // echo $subscriptionButton;
}


$deleteButton = $_POST['delete'];
if (isset($deleteButton)) {

    $deleteSub = "delete from membership where id_user = " . $_POST['valid'] . ";";
    $deleting = $pdo->prepare($deleteSub);
    $deleting->setFetchMode(PDO::FETCH_ASSOC);
    $deleting->execute();
    echo $deleteSub;

    // echo $newSub . " la et " . $_POST['valid'];
}

// $totalentries = "select count(*) as entries from user;";
// $query = $pdo->query($totalentries);
// $result = $query->fetch();
// $nbentries = $result('entries');
// $perpage = 15;
// $pages = ceil($nbentries/$perpage);
?>

<form action="" method="post">
    <input type="text" name="namesearch" placeholder="Prénom/nom">
    <input type="submit" name="namebutton" value="Recherche">
</form>


<?php
foreach ($array as $val) {
    $yourid = $val['id'];
     ?>
<a href="id=<?php echo $yourid ?>">
    <?php
    echo $val['id'] . " " . $val['firstname'] . " " . $val['lastname'] . " " .  $val['subscription'];
    // var_dump($val);
    ?>
    </a>
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
        <input type="hidden" name="valid" value="<?php echo $val['id']; ?>">
        <input type="hidden" name="subCheck" value="<?php echo $val['subscription']; ?>">
    </form>

<?php } ?>
<form action="" method="post">
</form>


<?php
include_once "footer.php";
?>