<?php

require './classes/Memory.php';
session_start();


// session_destroy();
// die;


if (isset($_SESSION['nb_paires'])) {
    $memory = new Memory($_SESSION['nb_paires']);
    if (isset($_POST['clique_carte'])) {
        $memory->afficheCarte();
    } else {
        $memory->comparerCarte();
    }
} else {
    if (!empty($_POST['nb_paires'])) {
        $_SESSION['nb_paires'] = $_POST['nb_paires'];
        $memory = new Memory($_SESSION['nb_paires']);
    }
}


var_dump($_POST);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./assets/style.css">
    <title>Document</title>
</head>

<body>

    <!-- start game -->
    <?php if (!isset($_SESSION['nb_paires'])) : ?>
        <form method="POST">
            <select name="nb_paires">
                <option value="">
                    -- Selectionner le nombre de paire --
                </option>
                <?php foreach (range(3, 12) as $number) : ?>
                    <option value="<?= $number ?>">
                        <?= $number ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="jouer">
        </form>
    <?php else : ?>
        <!-- On boucle sur le "plateau" contenant les cartes -->
        <div class="bloc_jeu">
            <?php foreach ($memory->getPlateau() as $key => $carte) : ?>
                <!-- Si l'état de la carte est "dos", on gére l'affichage -->
                <form class="carte_face" action="" method="post">
                    <button name="clique_carte" type="image" <?= isset($_SESSION['comparer']) && count($_SESSION['comparer']) === 2 ? 'disabled' : '' ?>>
                        <img src="<?= $carte->etat === 'dos' ? $carte->image_dos : $carte->image ?>" alt="image_carte_dos">
                    </button>
                    <?php if ($carte->etat == "dos") : ?>
                        <input type="hidden" value="<?= $key ?>" name="id_carte">
                    <?php endif; ?>
                </form>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</body>

</html>