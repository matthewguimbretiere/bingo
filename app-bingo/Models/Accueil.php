<?php

namespace App\Models;

function affErreur( $erreur, $redirection ) {
    global $twig;
    $twig->display(
    	$redirection,
    	[
    		'erreur' => $erreur
    	]
    );
}

class Accueil extends Base
{
    protected $tableName = 'membre';

    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function affErreur ( $msg, $page )
    {
        global $twig;
        $twig->display(
            $page . '.html.twig',
            [
                "msgErreur" => $msg
            ]
        );
    }

    /**
     * Connexion save
     */
    public function connexionSave($datas)
    {
        if (isset($datas['pseudo'])) {
            if ( $datas['pseudo'] != "" ) {
                $pseudo = htmlspecialchars($datas['pseudo']);

                $sql = "SELECT * FROM membre WHERE ip = :ip";
                $sth = self::$dbh->prepare( $sql );
                $sth->execute([":ip" => $_SERVER['REMOTE_ADDR']]);
                $ipexist = $sth->rowCount();

                $sql = "SELECT * FROM membre WHERE pseudo = :pseudo";
                $sth = self::$dbh->prepare( $sql );
                $sth->execute([":pseudo" => $pseudo]);
                $pseudoexist = $sth->rowCount();

                if ($ipexist == 0) {
                    if ($pseudoexist == 0) {
                        $sql = "INSERT INTO membre (pseudo,ip) VALUES (:pseudo, :ip)";
                        $sth = self::$dbh->prepare( $sql );
                        $sth->execute([
                            ":pseudo" => $pseudo,
                            ":ip" => $_SERVER['REMOTE_ADDR']
                        ]);

                        $sql = "SELECT * FROM membre WHERE ip = :ip";
                        $sth = self::$dbh->prepare( $sql );
                        $sth->execute([":ip" => $_SERVER['REMOTE_ADDR']]);
                        $user = $sth->fetch();

                        $_SESSION['id'] = $user['id'];
                        $_SESSION['pseudo'] = $user['pseudo'];
                        $_SESSION['ip'] = $user['ip'];
                       

                        redirect('/choix-cartons');
                    } else {
                        affErreur("Ce pseudo est déjà pris.", "index.html.twig");
                    }
                } else {
                    $sql = "SELECT * FROM membre WHERE ip = :ip";
                    $sth = self::$dbh->prepare( $sql );
                    $sth->execute([":ip" => $_SERVER['REMOTE_ADDR']]);
                    $user = $sth->fetch();
                    if ( $pseudo != $user['pseudo']) {
                        affErreur("Ce n'est pas le bon pseudo.", "index.html.twig");
                    } else {
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['pseudo'] = $user['pseudo'];
                        $_SESSION['ip'] = $user['ip'];

                        if ($user['id'] == 1) {
                            redirect('/admin');
                        } else {
                            redirect('/partie');
                        }                        
                    }
                }
            } else {
                affErreur("Veuillez remplir tous les champs", "index.html.twig");
            }
        } else {
            affErreur("Veuillez remplir tous les champs", "index.html.twig");
        }
    }
    
    /**
     * Sauvegarde du carton
     */
    public function choixCartonsSave( $datas )
    {
        $idJoueur = $_SESSION['id'];

        $sql = "SELECT * FROM cartons WHERE joueur = :joueur";
        $sth = self::$dbh->prepare($sql);
        $sth->execute([":joueur" => $idJoueur]);
        $count = $sth->rowCount();

        if ( $count == 0 ) {
            $sql = "INSERT INTO cartons(joueur,numB_1,numB_2,numB_3,numB_4,numB_5,numI_1,numI_2,numI_3,numI_4,numI_5,numN_1,numN_2,numN_3,numN_4,numN_5,numG_1,numG_2,numG_3,numG_4,numG_5,numO_1,numO_2,numO_3,numO_4,numO_5) VALUES
            (:joueur,:numB_1,:numB_2,:numB_3,:numB_4,:numB_5,:numI_1,:numI_2,:numI_3,:numI_4,:numI_5,:numN_1,:numN_2,:numN_3,:numN_4,:numN_5,:numG_1,:numG_2,:numG_3,:numG_4,:numG_5,:numO_1,:numO_2,:numO_3,:numO_4,:numO_5) ";
            $sth = self::$dbh->prepare( $sql );
            $sth->execute([
            ":joueur" => $idJoueur,
            ":numB_1" => $datas['numB_1'],
            ":numB_2" => $datas['numB_2'],
            ":numB_3" => $datas['numB_3'],
            ":numB_4" => $datas['numB_4'],
            ":numB_5" => $datas['numB_5'],
            ":numI_1" => $datas['numI_1'],
            ":numI_2" => $datas['numI_2'],
            ":numI_3" => $datas['numI_3'],
            ":numI_4" => $datas['numI_4'],
            ":numI_5" => $datas['numI_5'],
            ":numN_1" => $datas['numN_1'],
            ":numN_2" => $datas['numN_2'],
            ":numN_3" => $datas['numN_3'],
            ":numN_4" => $datas['numN_4'],
            ":numN_5" => $datas['numN_5'],
            ":numG_1" => $datas['numG_1'],
            ":numG_2" => $datas['numG_2'],
            ":numG_3" => $datas['numG_3'],
            ":numG_4" => $datas['numG_4'],
            ":numG_5" => $datas['numG_5'],
            ":numO_1" => $datas['numO_1'],
            ":numO_2" => $datas['numO_2'],
            ":numO_3" => $datas['numO_3'],
            ":numO_4" => $datas['numO_4'],
            ":numO_5" => $datas['numO_5']
            ]);

            $sql = "INSERT INTO classement(joueur) VALUES (:joueur)";
            $sth = self::$dbh->prepare( $sql );
            $sth->execute([
            ":joueur" => $idJoueur
            ]);
        } else {
            $sql = "DELETE FROM cartons WHERE joueur = :joueur";
            $sth = self::$dbh->prepare($sql);
            $sth->execute([":joueur" => $idJoueur]);

            $sql = "INSERT INTO cartons(joueur,numB_1,numB_2,numB_3,numB_4,numB_5,numI_1,numI_2,numI_3,numI_4,numI_5,numN_1,numN_2,numN_3,numN_4,numN_5,numG_1,numG_2,numG_3,numG_4,numG_5,numO_1,numO_2,numO_3,numO_4,numO_5) VALUES
            (:joueur,:numB_1,:numB_2,:numB_3,:numB_4,:numB_5,:numI_1,:numI_2,:numI_3,:numI_4,:numI_5,:numN_1,:numN_2,:numN_3,:numN_4,:numN_5,:numG_1,:numG_2,:numG_3,:numG_4,:numG_5,:numO_1,:numO_2,:numO_3,:numO_4,:numO_5) ";
            $sth = self::$dbh->prepare( $sql );
            $sth->execute([
            ":joueur" => $idJoueur,
            ":numB_1" => $datas['numB_1'],
            ":numB_2" => $datas['numB_2'],
            ":numB_3" => $datas['numB_3'],
            ":numB_4" => $datas['numB_4'],
            ":numB_5" => $datas['numB_5'],
            ":numI_1" => $datas['numI_1'],
            ":numI_2" => $datas['numI_2'],
            ":numI_3" => $datas['numI_3'],
            ":numI_4" => $datas['numI_4'],
            ":numI_5" => $datas['numI_5'],
            ":numN_1" => $datas['numN_1'],
            ":numN_2" => $datas['numN_2'],
            ":numN_3" => $datas['numN_3'],
            ":numN_4" => $datas['numN_4'],
            ":numN_5" => $datas['numN_5'],
            ":numG_1" => $datas['numG_1'],
            ":numG_2" => $datas['numG_2'],
            ":numG_3" => $datas['numG_3'],
            ":numG_4" => $datas['numG_4'],
            ":numG_5" => $datas['numG_5'],
            ":numO_1" => $datas['numO_1'],
            ":numO_2" => $datas['numO_2'],
            ":numO_3" => $datas['numO_3'],
            ":numO_4" => $datas['numO_4'],
            ":numO_5" => $datas['numO_5']
            ]);
        } 
    }

    /**
     * Suppression du carton
     */
    public function choixCartonsSupp()
    {
        $sql = "DELETE FROM cartons WHERE joueur = :joueur";
        $sth = self::$dbh->prepare($sql);
        $sth->execute([":joueur" => $idJoueur]);
    }

    /**
     * Obtenir tous les cartons
     */
    public function getAllCartons()
    {
        $sql = "SELECT * FROM cartons";
        $sth = self::$dbh->prepare($sql);
        $sth->execute();

        $array = array();

        foreach ($sth->fetchAll() as $carton) {
            $sql = "SELECT * FROM membre WHERE id = :id";
            $sth = self::$dbh->prepare($sql);
            $sth->execute(
                [
                    ":id" => $carton['joueur']
                ]
            );

            $infos = $sth->fetch();

            array_push($array, [
                "pseudo" => $infos['pseudo'],
                "cartonn" => $carton
            ]);
        }

        return $array;
    }

    /**
     * Obtenir le classement
     */
    public function getClassement()
    {
        $sql = "SELECT * FROM classement ORDER BY nbrRestant DESC";
        $sth = self::$dbh->prepare($sql);
        $sth->execute();
        $classement = $sth->fetchAll();

        $array = array();

        foreach ($classement as $theclassement) {
            $sql = "SELECT * FROM membre WHERE id = :id";
            $sth = self::$dbh->prepare($sql);
            $sth->execute(
                [
                    ":id" => $theclassement['joueur']
                ]
            );
            $infos = $sth->fetch();

            array_push($array, [
                "Plus que " . $theclassement['nbrRestant'] . " numéros pour le carton pour " . $infos['pseudo']
            ]);
        }
        
        return $array;
    }

    /**
     * Nombre de lignes
     */
    public function ligne( $nbr )
    {
        $sql = "SELECT * FROM laligne";
        $sth = self::$dbh->prepare($sql);
        $sth->execute();
        $count = $sth->rowCount();

        if($count == 0) {
            $sql = "INSERT INTO laligne(ligne) VALUES (:ligne)";
            $sth = self::$dbh->prepare($sql);
            $sth->execute([":ligne" => $nbr]);
        } else {
            $sql = "UPDATE laligne SET ligne = :ligne";
            $sth = self::$dbh->prepare($sql);
            $sth->execute([":ligne" => $nbr]);
        }
    }

    /**
     * Ajout du numéro bdd
     */
    public function numeros($num)
    {
        $sql = "INSERT INTO numeros(num) VALUES (:num)";
        $sth = self::$dbh->prepare($sql);
        $sth->execute([":num" => $num]);
    }

    /**
     * Retrait d'un dans le classement
     */
    public function retrait($id)
    {
        $sql = "UPDATE classement SET nbrRestant = nbrRestant - 1 WHERE joueur = :id";
        $sth = self::$dbh->prepare($sql);
        $sth->execute([":id" => $id]);
    }

    /**
     * Classement 2
     */
    public function getClassementt()
    {
        $sql = "SELECT * FROM classement ORDER BY nbrRestant DESC";
        $sth = self::$dbh->prepare($sql);
        $sth->execute();
        $classement = $sth->fetchAll();

        $array = array();

        foreach ($classement as $theclassement) {
            $sql = "SELECT * FROM membre WHERE id = :id";
            $sth = self::$dbh->prepare($sql);
            $sth->execute(
                [
                    ":id" => $theclassement['joueur']
                ]
            );
            $infos = $sth->fetch();

            array_push($array, [
                "Plus que " . $theclassement['nbrRestant'] . " numéros pour le carton pour " . $infos['pseudo']
            ]);
        }
        
        header( 'Content-Type: application/json' );
        echo json_encode([
        'classements' => $array
        ]);
    }
    
    /**
     * Continue de la partie
     */
    public function continue()
    {
        $sql = "DELETE FROM gagnant";
        $sth = self::$dbh->prepare($sql);
        $sth->execute();
    }

    /**
     * get infos gagnant
     */
    public function getInfoGagnant()
    {
        $chaine = "";
        
        $sql = "SELECT * FROM gagnant ORDER BY id ASC LIMIT 1";
        $sth = self::$dbh->prepare($sql);
        $sth->execute();
        $ipexist = $sth->rowCount();
        $legagnant = $sth->fetch();

        if ($ipexist==0) {
            $chaine = $chaine . "<style>#infoGagnant{display:none}</style>" ;
            $chaine = $chaine . '<h1 id="titreGagnant"></h1>';

            if($_SESSION['id']=="1") {
                $chaine = $chaine . '<button id="continuerBtn" class="bouton2">Continuer</button>';
                $chaine = $chaine . '<input type="text" hidden="hidden" id="infoGagnantExist" value="0">';
            } else {
                $chaine = $chaine . '<h3>La partie va recommencer dans un instant</h3>';
            }         
            
            $chaine = $chaine . '<h2 style="display: none;" id="gagnantVerif">0</h2>';
            
        } else {
            $chaine = $chaine . '<style>#infoGagnant{display:flex}</style>';
            $chaine = $chaine . '<h1 id="titreGagnant">Félicitation à ' . $legagnant['gagnant'] . ' qui à gagné avec ' . $legagnant['nbrligne'] . '</h1>';
            if($_SESSION['id']=="1") {
                $chaine = $chaine . '<button id="continuerBtn" class="bouton2">Continuer</button>';
                $chaine = $chaine . '<input type="text" hidden="hidden" id="infoGagnantExist" value="1">';
            } else {
                $chaine = $chaine . '<h3>La partie va recommencer dans un instant</h3>';
            }  
            $chaine = $chaine . '<h2 style="display: none;" id="gagnantVerif">1</h2>';
        }


        header( 'Content-Type: application/json' );
        echo json_encode([
        'infos' => $chaine
        ]);
    }

    /**
     * Obtenir un carton
     */
    public function getCarton()
    {
        $sql = "SELECT * FROM cartons WHERE joueur = :id";
        $sth = self::$dbh->prepare($sql);
        $sth->execute([":id" => $_SESSION['id']]);
        $carton = $sth->fetch();

        return $carton;
    }

    /**
     * Load ligne
     */
    public function load_ligne()
    {
        $sql = "SELECT * FROM laligne ORDER BY id DESC LIMIT 1";
        $sth = self::$dbh->prepare($sql);
        $sth->execute();
        $infos = $sth->fetch();

        header( 'Content-Type: application/json' );
        echo json_encode([
        'infos' => $infos['ligne']
        ]);
    }

    /**
     * Load tableau
     */
    public function load_tableau()
    {
        $sql = "SELECT * FROM numeros ORDER BY id DESC";
        $sth = self::$dbh->prepare($sql);
        $sth->execute();
        $nums = $sth->fetchAll();
        $j = 0;
        $chaine = "";
        foreach($nums as $key) {
            $j++;
            $chaine = $chaine . '<h1 id="lenumero' . $j .'" style="display: none;">' . $key['num'] .'</h1>';
        }
        $chaine = $chaine . '<h1 id="total" style="display: none;">' . $j . '</h1>';

        header( 'Content-Type: application/json' );
        echo json_encode([
        'infos' => $chaine
        ]);
    }

    /**
     * load numéros
     */
    public function load_numeros()
    {
        $sql = "SELECT * FROM numeros ORDER BY id DESC LIMIT 5";
        $sth = self::$dbh->prepare($sql);
        $sth->execute();
        $nums = $sth->fetchAll();
        $chaine = "";
        foreach ($nums as $key) {
            $chaine = $chaine . '<img src="./assets/img/boule/boule_'. $key['num'] .'.png">';
        }
        header( 'Content-Type: application/json' );
        echo json_encode([
        'infos' => $chaine
        ]);
    }

    /**
     * win
     */
    public function win( $ligne )
    {
        $sql = "INSERT INTO gagnant(gagnant, nbrligne) VALUES(:gagnant, :nbrligne)";
        $sth = self::$dbh->prepare($sql);
        $sth->execute([
            ":gagnant" => $_SESSION['pseudo'],
            ":nbrligne" => $ligne
        ]);
    }
}
?>