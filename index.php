<?php
include_once "database.php";

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $page = (int) strip_tags($_GET['page']);
} else {
    $page = 1;
}

//P A G I N A T I O N
$perpage = 12;
$nb_pages = ceil($nb_movies/$perpage);
$firstpage = ($page*$perpage)-$perpage;
$prevPage = "'index.php?page=" . $page-1 . "'";
$nextPage = "'index.php?page=" . $page+1 . "'";

$submit = $_POST["valider"];
$keywords = $_POST["keywords"];
$genre = $_POST["genre"];
$distributor = $_POST["distributor"];

$query = "select count(title) as 'number' from (((movie left join movie_genre ON movie.id = movie_genre.id_movie) left join genre on genre.id = movie_genre.id_genre) left join distributor on distributor.id = movie.id_distributor)";
$res = $pdo->query($query);
$result = $res->fetch();
$nb_movies = (int) $result['number'];
echo $nb_movies;


$query = "select title as 'Titre', genre.name as 'Genre', distributor.name as 'Producteur' from (((movie left join movie_genre ON movie.id = movie_genre.id_movie) left join genre on genre.id = movie_genre.id_genre) left join distributor on distributor.id = movie.id_distributor) limit $firstpage, $perpage;";
$res = $pdo->query($query);
$tab = $res->fetchAll();
$display="yes";

if (isset($submit)) {
    $sql1 = "select count(title) as 'number' from (((movie left join movie_genre ON movie.id = movie_genre.id_movie) left join genre on genre.id = movie_genre.id_genre) left join distributor on distributor.id = movie.id_distributor) where title like '%$keywords%';";
    $query = $pdo->prepare($sql1);
    $query->execute();
    $result = $query->fetch();
    $nb_movies = (int) $result['number'];

    $query = "select title as 'Titre', genre.name as 'Genre', distributor.name as 'Producteur' from (((movie left join movie_genre ON movie.id = movie_genre.id_movie) left join genre on genre.id = movie_genre.id_genre) left join distributor on distributor.id = movie.id_distributor) where title like '%$keywords%'";
    if($genre != 0){
        $query .= " and genre.id = $genre ";   }
        if(isset($distributor) && !empty(trim($distributor))){
            $query .= " and distributor.name like '%$distributor%'";
        }
        $query .= "limit $firstpage, $perpage;";
        $res = $pdo->query($query);
        // $res = $pdo->prepare($query);
        // $res->setFetchMode(PDO::FETCH_ASSOC);
        // $res->execute();
        $tab = $res->fetchAll();
        $display = "yes";
    }
    
    
// Ajouter la colonne des genres dans movie, et se baser sur ça pour afficher q ue les bons films
// Tableau des genres, foreach genre on crée un tableau qui fait une query différente (select genre from movie)
include_once "header.php";
include_once "search.php";
include_once "footer.php";