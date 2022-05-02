<?php
require './classes/Carte.php';
class Memory
{
    private $nbPaires;

    public function __construct($nbPaires = 0)
    {
        $this->nbPaires = $nbPaires;
    }
    private function buildPlateau()
    {
        $plateaux = [];
        $images = ["1.jpg", "2.jpg", "3.jpg", "4.jpg", "5.jpg", "6.jpg", "7.jpg", "8.jpg", "9.jpg", "10.jpg", "11.jpg", "12.jpg"];

        foreach (array_rand($images, $this->nbPaires) as $index) {
            $carte1 = new Carte("dos", "./assets/images/$images[$index]");
            $carte2 = new Carte("dos", "./assets/images/$images[$index]");
            $plateaux = [...$plateaux, $carte1, $carte2];
        }

        shuffle($plateaux);
        $_SESSION['plateau'] = $plateaux;
        return  $_SESSION['plateau'];
    }


    public function getPlateau()
    {
        return  isset($_SESSION['plateau']) ? $_SESSION['plateau'] : $this->buildPlateau();
    }

    public function afficheCarte()
    {
        if (isset($_POST['id_carte'])) {
            $_SESSION['nb_click'] = isset($_SESSION['nb_click']) ? $_SESSION['nb_click'] + 1 : 1;
            $_SESSION['plateau'][$_POST['id_carte']]->etat = "face";
            if (!isset($_SESSION['comparer'])) {
                $_SESSION['comparer'] = [];
            }
            //On insert les cartes à comparer dans une variable de session
            $_SESSION['comparer'][] = $_POST['id_carte'];
            if (count($_SESSION['comparer']) === 2) {
                header('Refresh: 1;');
            }
        }
    }

    public function comparerCarte()
    {
        if (isset($_SESSION['comparer']) && count($_SESSION['comparer']) === 2) {
            if ($_SESSION['plateau'][$_SESSION['comparer'][0]]->image !== $_SESSION['plateau'][$_SESSION['comparer'][1]]->image) {
                $_SESSION['plateau'][$_SESSION['comparer'][0]]->etat = 'dos';
                $_SESSION['plateau'][$_SESSION['comparer'][1]]->etat = 'dos';
            } else {
                $_SESSION['isWin'] = $this->checkIsWin();
                if ($_SESSION['isWin']) {
                    $pdo = new PDO('mysql:host=localhost;dbname=memory', 'root', '');
                    $sql = "INSERT INTO `score` (`id_utilisateur`, `score_user`, `date`, `nombre_paires`) VALUES (:id_utilisateur, :score_user, :date, :nb_paires)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'id_utilisateur' => $_SESSION['user'],
                        'score_user' => $_SESSION['nb_click'],
                        'date' => (new DateTime())->format('y-m-d'),
                        'nb_paires' => $_SESSION['nb_paires'],
                    ]);
                }
            }
            unset($_SESSION['comparer']);
        }
    }


    private function checkIsWin()
    {
        $cartesDos = array_filter($_SESSION['plateau'], function($carte) {
           return $carte->etat === 'dos';
        });
        return count($cartesDos) === 0;
    }

    public function startNewGame() {

        unset( 
            $_SESSION['nb_click'],
            $_SESSION['nb_paires'],
            $_SESSION['isWin'],
            $_SESSION['comparer'],
            $_SESSION['plateau']
        );
        header('Refresh: 1;');
    }
}   
