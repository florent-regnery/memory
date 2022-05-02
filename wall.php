<?php
require_once './fame.php';
ob_start();
?>

<h2>Wall of Fame</h2>

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

<section>
    <table class="tabletop">
        <thead>
            <tr>
                <th colspan="3">TOP 10 : Joueur</th>
            </tr>
        </thead>
        <tbody>
            <tr class="titre_top">
                <td>Place</td>
                <td>Nom</td>
                <td>Score</td>
            </tr>
            <?php foreach ($best_scores as $index => $score) : ?>
                <tr>
                    <td class="place"># <?= $index + 1 ?> </td>
                    <td><?= $score['login'] ?></td>
                    <td class="score"><?= $score['score_user'] ?></td>
                <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    $content = ob_get_clean();
    require_once 'template.php';
    ?>