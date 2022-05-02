<?php

//Récupère les infos des 10 premiers scores au général
$params = [];
$bdd = new PDO('mysql:host=localhost;dbname=memory', 'root', '');
$sql = "SELECT login, score_user 
    FROM utilisateur u
    INNER JOIN score s ON s.id_utilisateur = u.id";


//Sert pour la génération du tableau
if (!empty($_POST["nb_paires"])) {
    $sql .= " WHERE nombre_paires = :nb_paires";
    $params = ['nb_paires' => $_POST["nb_paires"]];
}

$sql .= " ORDER BY s.score_user ASC LIMIT 10";
$stmt = $bdd->prepare($sql);
$stmt->execute($params);
$best_scores = $stmt->fetchAll();
