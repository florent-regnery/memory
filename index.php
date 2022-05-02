<?php

require './classes/Memory.php';

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
}
// changer l'id avec la bdd


/**
 * Créer une page qui va récupérer les informations dans la table score avec
 * une jointure vers utilisateur pour afficher le login + score
 * order by score 
 * 
 * WHERE nombre_paires = :nombre_paires
 * Utiliser la méthode prepare + execute et récupérer le nombre_paires depuis le menu déroulant 
 * 
 */



// session_destroy();
// die;

if (!empty($_POST['start'])) {
    $memory = new Memory();
    $memory->startNewGame();
}
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

ob_start();
?>

<!-- <button name="open">
        open
    </button>
    <button name="close">
       close
    </button> 
    <button name="test">
        test animate
    </button> -->
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
    <?php if (isset($_SESSION['isWin']) && $_SESSION['isWin']) : ?>
        <p>Féliciation</p>
        <form method="POST">
            <input type="submit" value="start" name="start">
        </form>
    <?php endif; ?>
    <?php if (isset($_SESSION['nb_click'])) : ?>
        nombre de clique : <?= $_SESSION['nb_click'] ?>
    <?php endif; ?>
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

<?php
$content = ob_get_clean();
require_once 'template.php';
?>